$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    var type_client          = $('#type_client').val();
    var nama_client          = $('#nama_client').val();
    var show_delete;         if($('#Check_box').prop("checked") == true) { show_delete = "T"; } else { show_delete = "A"; }

    $.ajax({
        type: "POST",
        data: {type_client:type_client, data:nama_client, show_delete:show_delete},
        url: "Ajax/database_client_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#list_DatabaseClient").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function search_typedata() {
    $('#nama_client').val("");
    onload();
}

function search_data() {
    $("#type_client").val("");
    var Validasi_Search = $('#nama_client').val().length;

    if(Validasi_Search >= 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 2 huruf");
        return false;
    }
}

function Show_delete() {
    $('#nama_client').val("");
    $("#type_client").val("");
    $('#loader').show();
    onload();
}

function hapus(cid,nama_client,status_client) {
    if(status_client=="A") {
        var abc = "hapus";
    } else {
        var abc = "kembalikan";
    }

    if(confirm( abc +' client "'+ nama_client + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                Client_ID           : cid,
                jenis_submit        : "delete_client",
                status_client       : status_client
            },
            success: function(data){
                alert('Client "' + nama_client + '" sudah di' + abc);
                onload();
                return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        }); 
    }
}

function validasi(id) {
    var ID_Data = $('#'+id).val();

    $.ajax({
        type: "POST",
        data: {tipe_validasi: "Search_"+id, term:ID_Data},
        url: "progress/validasi_progress.php",
        success: function(data){
            if( data > 0 ) {
                $("#validasi_"+id).val(data);
                $("#Alert_Val"+id).html("<b style='color:red'> "+id+" Sudah terdaftar</b>");
            } else {
                $("#validasi_"+id).val("0");
                $("#Alert_Val"+id).html("<b style='color:green'> "+id+" belum terdaftar</b>");
            }
        }
    });
}

function submit(id) {
    var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var NamaClient       = $('#client').val();
    var NoTelp           = $('#form_NoTelp').val();
    var EmailClient      = $('#form_EmailClient').val();
    var DeskClient       = $('#form_DeskClient').val();
    var levelClient      = $('#form_levelClient').val();
    var Special;  if($('#form_special').prop("checked") == true) { Special = "Y"; } else { Special = "N"; }

    if(NamaClient=="") {
        alert("Nama Client tidak boleh kosong");
        return false;
    }

    if($('#validasi_client').val()==1) {
        alert("Nama Client sudah terdaftar");
        return false;
    }

    if(levelClient=="") {
        alert("Level Client tidak boleh kosong");
        return false;
    }

    if (EmailClient!="" && re.test(EmailClient)==false) { // this return result in boolean type
        alert("Email Yang dimasukan tidak valid");
        return false;
    } 

    var fdata = new FormData()
    fdata.append("NamaClient", NamaClient);
    fdata.append("NoTelp", NoTelp);
    fdata.append("EmailClient", EmailClient);
    fdata.append("DeskClient", DeskClient);
    fdata.append("levelClient", levelClient);
    fdata.append("Special", Special);
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
