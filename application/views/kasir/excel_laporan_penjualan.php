
<table class="table-print" width="100%">
    <thead>
        <tr>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" width="24px" >ID</th>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" >Tanggal</th>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" >Nama</th>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" >Qty</th>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" >Harga Satuan (Rp.)</th>
            <th style="text-align: center; padding:4px; border-top:1px dotted; border-bottom:1px dotted; text-transform:uppercase; white-space: nowrap;" >Jumlah Bayar (Rp.)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key=>$item) {
            
        ?>
        <tr>
            <td style="white-space: nowrap; padding: 4px; text-align: left; border-bottom: 1px dotted" > <?= $item->transaction_code ?> </td>
            <td style="white-space: nowrap; padding: 4px; text-align: left; border-bottom: 1px dotted" > <?= $item->created_at ?> </td>
            <td style="white-space: nowrap; padding: 4px; text-align: left; border-bottom: 1px dotted" > <?= $item->product_name ?> </td>
            <td style="white-space: nowrap; padding: 4px; text-align: right; border-bottom: 1px dotted" > <?= $item->qty ?> </td>
            <td style="white-space: nowrap; padding: 4px; text-align: right; border-bottom: 1px dotted" > <?= number_format($item->price, 0, ',', '.') ?> </td>
            <td style="white-space: nowrap; padding: 4px; text-align: right; border-bottom: 1px dotted" > <?= number_format($item->price * $item->qty, 0, ',', '.') ?> </td>        
        </tr>
        <?php }?>
    </tbody>
</table>
