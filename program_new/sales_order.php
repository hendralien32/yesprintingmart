<?php
require_once '../function.php';

?>

<script src="js/sales_order.js" async type="text/javascript"></script>

<div class='plugin-top'>
    <div class='item'>
        <div class='left_title'>Sales Order</div>
        <div id='right_title' class='right_title'></div>
    </div>
    <div class='item'>
        <button id='button-search' onclick="search_display()"><i class="fas fa-search-plus"></i></button>
        <button><i class="fal fa-plus"></i> Add Order</button>
        <button><i class="fal fa-receipt"></i> Create Invoice</button>
    </div>
</div>

<div id='plugin-search' class='display-none'>
    <input type="text" id='search_client' placeholder="Search Nama Client" onChange="SearchData()" autocomplete="off">
    <input type="text" id='search_data' placeholder="Search data ( Deskripsi, oid, No. Invoice )" onChange="SearchData()" autocomplete="off">
    <input type="date" id='search_drTgl' data-placeholder="Dari Tgl" value="<?= $date; ?>" max="<?= $date; ?>" onChange="SearchDate()">
    <input type="date" id='search_keTgl' data-placeholder="Ke Tgl" value="<?= $date; ?>" max="<?= $date; ?>" onChange="SearchDate()">
    <input type="number" id='search_limit' placeholder="Search Deskripsi" value="100" onChange="SearchData()">
</div>

<div id='ajax_load'>

</div>