<?php
require_once '../function.php';

$idSetter = 
    isset($_SESSION['Setter_ID']) 
        ? $_SESSION['Setter_ID'] 
        : "";
?>

<script src="js/absensi.js" async type="text/javascript"></script>

<div class='calender-container'>
    <div class='plugin-top'>
        <div class='item'>
            <div class='left_title'>Absensi</div>
            <div id='right_title'>10 Karyawan</div>
        </div>
        <div class='item'>
            <button class='button-search'><i class="fas fa-search-plus"></i></button>
            <button class='add_form' data-form='absensi'><i class="fal fa-plus"></i> Add Absensi</button>
            <button class='add_form' data-form='absensi_individu'><i class="fal fa-plus"></i> Add Absensi Personal</button>
        </div>
    </div>
    <div class='plugin-search'>
        <input type="text" id='search_user' placeholder="Search Nama Client" onChange="SearchData()" autocomplete="off">
        <input type="month" id='search_drBln' data-placeholder="Dari Bulan" value="<?= $months; ?>" onChange="SearchDate()">
        <input type="month" id='search_keBln' data-placeholder="Ke Bulan" value="<?= $months; ?>" onChange="SearchDate()">
    </div>

    <div class='ajax_load'>

    </div>
</div>