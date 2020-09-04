<?php
require_once "../function.php";
?>

<script src="js/laporan_harian_konika.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="LaodSubForm('counter_Mesin')"><i class="far fa-plus-circle"></i> Add Counter</button>
    <input type="month" data-placeholder="Dari Bulan" id="dari_bulan" onblur="SearchFrom()" value="<?= $months; ?>" max="<?= $date ?>">
    <input type="month" data-placeholder="Ke Bulan" id="ke_bulan" onblur="SearchTo()" max="<?= $months ?>" disabled="disabled" readonly>
</div>

<div id="list_laporan_konika">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>