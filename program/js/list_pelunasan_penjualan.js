$(document).ready(function() {

    $('#tanggal').keyup(function(e) { 
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
    var Tanggal     = $('#tanggal').val();
    var type_bayar     = $('#type_bayar').val();

    $.ajax({
        type: "POST",
        data: {date:Tanggal, type_bayar:type_bayar},
        url: "Ajax/list_pelunasan_penjualan_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#List_Pelunasan").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function SearchDate() {
    onload();
}

function search_typedata() {
    onload();
}

function Copy_SisaByr(data) {
    $("#jumlah_bayar").val(data);
}

function CopySub_SisaByr(data) {
    $("#sub_jumlah_bayar").val(data);
}

function submit(id, no_invoice) {
    var jumlah_bayar = $( "#jumlah_bayar" );

    var tanggal_bayar = $('#tanggal_bayar').val();
    var jumlah_bayar = $('#jumlah_bayar').val() || 0;
    var adjust = $('#adjust').val() || 0;
    var nomor_atm = $('#nomor_atm').val();
    var bank = $('#bank').val();
    var rekening_tujuan = $('#rekening_tujuan').val();
    var sisa_bayar = $('#sisa_bayar').val() || 0;

    if( jumlah_bayar == "" || jumlah_bayar == "0" ) {
        alert("Jumlah Bayar Tidak boleh kosong");
        $(".table-pelunasan").find( jumlah_bayar ).css( "background-color", "#ffe2e3" );
        return false;
    } else if(parseInt(jumlah_bayar) > parseInt(sisa_bayar)) {
        alert(jumlah_bayar);
        alert(sisa_bayar);
        alert("Jumlah Bayar Tidak boleh lebih besar dari sisa bayar");
        $(".table-pelunasan").find( jumlah_bayar ).css( "background-color", "#ffe2e3" );
        return false;
    } else if(bank!="" && rekening_tujuan == "") {
        alert("Rekening Tujuan Wajib di isi");
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
            onload();
            $("#bagDetail").html("<center><img src='../images/0_4Gzjgh9Y7Gu8KEtZ.gif' width='150px' id='loader'></center>");
            LaodForm("pelunasan_invoice",no_invoice);
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function Update(id, no_invoice) {
    var jumlah_bayar = $( "#sub_jumlah_bayar" );

    var tanggal_bayar = $('#sub_tanggal_bayar').val();
    var jumlah_bayar = $('#sub_jumlah_bayar').val() || 0;
    var adjust = $('#sub_adjust').val() || 0;
    var nomor_atm = $('#sub_nomor_atm').val();
    var bank = $('#sub_bank').val();
    var rekening_tujuan = $('#sub_rekening_tujuan').val();
    var sisa_bayar = $('#sub_sisa_bayar').val() || 0;

    var test        = no_invoice.split('*');

    if( jumlah_bayar == "" || jumlah_bayar == "0" ) {
        alert("Jumlah Bayar Tidak boleh kosong");
        $(".table-pelunasan").find( jumlah_bayar ).css( "background-color", "#ffe2e3" );
        return false;
    } else if(parseInt(jumlah_bayar) > parseInt(sisa_bayar)) {
        alert("Jumlah Bayar Tidak boleh lebih besar dari sisa bayar");
        $(".table-pelunasan").find( jumlah_bayar ).css( "background-color", "#ffe2e3" );
        return false;
    } else if(bank!="" && rekening_tujuan == "") {
        alert("Rekening Tujuan wajib di isi");
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
            // $("#Result" ).html(data);
            onload();
            hidesubBox();
            // $("#bagDetail").html("<center><img src='../images/0_4Gzjgh9Y7Gu8KEtZ.gif' width='150px' id='loader'></center>");
            // LaodForm("pelunasan_invoice",test[1]);
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}