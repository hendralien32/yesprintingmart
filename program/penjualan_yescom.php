<?php
require_once "../function.php";
?>

<script src="js/penjualan_yescom.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="LaodSubForm('penjualan_yescom')"><i class="far fa-plus-circle"></i> Add Order</button>
    <button type="button" onclick="LaodForm('penjualan_invoice_yescom')"><i class="fas fa-receipt"></i> Add Invoice</button>
    <input type="text" id="search_data" class='search data' placeholder="Search Nama Client, Deskripsi, ID, SO" onchange="search_data()">
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
</div>

<div id="list_PenjualanYes">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>