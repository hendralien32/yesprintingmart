<?php
    require_once "../function.php";
?>

<script src="js/database_client.js" async type="text/javascript" ></script>

<div class="left_content">
    <?php if($_SESSION['level']=="admin" or $_SESSION['level']=="CS" or $_SESSION['level']=="accounting") : ?>
        <button type="button" onclick="LaodForm('database_client')"><i class="far fa-plus-circle"></i> Add Client</button>
    <?php endif; ?>
    <input type="text" id="nama_client" class='search data' placeholder="Nama Client, Nomor Hp & Email" onchange="search_data()">
    <select id="type_client" onchange="search_typedata()">
        <option value="">Pilih Level Client</option>
        <option value="D1">D1 - Good Client</option>
        <option value="D2">D2 - Average Client</option>
        <option value="D3">D3 - Bad Client</option>
        <option value="D4">D4 - Blacklisted Client</option>
    </select>
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted Client</label>
    </span>
</div>

<div id="list_DatabaseClient">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>