<?php
require_once "../function.php";
?>

<script src="js/Laporan_StockBrg.js" async type="text/javascript"></script>

<div class="left_content">
    <!-- <button type="button" onclick="LaodForm('adjusting_stock')"><i class="far fa-plus-circle"></i> Adjusting Stock</button> -->
    <SELECT id="type_stock" onchange="jenis_stock()">
        <option value="">Pilih Jenis Stock</option>
        <?php
        $array_kode = array(
            "KRTS" => "Kertas",
            "SPRT" => "Sparepart",
            "TNT" => "Tonner / Tinta"
        );
        foreach ($array_kode as $kode => $kd) :
            echo "<option value='$kode'>$kd</option>";
        endforeach;
        ?>
    </select>
    <input type="date" data-placeholder="Dari Tgl" id="dari_bulan" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tgl" id="ke_bulan" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
</div>

<div id="list_LapStockBrg">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>