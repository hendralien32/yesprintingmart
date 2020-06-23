<?php
require_once "../function.php";
?>

<script src="js/invoice_Penjualan_yescom.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="print_report()"><i class='fas fa-print' style='color:#fff'></i> Daily Invoice</button>
    <input type="text" id="search_data" class='search data' placeholder="Search Nama Client, Deskripsi, ID, SO" onchange="search_data()">
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
</div>

<div id="list_PenjualanYes">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>