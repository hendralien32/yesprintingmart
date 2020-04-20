<script src="js/pelunasan.js" async type="text/javascript" ></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('Multipayment')"><i class="far fa-plus-circle"></i> Multiple Payment</button>
    <input type='text' value="" placeholder="Search Client" class='search client' id='Search_Client' onchange="SearchClient()" autocomplete="off">
    <input type='text' value="" placeholder="Search Deskripsi, No Order, No. Invoice" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="" data-placeholder="Tanggal" id="dari_tanggal" onblur="SearchDate()">
    <input type="date" value="" data-placeholder="Tanggal" id="ke_tanggal" onblur="SearchDate()">
    SHOW Lunas
</div>

<div id="pelunasan_list">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>