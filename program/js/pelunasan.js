$(document).ready(function() {

    $('#dari_tanggal').keyup(function(e) { 
        if (e.keyCode == 13) { // Enter keycode
            $('#loader').show();
            onload();
            return false;
        }
    });

    $('#ke_tanggal').keyup(function(e) { 
        if (e.keyCode == 13) { // Enter keycode
            $('#loader').show();
            onload();
            return false;
        }
    });

    onload();
});

function onload() {
    $('#loader').show();
    var search          = $('#search').val();
    var client          = $('#Search_Client').val();
    var Dari_Tanggal    = $('#dari_tanggal').val();
    var Ke_Tanggal      = $('#ke_tanggal').val();
    var show_lunas;     if($('#Check_box').prop("checked") == true) { show_lunas = "Y"; } else { show_lunas = "N"; }

    $.ajax({
        type: "POST",
        data: {data:search, Dari_Tanggal:Dari_Tanggal, Ke_Tanggal:Ke_Tanggal, client:client, show_lunas:show_lunas},
        url: "Ajax/pelunasan_penjualan_Ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#pelunasan_list").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function SearchClient() {
    $('#search').val("");
    var Validasi_Search = $('#Search_Client').val().length;

    if(Validasi_Search >= 2) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 2 Character");
    }
}

function SearchData() {
    $('#Search_Client').val("");
    var Validasi_Search = $('#search').val().length;

    if(Validasi_Search > 5) {
        $('#Check_box').prop("checked", true);
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 5 Character");
        return false;
    }
}

function SearchFrom() {
    var dari_tanggal = $('#dari_tanggal').val();
    var All_Element =  ["ke_tanggal", "Check_box"];
    
    for (i = 0; i < All_Element.length; i++) {
        $('#'+All_Element[i]+'').prop("disabled", false);
        $('#'+All_Element[i]+'').prop("readonly", false);
    }

    $('#ke_tanggal').attr('min' , dari_tanggal);
    $('#Check_box').prop("checked", true);
    $('#loader').show();
    
    onload();
}

function SearchTo() {
    $('#loader').show();
    onload();
}

function show_lunas() {
    $('#loader').show();
    onload();
}

function Copy_SisaByr(data) {
    $("#jumlah_besar").val(data);
}

function submit(data) {
    var tanggal_bayar = $('#tanggal_bayar').val();
    var jumlah_bayar = $('#jumlah_bayar').val();
    var adjust = $('#adjust').val();
    var nomor_atm = $('#nomor_atm').val();
    var bank = $('#bank').val();
    var rekening_tujuan = $('#rekening_tujuan').val();
    var sisa_bayar = $('#sisa_bayar').val();

    alert(tanggal_bayar);
}