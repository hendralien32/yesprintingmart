<?php
    require_once "../function.php";
?>

<script src="js/database_bahan.js" async type="text/javascript" ></script>

<div class="left_content">
    <?php if($_SESSION['level']=="admin") : ?>
    <button type="button" onclick="LaodForm('database_bahan')"><i class="far fa-plus-circle"></i> Add Bahan</button>
    <?php endif; ?>
    <input type="text" id="nama_bahan" class='search data' placeholder="Nama Bahan" onchange="search_data()">
    <select id="type_bahan" onchange="search_typedata()">
        <option value="">Pilih Jenis Bahan</option>
        <option value="KRTS">Kertas Digital</option>
        <option value="LF">Bahan Large Format</option>
        <option value="INDOOR">Bahan Indoor</option>
        <option value="TNT">Tinta</option>
        <option value="SPRT">Sparepart</option>
        <option value="ETC">Lain-Lain</option>
    </select>
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted Bahan</label>
    </span>
</div>

<div id="list_DatabaseBahan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>