<?php
require_once "../function.php";
?>

<script src="js/database_supplier.js" async type="text/javascript"></script>

<div class="left_content">
    <?php if ($_SESSION['level'] == "admin") : ?>
        <button type="button" onclick="LaodForm('database_supplier')"><i class="far fa-plus-circle"></i> Add Supplier</button>
    <?php endif; ?>
    <input type="text" id="nama_supplier" class='search data' placeholder="Nama supplier" onchange="search_data()">
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted Supplier</label>
    </span>
</div>

<div id="list_DatabaseSupplier">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>