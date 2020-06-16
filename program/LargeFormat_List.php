<script src="js/LargeFormat_List.js" async type="text/javascript"></script>

<?php $bahan_Session = isset($_SESSION['ListOrder_BahanLF']) ? $_SESSION['ListOrder_BahanLF'] : ""; ?>

<div class="left_content">
    <button type="button" onclick="LaodForm('LargeFormat_Order')"><i class="far fa-plus-circle"></i> Buka Order Cetak</button>
    <input type='text' value="" placeholder="Search ID Yescom, ID Yesprint, No. Invoice, Nama Client" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" value="<?= $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px;">
    <input type="hidden" id="session_bahan" value="<?= $bahan_Session; ?>">
</div>

<div id="setter_penjualan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>