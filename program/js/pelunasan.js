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

    $.ajax({
        type: "POST",
        data: {data:search, Dari_Tanggal:Dari_Tanggal, Ke_Tanggal:Ke_Tanggal, client:client},
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

function SearchData() {
    $('#Search_Client').val("");
    var Validasi_Search = $('#search').val().length;

    if(Validasi_Search > 5) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 5 Character");
        return false;
    }
}