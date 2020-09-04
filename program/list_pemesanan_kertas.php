<?php
require_once "../function.php";
?>

<script src="js/list_pemesanan_kertas.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('order_kertas')"><i class="far fa-plus-circle"></i> Order Kertas</button>
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
    <span>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick=''>
        <label class="label-checkbox100" for="Check_box">Show Barang belum Diterima</label>
    </span>
</div>

<div id="list_pemesanan_kertas">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>