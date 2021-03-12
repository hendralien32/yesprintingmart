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
            <button id='button-search'><i class="fas fa-search-plus"></i></button>
            <button><i class="fal fa-plus"></i> Add Absensi</button>
        </div>
    </div>
    <div class='plugin-search'>
        <input type="text" id='search_user' placeholder="Search Nama Client" onChange="SearchData()" autocomplete="off">
        <input type="date" id='search_drTgl' data-placeholder="Dari Tgl" value="<?= $date; ?>" max="<?= $date; ?>" onChange="SearchDate()">
        <input type="date" id='search_keTgl' data-placeholder="Ke Tgl" max="<?= $date; ?>" onChange="SearchDate()">
    </div>

    <div class='ajax_load'>

    </div>
</div>