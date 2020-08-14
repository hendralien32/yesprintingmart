$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    var nama_user          = $('#nama_user').val();
    var show_delete;       if($('#Check_box').prop("checked") == true) { show_delete = "n"; } else { show_delete = "a"; }

    $.ajax({
        type: "POST",
        data: {data:nama_user, show_delete:show_delete},
        url: "Ajax/database_user_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#list_DatabaseUser").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function search_data() {
    var Validasi_Search = $('#nama_user').val().length;

    if(Validasi_Search >= 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 2 huruf");
        return false;
    }
}

function Show_delete() {
    $('#nama_user').val("");
    $('#loader').show();
    onload();
}

function hapus(cid,nama_user,status_user) {
    if(status_user=="a") {
        var abc = "hapus";
    } else {
        var abc = "kembalikan";
    }

    if(confirm( abc +' User "'+ nama_user + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                user_ID             : cid,
                jenis_submit        : "delete_user",
                status_user         : status_user
            },
            success: function(data){
                alert('User "' + nama_user + '" sudah di' + abc);
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
                $("#Alert_Val"+id).html("<b style='color:red'> "+id+" sudah terdaftar</b>");
            } else {
                $("#validasi_"+id).val("0");
                $("#Alert_Val"+id).html("<b style='color:green'> "+id+" belum terdaftar</b>");
            }
        }
    });
}

function submit(id) {
    const Username      = $('#username').val();
    const IdUser        = $('#id_user').val();
    const Nama          = $('#form_Nama').val();
    const Password      = encodeURIComponent($('#form_Password').val());
    const RetypePswd    = encodeURIComponent($('#retype_password').val());
    const NoTelp        = $('#form_NoTelp').val();
    const TglMasuk      = $('#tanggal_masuk').val();
    const Tgl_Keluar    = $('#tanggal_keluar').val();
    const LevelUser     = $('#form_levelUser').val();

    if(Username=="") {
        alert("Username tidak boleh kosong");
        return false;
    } else if($('#validasi_client').val()==1) {
        alert("Nama Client sudah terdaftar");
        return false;
    } else if(Nama=="") {
        alert("Nama User tidak boleh kosong");
        return false;
    } else if(LevelUser=="") {
        alert("Level User tidak boleh kosong");
        return false;
    }

    if(IdUser=="") {
        if(Password=="") {
            alert("Password User tidak boleh kosong");
            return false;
        } else if(Password!=RetypePswd) {
            alert("Password dan Retype Password tidak sama");
            return false;
        }
    }

    var fdata = new FormData()
    fdata.append("Username", Username);
    fdata.append("IdUser", IdUser);
    fdata.append("Nama", Nama);
    fdata.append("Password", Password);
    fdata.append("NoTelp", NoTelp);
    fdata.append("TglMasuk", TglMasuk);
    fdata.append("Tgl_Keluar", Tgl_Keluar);
    fdata.append("LevelUser", LevelUser);
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