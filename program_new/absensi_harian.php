<?php
require_once '../function.php';

$idSetter = 
    isset($_SESSION['Setter_ID']) 
        ? $_SESSION['Setter_ID'] 
        : "";

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
            <button class='add_form' data-form='absensi' onclick="showForm('absensi_individu','Insert_Absensi','','lightbox-medium')"><i class="fal fa-plus"></i> Absensi Harian</button>
            <button class='add_form' data-form='absensi_individu' onclick="showForm('absensi_individu','Form_Absensi_Individu','','lightbox-medium')"><i class="fal fa-plus"></i> Absensi Personal</button>
        </div>
    </div>
    <div class='plugin-search'>
        <input type="text" id='search_user' placeholder="Search Nama Karyawan" autocomplete="off">
        <input type="date" id='search_drBln' data-placeholder="Dari Bulan" value="<?php echo "$months-01"; ?>">  
        <input type="date" id='search_keBln' data-placeholder="ke Bulan" value="<?php echo "$months-$days"; ?>">
        <select id='search_absensi'>
            <option value=''>List Absensi</option>
            <option value='permisi'>List Permisi</option>
            <option value='lembur'>List Lembur</option>
        </select>
    </div>

    <div class='ajax_load'>

    </div>
</div>