<?php
require_once "../function.php";
?>

<script src="js/laporan_SetoranBank.js" async type="text/javascript"></script>

<div class="left_content">
    <SELECT id="jenis_laporan" onchange="jenis_laporan()">
        <?php
        $array_kode = array(
            "" => "Show All",
            "Cash" => "Cash / DP",
            "CC" => "Transfer / Gesek"
        );
        foreach ($array_kode as $kode => $kd) :
            echo "<option value='$kode'>$kd</option>";
        endforeach;
        ?>
    </select>
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" value="<?= $date; ?>" max="<?= $date ?>">
</div>

<div id="list_LapSetoranBank">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>