<?php
require_once "../function.php";
?>

<script src="js/List_PemesananStock.js" async type="text/javascript"></script>

<div class="left_content">
    <?php if ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting") : ?>
        <button type="button" onclick="LaodForm('StockBahan_LF')"><i class="far fa-plus-circle"></i> Order Bahan</button>
    <?php endif; ?>
    <input type="text" id="search_data" class='search data' placeholder="Kode Bahan, Kode Order, Nama Supplier" onchange="search_data()">
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
    <span>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Barang belum Diterima</label>
    </span>
</div>

<div id="list_StockBahan_LF">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>