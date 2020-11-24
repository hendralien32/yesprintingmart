<?php
$mesin_Session = isset($_SESSION['session_MesinDP']) ? $_SESSION['session_MesinDP'] : "";
$bahan_Session = isset($_SESSION['ListOrder_BahanDP']) ? $_SESSION['ListOrder_BahanDP'] : "";
?>

<!-- Update Mesin Di database Pricelist -->
<!-- UPDATE `digital_printing` SET `mesin` = 'Konika_C-1085', `tgl_cetak` = tgl_cetak WHERE `digital_printing`.`did` = 79321; -->

<script src="js/list_digital.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" id='button_rusak' onclick="LaodSubForm('maintenance_DP')" style='display:none'><i class="far fa-plus-circle"></i> Maintenance</button>
    <input type='text' value="" placeholder="Search ID Yescom, ID Yesprint, Nama Client" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px;">
    <input type="hidden" id="session_kertas" value="<?= $bahan_Session; ?>">
    <input type="hidden" id="session_mesinDP" value="<?= $mesin_Session; ?>">
    <SELECT id="type_mesin" onchange="session_mesin()">
        <option value="">Pilih Mesin</option>
        <?php
        $array_kode = array(
            "Konika_C-6085" => "Konika C-6085",
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

<div id="setter_penjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>