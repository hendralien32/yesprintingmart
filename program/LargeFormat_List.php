<?php
$mesin_Session = isset($_SESSION['session_mesin']) ? $_SESSION['session_mesin'] : "";
$bahan_Session = isset($_SESSION['ListOrder_BahanLF']) ? $_SESSION['ListOrder_BahanLF'] : "";
?>

<script src="js/LargeFormat_List.js" async type="text/javascript"></script>

<div class="left_content">
    <button type="button" id='button_order' onclick="LaodFormLF('LargeFormat')" style='display:none'><i class="far fa-plus-circle"></i> Buka Order Cetak</button>
    <button type="button" id='button_rusak' onclick="LaodFormLF('LargeFormat_Rusak')" style='display:none'><i class="far fa-plus-circle"></i> Rusak Cetak</button>
    <input type='text' value="" placeholder="Search ID Yescom, ID Yesprint, No. Invoice, Nama Client" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px;">
    <input type="hidden" id="session_bahan" value="<?= $bahan_Session; ?>">
    <input type="hidden" id="session_mesin" value="<?= $mesin_Session; ?>">
    <SELECT id="type_mesin" onchange="session_mesin()">
        <option value="">Pilih Mesin</option>
        <?php
        $array_kode = array(
            "Polaris" => "Polaris",
            "Xuli" => "Xuli",
            "HP Latex 360" => "HP Latex 360",
            "Graptech FC8000-100" => "Graptech FC8000-100"
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