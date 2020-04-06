<script src="js/penjualan.js" async type="text/javascript" ></script>


<div class="left_content">
    <button type="button" onclick="LaodForm('setter_penjualan')"><i class="far fa-plus-circle"></i> Add Order</button>
    <button type="button" onclick="LaodForm('setter_penjualan_invoice')"><i class="far fa-plus-circle"></i> Add Invoice</button>
    <input type='text' value="" placeholder="Search Client" class='search client' id='Search_Client' onchange="SearchClient()" autocomplete="off">
    <input type='text' value="" placeholder="Search Deskripsi, No Order, No. Invoice" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="<?php echo $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()">
    <input type="hidden" id="test" value="<?php echo $_SESSION['filter_ID_Penjualan']; ?>">
</div>

<div id="setter_penjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>