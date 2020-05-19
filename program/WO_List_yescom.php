<?php
    require_once "../function.php";
?>

<script src="js/WO_List_yescom.js" async type="text/javascript" ></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('WO_List_yescom')"><i class="far fa-plus-circle"></i> Add Order Yescom</button>
    <input type="text" id="search" class='search data' placeholder="Client, ID, SO, dan Deskription" onchange="search_data()">
    <select id="warna_wo" onchange="search_typedata()" style='margin-right:10px'>
        <option value="">Pilih Work Order</option>
        <option value="Kuning">Kuning</option>
        <option value="Hijau">Hijau</option>
    </select>
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='Show_delete()'>
        <label class="label-checkbox100" for="Check_box">Show Deleted Order</label>
    </span>
</div>

<div id="list_WO_Yescom">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>