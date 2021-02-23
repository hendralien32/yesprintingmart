<?php
require_once '../function.php';
$SalesOrder_Jumlah = isset($_SESSION['SalesOrder_Jumlah']) ? $_SESSION['SalesOrder_Jumlah'] : "0";

?>

<script src="js/sales_order.js" async type="text/javascript"></script>

<div class='plugin-top'>
    <div class='item'>
        <div class='left_title'>Sales Order</div>
        <div class='right_title'><?= $SalesOrder_Jumlah ?> Order</div>
    </div>
    <div class='item'>
        <button id='button-search' onclick="search_display()"><i class="fas fa-search-plus"></i></button>
        <button><i class="fal fa-plus"></i> Add Order</button>
        <button><i class="fal fa-receipt"></i> Create Invoice</button>
    </div>
</div>

<div id='plugin-search' class='display-none'>
    <input type="text" id='search_client' placeholder="Search Nama Client" autocomplete="off">
    <input type="text" id='search_data' placeholder="Search Deskripsi" autocomplete="off">
    <input type="date" id='search_drTgl' data-placeholder="Dari Tgl">
    <input type="date" id='search_keTgl' data-placeholder="Ke Tgl" max="<?= $date; ?>">
    <!-- <button><i class="fal fa-search"></i> Search</button> -->
</div>

<div id='ajax_load'>

</div>