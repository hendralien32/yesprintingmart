<?php
session_start();
require_once "../../function.php";

$jenis_laporan = ($_POST['jenis_laporan'] != "") ? $_POST['jenis_laporan'] : "";
$dari_bulan = ($_POST['dari_bulan'] != "") ? $_POST['dari_bulan'] : $monts;
$ke_bulan = ($_POST['ke_bulan'] != "") ? $_POST['ke_bulan'] : $_POST['dari_bulan'];

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <thead>
        <tr>
            <th width="1%">#</th>
            <th width="10%">Tanggal</th>
            <th width="6%">Digital</th>
            <th width="6%">Kotak</th>
            <th width="6%">Potong</th>
            <th width="7%">Laminate</th>
            <th width="7%">LF</th>
            <th width="6%">Indoor</th>
            <th width="6%">Xuli</th>
            <th width="7%">Alat Tambahan</th>
            <th width="7%">Offset</th>
            <th width="6%">Design</th>
            <th width="6%">Delivery</th>
            <th width="6%">Lain</th>
            <th width="6%">diskon</th>
            <th width="7%">Total</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>