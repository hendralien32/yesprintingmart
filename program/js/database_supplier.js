$(document).ready(function () {
    onload();
});

function onload() {
    $('#loader').show();
    var nama_supplier = $('#nama_supplier').val();
    var show_delete;
    if ($('#Check_box').prop("checked") == true) {
        show_delete = "Y";
    } else {
        show_delete = "N";
    }

    $.ajax({
        type: "POST",
        data: {
            data: nama_supplier,
            show_delete: show_delete
        },
        url: "Ajax/database_supplier_ajax.php",
        cache: false,
        success: function (data) {
            $('#loader').hide();
            $("#list_DatabaseSupplier").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function search_data() {
    onload();
    return false;
}

function Show_delete() {
    $('#nama_supplier').val("");
    $('#loader').show();
    onload();
}

function validasi(id) {
    var ID_Data = $('#' + id).val();

    $.ajax({
        type: "POST",
        data: {
            tipe_validasi: "Search_" + id,
            term: ID_Data
        },
        url: "progress/validasi_progress.php",
        success: function (data) {
            if (data > 0) {
                $("#validasi_" + id).val(data);
                $("#Alert_Val" + id).html("<b style='color:red'> " + id + " sudah terdaftar</b>");
            } else {
                $("#validasi_" + id).val("0");
                $("#Alert_Val" + id).html("<b style='color:green'> " + id + " belum terdaftar</b>");
            }
        }
    });
}

function submit(id) {
    var Namasupplier = $('#supplier').val();
    var IdSupplier = $('#id_supplier').val();
    var NoTelp = $('#no_telp').val();

    if (Namasupplier == "") {
        alert("Nama Supplier tidak boleh kosong");
        return false;
    }

    if ($('#validasi_supplier').val() == 1) {
        alert("Nama Supplier sudah terdaftar");
        return false;
    }

    var fdata = new FormData()
    fdata.append("IdSupplier", IdSupplier);
    fdata.append("Namasupplier", Namasupplier);
    fdata.append("NoTelp", NoTelp);
    fdata.append("jenis_submit", id);

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        beforeSend: function () {
            $('#submitBtn').attr("disabled", "disabled");
            $(".icon-close").removeAttr('onclick');
        },
        success: function (data) {
            if (id == "submit_client") {
                alert("Supplier \"" + Namasupplier + "\" berhasil di input");
            } else {
                alert("Supplier \"" + Namasupplier + "\" berhasil di Update");
            }
            // $("#bagDetail").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });
}