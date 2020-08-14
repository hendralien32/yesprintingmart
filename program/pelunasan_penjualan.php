<script src="js/pelunasan.js" async type="text/javascript" ></script>

<div class="left_content">
    <button type="button" onclick="LaodForm('pelunasan_Multi_invoice')"><i class="far fa-plus-circle"></i> Multi Payment</button>
    <input type='text' value="" placeholder="Search Client" class='search client' id='Search_Client' onchange="SearchClient()" autocomplete="off">
    <input type='text' value="" placeholder="Search No. Invoice" class='search data' id='search' onchange="SearchData()" autocomplete="off">
    <input type="date" data-placeholder="Dr Tanggal" id="dari_tanggal" onblur="SearchFrom()" max="<?= $date ?>">
    <input type="date" data-placeholder="Ke Tanggal" id="ke_tanggal" onblur="SearchTo()" max="<?= $date ?>" disabled="disabled" readonly>
    <span style='margin-left:10px'>
        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='show_lunas()' disabled readonly>
        <label class="label-checkbox100" for="Check_box">Show Lunas</label>
    </span>
</div>

<div id="pelunasan_list">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>