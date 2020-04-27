<script src="js/penjualan.js" async type="text/javascript" ></script>

<?php $abc = isset($_SESSION['filter_ID_Penjualan']) ? $_SESSION['filter_ID_Penjualan'] : ""; ?>

<div class="left_content">
    <button type="button" onclick="LaodForm('setter_penjualan')"><i class="far fa-plus-circle"></i> Add Order</button>
    <button type="button" onclick="LaodForm('setter_penjualan_invoice')"><i class="far fa-plus-circle"></i> Add Invoice</button>
    <input type='text' value="" placeholder="Search Client" class='search client' id='Search_Client' onchange="SearchClient()" autocomplete="off">
    <input type='text' value="" placeholder="Search Deskripsi, No Order, No. Invoice" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="<?= $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px;">
    <input type="hidden" id="test" value="<?= $abc; ?>">
</div>

<div id="setter_penjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>