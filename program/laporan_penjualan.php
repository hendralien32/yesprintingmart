<?php
require_once "../function.php";
?>

<script src="js/laporan_penjualan.js" async type="text/javascript"></script>

<div class="left_content">
    <SELECT id="jenis_laporan" onchange="jenis_laporan()">
        <?php
        $array_kode = array(
            "yesprint" => "Yes Printingmart",
            "yescom" => "Yes Communication"
        );
        foreach ($array_kode as $kode => $kd) :
            echo "<option value='$kode'>$kd</option>";
        endforeach;
        ?>
    </select>
    <input type="month" data-placeholder="Dari Bulan" id="dari_bulan" onblur="SearchFrom()" value="<?= $months; ?>" max="<?= $months ?>">
    <input type="month" data-placeholder="Ke Bulan" id="ke_bulan" onblur="SearchTo()" max="<?= $months ?>" disabled="disabled" readonly>
</div>

<div id="list_LapPenjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>