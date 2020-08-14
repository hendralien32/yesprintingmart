<?php
require_once "../function.php";
?>

<script src="js/StockBahan_LF.js" async type="text/javascript"></script>

<div class="left_content">
    <input type="text" id="search_data" class='search data' placeholder="Kode Bahan, Kode Order, Nama Supplier" onchange="search_data()">
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_habis()'>
        <label class="label-checkbox100" for="Check_box">Show Stock Habis</label>
    </span>
</div>

<div id="list_StockBahan_LF">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>