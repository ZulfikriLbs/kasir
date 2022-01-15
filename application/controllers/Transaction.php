<?php defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_transaction');
    $this->load->model('model_product');
    if (!$this->session->userdata('user_id')) {
      header('location:http://localhost:8080');
    }
  }

  public function create_transaction(){
    $code_transaction = $this->input->post("code_transaction");
    $response = [];
    if ($this->cart->contents() != []) {
      foreach ($this->cart->contents() as $cart) {
        $order = [
          'transaction_code' => $code_transaction,
          'created_by' => $this->session->userdata('user_id'),
          'product_id' => $cart['id'],
          'product_name' => $cart['name'],
          'price' => $cart['price'],
          'qty' => $cart['qty'],
        ];

        $res = $this->model_product->get_product_qty($cart['id']);
        $last_qty = $res->product_qty - $cart['qty'];

        $this->model_transaction->create_order($order);
        $this->model_product->update_product_qty($cart['id'], $last_qty);
      }

      $this->cart->destroy();
      
      $response = array(
        "status" => 200,
        "message" => "success",
      );
    } else {
      $response = array(
        "status" => 500,
        "message" => "internal server error",
      );
    }
    echo json_encode($response);
	}
}