<?php

defined('BASEPATH') || exit('No direct script access allowed');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../../.env');
$dotenv->load();

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Option extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_transaction');
		$this->load->model('model_product');
		if (! $this->session->userdata('user_id')) {
			header('location:' . $_ENV['APP_HOST']);
		}
	}

	private function _validate()
	{
		$data                 = [];
		$data['error_string'] = [];
		$data['inputerror']   = [];
		$data['status']       = true;

		if ($this->input->post('nama_barang') === '') {
			$data['inputerror'][]   = 'nama_barang';
			$data['error_string'][] = 'Nama barang is required';
			$data['status']         = false;
		}

		if ($this->input->post('harga_beli') === '') {
			$data['inputerror'][]   = 'harga_beli';
			$data['error_string'][] = 'Harga beli is required';
			$data['status']         = false;
		}

		if ($this->input->post('harga_jual') === '') {
			$data['inputerror'][]   = 'harga_jual';
			$data['error_string'][] = 'Harga jual is required';
			$data['status']         = false;
		}

		if ($this->input->post('setok') === '') {
			$data['inputerror'][]   = 'setok';
			$data['error_string'][] = 'Setok is required';
			$data['status']         = false;
		}

		if ($this->input->post('satuan') === '') {
			$data['inputerror'][]   = 'satuan';
			$data['error_string'][] = 'Satuan is required';
			$data['status']         = false;
		}

		if ($data['status'] === false) {
			echo json_encode($data);

			exit();
		}
	}

	public function search_product()
	{
		$data = $this->model_product->search_product($_REQUEST['keyword']);
		echo json_encode($data);
	}

	public function add_keranjang()
	{
		$data = [
			'id'    => $this->input->post('product_id'),
			'name'  => $this->input->post('product_name'),
			'price' => $this->input->post('selling_price'),
			'qty'   => $this->input->post('product_qty'),
		];
		echo json_encode([
			'status' => $this->cart->insert($data),
			'total'  => $this->cart->total(),
		]);
	}

	public function minus_keranjang()
	{
		$data = [
			'id'    => $this->input->post('product_id'),
			'name'  => $this->input->post('product_name'),
			'price' => $this->input->post('selling_price'),
			'qty'   => $this->input->post('product_qty'),
		];
		echo json_encode([
			'status' => $this->cart->update($data),
			'total'  => $this->cart->total(),
		]);
	}

	public function list_shoping_cart()
	{
		$data = [];
		$no   = 1;

		foreach ($this->cart->contents() as $items) {
			$row   = [];
			$row[] = $no;
			$row[] = $items['name'];
			$row[] = 'Rp. ' . number_format($items['price'], 0, '', '.') . ',-';
			$row[] = '
                  <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-sm">
                      <li class="page-item">
                        <a class="page-link bg-danger" href="javascript:void(0)" onclick="minus_cart(' . $items['id'] . ', ' . "'" . $items['name'] . "'" . ', ' . $items['price'] . ', ' . $items['qty'] . ', ' . "'" . $items['rowid'] . "'" . ')">
                          <i class="fas fa-minus fas-xs text-white"></i>
                        </a>
                      </li>
                      <li class="page-item border border-light pl-2 pr-2">
                        ' . $items['qty'] . '
                      </li>
                      <li class="page-item">
                        <a class="page-link bg-success" href="javascript:void(0)" onclick="plus_cart(' . $items['id'] . ', ' . "'" . $items['name'] . "'" . ', ' . $items['price'] . ' )">
                          <i class="fas fa-plus fas-xs text-white"></i>
                        </a>
                      </li>
                    </ul>
                  </nav>
                ';

			$row[] = 'Rp. ' . number_format($items['qty'] * $items['price'], 0, '', '.') . ',-';
			$row[] = '<a href="javascript:void(0)" style="" onclick="delete_cart(' . "'" . $items['rowid'] . "'" . ')">
                  <i class="fas fa-times text-danger"></i>
                </a>';
			$data[] = $row;
			$no++;
		}
		$output = [
			'data' => $data,
		];
		echo json_encode($output);
	}

	public function auto_update()
	{
		$tgl  = date('Y-m-d');
		$data = ['sstatus_promo' => 0];
		$this->db->where('ahir_promo <', $tgl);
		$this->db->update('barang', $data);

		return true;
	}

	public function save_orders()
	{
		$this->load->model('model_merchant');
		$bayar   = $this->input->post('bayar');
		$kembali = $this->input->post('kembali');
		$toko    = $this->model_merchant->find_merchant();
		$no      = 1;
		$datas = $this->cart->contents();
		$this->cetak($datas);

		$output  = '';

		$output .= '<div>';

		$output .= '
              <div style="text-align: center; font-size: 20px; font-weight: bold;">' . $toko->merchant_name . '</div>
              <div style="text-align: center">' . $toko->merchant_address . ' ' . $toko->merchant_telephone . '</div>
              <div style="text-align: center" id="time_transaction" data-transaction="' . date('Y-m-d  h:i:s') . '">' . date('Y-m-d  h:i:s') . '</div>
              ';
		$output .= '<div style="border-top:1px dashed; border-bottom:1px dashed; margin: 20px 0;">'; // body

		$output .= '<div style="display: flex; border-bottom:1px dashed; margin-bottom: 10px;">
                  <div style="width: 10%; font-weight: bold;">No</div>
                  <div style="width: 40%; font-weight: bold;">Nama</div>
                  <div style="width: 15%; font-weight: bold; text-align: center;">Qty</div>
                  <div style="width: 35%; font-weight: bold;">Sub Total</div>
                </div>
              ';

		foreach ($datas as $row) {
			$output .= '
                <div style="display: flex; margin-bottom: 10px;">
                  <div style="width: 10%;">' . $no++ . '</div>
                  <div style="width: 40%;">' . $row['name'] . '</div>
                  <div style="width: 15%; text-align: center;">' . $row['qty'] . '</div>
                  <div style="width: 35%;">Rp.' . $row['price'] . '</div>
                </div>';
		}

		$output .= '</div>';

		$output .= '
                <div style="display: flex;">
                  <div style="width: 60%; text-align: right;">Total</div>
                  <div style="width: 5%; text-align: center;">:</div>
                  <div style="width: 35%;">Rp.' . number_format($this->cart->total(), 0, ',', '.') . '</div>
                </div>
              ';

		$output .= '
                <div style="display: flex;">
                  <div style="width: 60%; text-align: right;">Bayar</div>
                  <div style="width: 5%; text-align: center;">:</div>
                  <div style="width: 35%;">Rp.' . number_format($bayar, 0, ',', '.') . '</div>
                </div>
              ';

		$output .= '
                <div style="display: flex;">
                  <div style="width: 60%; text-align: right;">Kembali</div>
                  <div style="width: 5%; text-align: center;">:</div>
                  <div style="width: 35%;">Rp.' . $kembali . '</div>
                </div>
              ';

		$output .= '<div style="text-align:center; margin: 20px 0;">
                  Terimakasih atas kunjungan anda
                </div>';
		$output .= '</div>';
		echo $output;
	}

	public function shoping()
	{
		$time_transaction = $this->input->post('time_transaction');
		$response         = [];
		if ($this->cart->contents() !== []) {
			$order_id = md5(date('Y-m-d  h:i:s'));

			foreach ($this->cart->contents() as $cart) {
				$order = [
					'transaction_code' => $order_id,
					'created_by'       => $this->session->userdata('user_id'),
					'product_id'       => $cart['id'],
					'product_name'     => $cart['name'],
					'price'            => $cart['price'],
					'qty'              => $cart['qty'],
					'created_at'       => $time_transaction,
				];

				$res      = $this->model_product->get_product_qty($cart['id']);
				$last_qty = $res->product_qty - $cart['qty'];

				$this->model_transaction->create_order($order);
				$this->model_product->update_product_qty($cart['id'], $last_qty);
			}

			$this->cart->destroy();
			$response = [
				'status'  => 200,
				'message' => 'success',
			];
		} else {
			$response = [
				'status'  => 400,
				'message' => 'internal server error',
			];
		}
		echo json_encode($response);
	}

	public function delete_shoping_cart($rowid)
	{
		$res = $this->cart->update(
			[
				'rowid' => $rowid,
				'qty'   => 0,
			]
		);
		if (! $res) {
			echo json_encode(
				[
					'status'  => 200,
					'message' => 'Internal server error',
					'total'   => $this->cart->total(),
				]
			);

			return;
		}
		echo json_encode(
			[
				'status'  => 200,
				'message' => 'success',
				'total'   => $this->cart->total(),
			]
		);
	}

	public function data_penjualan()
	{
		$data_session = [
			'title'        => 'Penjualan',
			'active_class' => 'penjualan',
		];
		$this->session->set_userdata($data_session);
		$this->load->view('kasir/penjualan_view');
	}

	public function get_penjualan()
	{
		$this->load->model('model_transaction');
		$list = $this->model_transaction->get_datatables();
		$data = [];
		$no   = $_POST['start'];
		$n    = 0;

		foreach ($list as $barang) {
			$n++;
			$row    = [];
			$row[]  = $n;
			$row[]  = $barang->transaction_code;
			$row[]  = $barang->product_name;
			$row[]  = $barang->qty;
			$row[]  = $barang->user_name;
			$row[]  = $barang->price;
			$row[]  = $barang->price * $barang->qty;
			$row[]  = $barang->created_at;
			$data[] = $row;
		}

		$output = [
			'draw'            => $_POST['draw'],
			'recordsTotal'    => $this->model_transaction->count_all(),
			'recordsFiltered' => $this->model_transaction->count_filtered(),
			'data'            => $data,
		];
		echo json_encode($output);
	}

	public function laba()
	{
		$this->load->view('kasir/laba_view');
	}

	public function pengunjung()
	{
		$this->load->view('admin/pengunjung_view');
	}

	public function find_report_yearly_chart()
	{
		$data = [];

		for ($x = 0; $x < 12; $x++) {
			$this->db->select('SUM(price) AS price');
			$this->db->where('MONTH(created_at) =', $x + 1);
			$query  = $this->db->get('transactions');
			$data[] = $query->row()->price === null ? 0 : $query->row()->price;
		}
		$month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

		$output = [
			'month' => $month,
			'price' => $data,
		];
		echo json_encode($output);
	}

	public function cetak($datas = [])
	{
		try{
			/* Fill in your own connector here */
			$connector = new WindowsPrintConnector("kasir2");

			/* Information for the receipt */
			$tot = 0;
			$items = [];
			foreach($datas as $data){
				$str = $data['qty']." ".$data['name'];
				$str = strlen($str) <= 40 ? $str : substr($str, 0, 40) . '...';
				$tot += $data['qty'] * $data["price"];
				$items[] = new item($str, '');
				$items[] = new item(' ', number_format($data['qty'] * $data["price"], 0, '.', ','));
			}
			//$subtotal = new item('Subtotal', '12.95');
			//$tax = new item('A local tax', '1.30');
			$total = new item('Total', number_format($tot, 0, '.', ','));
			/* Date is kept the same for testing */
			$date = date('l jS \of F Y h:i:s A');
			//$date = "Monday 6th of April 2015 02:56:25 PM";

			/* Start the printer */
			//$logo = EscposImage::load("resources/escpos-php.png", false);
			$printer = new Printer($connector);

			/* Print top logo */
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			//$printer -> graphics($logo);

			/* Name of shop */
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> setEmphasis(true);
			$printer -> text("RUMAH UMKM SATU NUSANTARA\n");
			$printer -> setEmphasis(false);
			$printer -> text("Jalan Belidaan No.6, Cempedak Lobang, Kec. Sei Rampah, Kabupaten Serdang Bedagai, Sumatera Utara 20995\n");
			$printer -> selectPrintMode();
			//$printer -> text("Shop No. 42.\n");
			$printer -> feed();

			/* Title of receipt */
			$printer -> setEmphasis(true);
			$printer -> text("Tagihan\n");
			$printer -> setEmphasis(false);

			/* Items */
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer -> setEmphasis(true);
			$printer -> text(new item('', 'Rp.'));
			$printer -> setEmphasis(false);
			foreach ($items as $item) {
				$printer -> text($item);
			}
			$printer -> setEmphasis(true);
			$printer -> text(new item('', '----------'));
			// $printer -> setEmphasis(true);
			// $printer -> text($subtotal);
			$printer -> setEmphasis(false);
			$printer -> feed();

			/* Tax and total */
			//$printer -> text($tax);
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text($total);
			$printer -> selectPrintMode();

			/* Footer */
			$printer -> feed(2);
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> text("Terima kasih telah berbelanja.\n");
			// $printer -> text("For trading hours, please visit example.com\n");
			$printer -> feed(2);
			$printer -> text($date . "\n");

			/* Cut the receipt and open the cash drawer */
			$printer -> cut();
			$printer -> pulse();

			$printer -> close();
		}catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

/* A wrapper to do organise item names & prices into columns */

	}
}

class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;
        
        $sign = ($this -> dollarSign ? 'Rp ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
