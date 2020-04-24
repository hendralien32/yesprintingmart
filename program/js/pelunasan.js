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

    if(Validasi_Search >= 4) {
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
    $("#jumlah_bayar").val(data);
}

function submit(id, no_invoice) {
    var spans = $( "#jumlah_bayar" );

    var tanggal_bayar = $('#tanggal_bayar').val();
    var jumlah_bayar = $('#jumlah_bayar').val() || 0;
    var adjust = $('#adjust').val() || 0;
    var nomor_atm = $('#nomor_atm').val();
    var bank = $('#bank').val();
    var rekening_tujuan = $('#rekening_tujuan').val();
    var sisa_bayar = $('#sisa_bayar').val() || 0;

    if( jumlah_bayar == "" ) {
        alert("Jumlah Bayar Tidak boleh kosong");
        $(".table-pelunasan").find( spans ).css( "background-color", "#ffe2e3" );
        return false;
    } else if(jumlah_bayar > sisa_bayar) {
        alert("Jumlah Bayar Tidak boleh lebih besar dari sisa bayar");
        $(".table-pelunasan").find( spans ).css( "background-color", "#ffe2e3" );
        return false;
    }

    var fdata = new FormData()
    fdata.append("tanggal_bayar", tanggal_bayar);
    fdata.append("jumlah_bayar", jumlah_bayar);
    fdata.append("adjust", adjust);
    fdata.append("nomor_atm", nomor_atm);
    fdata.append("bank", bank);
    fdata.append("rekening_tujuan", rekening_tujuan);
    fdata.append("sisa_bayar", sisa_bayar);
    fdata.append("no_invoice", no_invoice);
    fdata.append("jenis_submit", id);

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        cache: false,
		processData : false,
		contentType : false,
        data: fdata,
        beforeSend: function() {
            $('#submitBtn').attr("disabled","disabled");
            $(".icon-close").removeAttr('onclick');
        },
        success: function(data) {
            // $("#bagDetail").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function force_paid(ID_Order) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order,
            jenis_submit    : "force_paid"
        },
        success: function(data){
            alert("Status Pelunasan sudah LUNAS");
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}