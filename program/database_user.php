<?php
    require_once "../function.php";
?>

<script src="js/database_user.js" async type="text/javascript" ></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('database_user')"><i class="far fa-plus-circle"></i> Add User</button>
    <input type="text" id="nama_user" class='search data' placeholder="Nama User" onchange="search_data()">
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted User</label>
    </span>
</div>

<div id="list_DatabaseUser">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>