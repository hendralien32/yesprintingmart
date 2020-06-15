<?php
session_start();
require_once "../../function.php";

$no_invoice = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";
$jenis_wo = isset($_POST['AksesEdit']) ? $_POST['AksesEdit'] : "";


$sql =
    "SELECT
        count(penjualan.oid) as Jumlah_Order,
        penjualan.jenis_wo,
        penjualan.kode
    FROM
        penjualan
    WHERE
        penjualan.jenis_wo = '$jenis_wo' and (penjualan.no_invoice = '$no_invoice' or penjualan.no_invoice = '0')
    ";
$n = 0;

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows >= 1) :
    // output data of each row
    $d = $result->fetch_assoc();
    $Jumlah_Order = "$d[Jumlah_Order]";
    $jenis_wo = "$d[jenis_wo]";
    $kode = "$d[kode]";
else :
    $Jumlah_Order = "0";
    $jenis_wo = "";
    $kode = "";
endif;
?>

<h3 class='title_form'>FORM YESCOM INVOICE</h3>

<input type="hidden" id="no_invoice" value="<?= $no_invoice; ?>">
<input type="hidden" id="InvoiceList_JenisWO_check" value="<?= $jenis_wo; ?>">
<input type="hidden" id="InvoiceList_Kode_check" value="<?= $kode; ?>">
<input type="hidden" id="InvoiceList_Qty_check" value="<?= $Jumlah_Order; ?>">
<div id="outstandinglist"></div>
<div id="Result"></div>