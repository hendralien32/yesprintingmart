<?php
require_once "../function.php";
$mesin_Session = isset($_SESSION['session_MesinDP']) ? $_SESSION['session_MesinDP'] : "";
?>

<script src="js/laporan_harian_konika.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" onclick="LaodSubForm('counter_Mesin')"><i class="far fa-plus-circle"></i> Add Counter</button>
    <input type="month" data-placeholder="Dari Bulan" id="dari_bulan" class='pointer' onblur="SearchFrom()" value="<?= $months; ?>" max="<?= $date ?>">
    <input type="month" data-placeholder="Ke Bulan" id="ke_bulan" class='pointer' onblur="SearchTo()" max="<?= $months ?>" disabled="disabled" readonly>
    <SELECT id="type_mesin" onchange="session_mesin()" class='pointer'>
        <option value="">Pilih Mesin</option>
        <?php
        $array_kode = array(
            "Konika_C-1085" => "Konika C-1085",
            "Konika_C7000" => "Konika C-7000"
        );
        foreach ($array_kode as $kode => $kd) :
            if ($kode == "$mesin_Session") : $pilih = "selected";
            else : $pilih = "";
            endif;
            echo "<option value='$kode' $pilih>$kd</option>";
        endforeach;
        ?>
    </select>
</div>

<div id="list_laporan_konika">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>