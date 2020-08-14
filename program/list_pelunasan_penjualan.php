<script src="js/list_pelunasan_penjualan.js" async type="text/javascript" ></script>

<div class="left_content">
    <input type="date" value="<?= $date; ?>" data-placeholder="Tanggal" id="tanggal" onblur="SearchDate()" max="<?= $date; ?>" style="width:200px">
    <select id="type_bayar" onchange="search_typedata()">
        <option value="">Pilih Type Bayar</option>
        <option value="A">Cash / DP</option>
        <option value="B">Kartu kredit / DP Kartu Kredit</option>
    </select>
</div>

<div id="List_Pelunasan">
    <center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px"></center>
</div>