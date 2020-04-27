<script src="js/invoce_penjualan.js" async type="text/javascript" ></script>

<div class="left_content">
    <input type='text' value="" placeholder="Search Client" class='search client' id='Search_Client' onchange="SearchClient()" autocomplete="off">
    <input type='text' value="" placeholder="Search Invoice" class='search invoice' id='Search_Invoice' onchange="Searchinvoice()" autocomplete="off">
    <input type='text' value="" placeholder="Search Deskripsi, No Order" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="<?= $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px">
</div>

<div id="invoce_penjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>