<?php
require_once "../function.php";
$mesin_Session = isset($_SESSION['session_MesinDP']) ? $_SESSION['session_MesinDP'] : "";
?>

<script src="js/list_PemotonganStockDP.js" async type="text/javascript"></script>

<div class="left_content">
    <input type='text' value="" placeholder="Search ID Yescom, ID Yesprint, Nama Client" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" value="<?= $date; ?>" style="width:200px;">
    <SELECT id="type_mesin" onchange="session_mesin()" class='pointer'>
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
    <span id='Counter_mesin'>
 
    </span>
</div>

<div id="list_PemotonganStockDP">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>