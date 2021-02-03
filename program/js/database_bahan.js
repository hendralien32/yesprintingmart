$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    var nama_bahan          = $('#nama_bahan').val();
    var type_bahan          = $('#type_bahan').val();
    var show_delete;       if($('#Check_box').prop("checked") == true) { show_delete = "n"; } else { show_delete = "a"; }

    $.ajax({
        type: "POST",
        data: {data:nama_bahan, type_bahan:type_bahan, show_delete:show_delete},
        url: "Ajax/database_bahan_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#list_DatabaseBahan").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function search_typedata() {
    $('#nama_bahan').val("");
    onload();
}


function Show_delete() {
    $('#nama_bahan').val("");
    onload();
}

function search_data() {
    $("#type_bahan").val("");
    var Validasi_Search = $('#nama_bahan').val().length;

    if(Validasi_Search >= 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 2 huruf");
        return false;
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

function hapus(cid,nama_bahan,status_bahan) {
    if(status_bahan=="a") {
        var abc = "hapus";
    } else {
        var abc = "kembalikan";
    }

    if(confirm( abc +' Bahan "'+ nama_bahan + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                bahan_ID           : cid,
                jenis_submit       : "delete_bahan",
                status_bahan       : status_bahan
            },
            success: function(data){
                alert('Bahan "' + nama_bahan + '" sudah di' + abc);
                onload();
                return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        }); 
    }
}

function submit(id) {
    const Bahan         = $('#bahan').val();
    const IdBahan       = $('#id_bahan').val();
    const JenisBahan    = $('#form_JenisBahan').val();
    const MinStock      = $('#form_MinStock').val();
    const Satuan        = $('#form_Satuan').val();
    const KodeBrng      = $('#kode_barang').val();
    const panjang       = $('#form_panjang').val();
    const lebar         = $('#form_lebar').val();
    

    if(Bahan == "") {
        alert("Nama Bahan tidak boleh kosong");
        return false;
    } else if($('#validasi_bahan').val()==1) {
        alert("Nama Bahan sudah terdaftar");
        return false;
    } else if( JenisBahan == "" ) {
        alert("Jenis Bahan tidak boleh Kosong");
        return false;
    } else if( JenisBahan == "KRTS" && ( panjang == 0 || lebar == 0 || panjang == "" || lebar == ""  ) ) {
        alert("Ukuran Kertas Error");
        return false;
    } else if ( MinStock == "" ) {
        alert("Minimal Stock tidak boleh Kosong");
        return false;
    } else if ( Satuan == "" ) {
        alert("Satuan tidak boleh Kosong");
        return false;
    }

    var fdata = new FormData()
    fdata.append("IdBahan", IdBahan);
    fdata.append("Bahan", Bahan);
    fdata.append("JenisBahan", JenisBahan);
    fdata.append("MinStock", MinStock);
    fdata.append("Satuan", Satuan);
    fdata.append("KodeBrng", KodeBrng);
    fdata.append("panjang", panjang);
    fdata.append("lebar", lebar);
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
            // $("#Result").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#Result").html(XMLHttpRequest);
        }
    });
}

function ChangeKodeBrg() {
    var kode_barang = $("#form_JenisBahan").val();
  
    if (kode_barang == "KRTS") {
      $("#ukuran").show();
      
    } else {
        $("#ukuran").hide();
        $("#form_panjang").val(0);
        $("#form_lebar").val(0);
    }
}
  