<?php
    require_once "../function.php";
?>

<script src="js/penjualan_yescom.js" async type="text/javascript" ></script>

<div class="left_content">
    <button type="button" onclick="LaodSubForm('penjualan_yescom')"><i class="far fa-plus-circle"></i> Add Order</button>
    <button type="button" onclick="LaodForm('aaa')"><i class="far fa-plus-circle"></i> Add Invoice</button>
    <input type="text" id="search_data" class='search data' placeholder="Search Nama Client, Deskripsi, ID, SO" onchange="search_data()">
    <input type="date" value="<?= $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px;">
</div>

<div id="list_PenjualanYes">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>