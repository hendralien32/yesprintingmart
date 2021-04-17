<?php
require_once '../function_new.php';

$days = cal_days_in_month(CAL_GREGORIAN, substr($months,5,2), substr($months,0,4));
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
            <?php if($add_Database_User == 'Y') : ?>
                <button class='add_form' data-form='absensi' onclick="showForm('database','Add_User','','lightbox-large')"><i class="fal fa-plus"></i> Tambah User</button>
            <?php endif; ?>
        </div>
    </div>
    <div class='plugin-search'>
        <input type="text" id='search_user' placeholder="Search Nama Karyawan" autocomplete="off">
    </div>

    <div class='ajax_load'>

    </div>
</div>