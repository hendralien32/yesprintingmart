<?php
require_once '../function.php';

?>

<script src="js/absensi.js" async type="text/javascript"></script>

<div class='calender-container'>
    <div class='plugin-top'>
        <div class='item'>
            <div class='left_title'><?= $_GET['page'] ?></div>
            <div id='right_title'></div>
        </div>
        <div class='item'>
            <button class='button-search'><i class="fas fa-search-plus"></i></button>
        </div>
    </div>
    <div class='plugin-search'>
        <input type="hidden" id='search_user' placeholder="Search Nama Karyawan" autocomplete="off">
        <input type="month" id='search_drBln' data-placeholder="Dari Bulan" value="<?= $months; ?>">  
        <input type="hidden" id='search_keBln' data-placeholder="ke Bulan" value="<?= $months; ?>">
    </div>

    <div class='ajax_load'>

    </div>
</div>