<?php
require_once "../function.php";
?>

<script src="js/StockBahan_LF.js" async type="text/javascript"></script>

<div class="left_content">
    <input type="text" id="search_data" class='search data' placeholder="Kode Bahan, Kode Order, Nama Supplier" onchange="search_data()">
    <select id="type_bahan" onchange="search_typedata()">
        <option value="D1">Bahan Terbuka</option>
        <option value="D2">Bahan Baru</option>
        <option value="D3">Bahan Habis</option>
    </select>
    <input type="text" id="session_mesin" value="Mesin : <?= $_SESSION['session_mesin'] ?>" readonly style="border:none">
</div>

<div id="list_StockBahan_LF">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>