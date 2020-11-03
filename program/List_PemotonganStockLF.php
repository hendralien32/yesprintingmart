<?php
require_once "../function.php";
?>

<script src="js/List_PemotonganStockLF.js" async type="text/javascript"></script>

<div class="left_content">
    <input type='text' value="" placeholder="Search ID Yescom, ID Yesprint, No. Invoice, Nama Client" class='search data' id='search' onchange="search_data()" autocomplete="off">
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
</div>

<div id="List_PemotonganStockLF">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>