<?php
    session_start();
    require_once "../../function.php";

?>

<h3 class='title_form'>Check Invoice Penjualan No. Invoice #<?= $_POST['data'] ?></h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>OID</th>
            <th>Deskripsi</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>107491</td>
            <td>Bahan : TIC 260gr<br>Sisi : 1<br>Qty : 10 Lembar</td>
            <td>Harga @ : 2.500</td>
        </tr>
    </tbody>
</table>
