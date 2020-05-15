<?php
    require_once "../function.php";
?>

<script src="js/database_pricelist.js" async type="text/javascript" ></script>

<div class="left_content">
    <?php if($_SESSION['level']=="admin") : ?>
    <button type="button" onclick="LaodForm('database_pricelist')"><i class="far fa-plus-circle"></i> Add Pricelist</button>
    <?php endif; ?>
    <input type="text" id="nama_pricelist" class='search data' placeholder="Nama bahan" onchange="search_data()">
    <select id="kode_barang" onchange="search_typedata()" style='margin-right: 10px'>
        <option value=''>Pilih Kode Bahan</option>
        <option value='digital' selected>Digital Printing</option>
        <option value='large format'>Large Format</option>
        <option value='latex'>Indoor Latex</option>
    </select>
    <select id="warna" onchange="search_typedata()" style='margin-right: 10px'>
        <option value='FC'>Fullcolor</option>
        <option value='BW'>Greyscale</option>
    </select>
    <select id="sisi_cetak" onchange="search_typedata()">
        <option value=''>Pilih sisi Cetak</option>
        <option value="1" selected>1 Sisi</option>
        <option value="2">2 Sisi</option>
    </select>
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted Pricelist</label>
    </span>
</div>

<div id="list_DatabasePricelist">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>