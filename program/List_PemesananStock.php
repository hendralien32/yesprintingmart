<?php
require_once "../function.php";
?>

<script src="js/List_PemesananStock.js" async type="text/javascript"></script>

<div class="left_content">
    <?php if($_SESSION['level']=="admin" or $_SESSION['level']=="CS" or $_SESSION['level']=="accounting") : ?>
        <button type="button" onclick="LaodForm('StockBahan_LF')"><i class="far fa-plus-circle"></i> Order Bahan</button>
    <?php endif; ?>
    <input type="text" id="search_data" class='search data' placeholder="Kode Bahan, Kode Order, Nama Supplier" onchange="search_data()">
</div>

<div id="list_StockBahan_LF">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>