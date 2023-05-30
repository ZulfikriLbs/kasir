<?php

?>
<div id="print-area">
    <table width="100%" style="margin-bottom: 30px">
        <tr>
            <td style="white-space: nowrap;">
                <table style="white-space:nowrap;" width="100%">
                    <tr>
                        <td style="text-align: left">
                            <span style="text-decoration: uppercase;">PT SUMATRA FAN JAYA</span><br>
                            Jl. Bunga Lau No.17 - MEDAN<br>
                            Telp. (62)(61)8364581<br>
                            Faks. (62)(61)8360255<br>
                        </td>
                        <td></td>
                        <td width="210px" style="vertical-align: baseline; text-align: right">
                            <table>
                                <tr>
                                    <td style="text-align: left;">Laporan</td>
                                    <td style="text-align: left;">: <?= $title?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; padding-right: 10px;">Tanggal Cetak</td>
                                    <td style="text-align: left;">: <?= date("d/m/Y H:i:s") ?></td>
                                </tr>
                            </table>
                        </th>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <td style="text-align: center;">
                <h4><?= $title ?></h4>
                
                <p>Tanggal: <?= $tanggal[0] ?> s.d. <?= $tanggal[1] ?></p>   
            </td>
        </tr>
    </table>
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
                <td style="white-space: nowrap; padding: 4px; text-align: left; border-bottom: 1px dotted" > <?= $item->qty ?> </td>
                <td style="white-space: nowrap; padding: 4px; text-align: right; border-bottom: 1px dotted" > <?= number_format($item->price, 0, ',', '.') ?> </td>
                <td style="white-space: nowrap; padding: 4px; text-align: right; border-bottom: 1px dotted" > <?= number_format($item->price * $item->qty, 0, ',', '.') ?> </td>        
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>