<?php
require_once "../function.php";
?>

<script src="js/list_pemesanan_kertas.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('Tambah_StockDP')"><i class="far fa-plus-circle"></i> Add Stock</button>
    <button type="button" onclick="LaodForm('adjusting_stock')"><i class="far fa-plus-circle"></i> Adjusting Stock</button>
    <input type="text" id="search" class='search data' autocomplete="off" placeholder="Nama Bahan" onchange="search_data()">
    <input type="month" data-placeholder="Dari Bulan" id="dari_bulan" onblur="SearchFrom()" value="<?= $months; ?>" max="<?= $months ?>">
    <input type="month" data-placeholder="Ke Bulan" id="ke_bulan" onblur="SearchTo()" max="<?= $months ?>" disabled="disabled" readonly>
</div>

<div id="list_pemesanan_kertas">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>