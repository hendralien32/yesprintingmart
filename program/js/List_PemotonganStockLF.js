$(document).ready(function () {
    $("#tanggal").keyup(function (e) {
        if (e.keyCode == 13) {
            // Enter keycode
            $("#loader").show();
            onload();
            return false;
        }
    });

    onload();
});

$(item).autocomplete({
    selectFirst: true,
});

function onload() {
    $("#loader").show();
    var search = $("#search").val();
    var Dari_Tanggal = $('#dari_tanggal').val();
    var Ke_Tanggal = $('#ke_tanggal').val();

    var fdata = new FormData()
    fdata.append("search", search);
    fdata.append("Dari_Tanggal", Dari_Tanggal);
    fdata.append("Ke_Tanggal", Ke_Tanggal);

    $.ajax({
        type: "POST",
        url: "Ajax/List_PemotonganStockLF_ajax.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        success: function (data) {
            $('#loader').hide();
            $("#List_PemotonganStockLF").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function search_data() {
    var Validasi_Search = $('#search').val().length;

    if (Validasi_Search >= 4) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 3 huruf");
        return false;
    }
}

function SearchFrom() {
    $('#search').val("");

    var dari_tanggal = $('#dari_tanggal').val();
    var All_Element = ["ke_tanggal"];

    for (i = 0; i < All_Element.length; i++) {
        $('#' + All_Element[i] + '').prop("disabled", false);
        $('#' + All_Element[i] + '').prop("readonly", false);
    }

    $('#ke_tanggal').attr('min', dari_tanggal);
    $('#loader').show();
    onload();
}

function SearchTo() {
    $('#loader').show();
    onload();
}

function LaodFormLF(id, SO_Kerja) {
    var judul = "Edit Form Order Kerja Large Format";

    $.ajax({
        type: "POST",
        data: {
            data: id,
            judul_form: judul,
            SO_Kerja: SO_Kerja,
            status: "Edit_PemotonganStockLF"
        },
        url: "Form/" + id + "_f.php",

        success: function (data) {
            showBox();
            validasi();
            $("#bagDetail").html(data);
        },
    });
}

function hapus_SOLF(no_so) {
    if (confirm('Hapus SO Kerja "' + no_so + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                SO_LF: no_so,
                jenis_submit: "hapus_SO_KerjaLF"
            },
            success: function (data) {
                // $("#tesdt").html(data);
                alert('SO Kerja "' + no_so + '" Berhasil dihapus');
                onload();
                return false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            },
        });
    }
}

function validasi(id) {
    var ID_Data = $("#" + id).val();

    $.ajax({
        type: "POST",
        data: {
            tipe_validasi: "Search_" + id,
            term: ID_Data,
        },
        url: "progress/validasi_progress.php",
        success: function (data) {
            if (data > 0) {
                $("#validasi_" + id).val(data);
                $("#Alert_Val" + id).html(
                    "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
                );
            } else {
                $("#validasi_" + id).val("0");
                $("#id_" + id).val("");
                $("#Alert_Val" + id).html(
                    "<i class='fas fa-times-octagon' style='color:red; margin-left:10px; margin-right:5px;'></i>"
                );
            }
        },
    });
}

function validasi_NoBahan(id) {
    var id_NamaBahan = $("#id_NamaBahan").val();
    var ID_Data = $("#" + id).val();

    $.ajax({
        type: "POST",
        data: {
            tipe_validasi: "Search_" + id,
            term: ID_Data,
            id_NamaBahan: id_NamaBahan,
        },
        url: "progress/validasi_progress.php",
        success: function (data) {
            if (data > 0) {
                $("#id_" + id).val(ID_Data);
                $("#validasi_" + id).val(data);
                $("#Alert_Val" + id).html(
                    "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
                );
            } else {
                $("#validasi_" + id).val("0");
                $("#id_" + id).val("");
                $("#Alert_Val" + id).html(
                    "<i class='fas fa-times-octagon' style='color:red; margin-left:10px; margin-right:5px;'></i>"
                );
            }
        },
    });
}

function test(id) {
    $("#NamaBahan").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "progress/validasi_progress.php",
                type: "POST",
                data: {
                    tipe_validasi: "autocomplete_stockLF",
                    term: request.term,
                },
                dataType: "json",
                success: function (data) {
                    response(
                        $.map(data, function (item) {
                            return {
                                label: item.nama_barang + "." + item.ukuran,
                                value: item.nama_bahan,
                                id: item.ID_BarangLF,
                                ukuran: item.ukuran,
                            };
                        })
                    );
                },
            });
        },
        minLength: 2,
        autoFocus: true,
        select: function (event, ui) {
            $("#NamaBahan").val(ui.item.value);
            $("#id_NamaBahan").val(ui.item.id);
            $("#panjang_potong").val(ui.item.ukuran);
        },
        change: function (event, ui) {
            $("#NamaBahan").val(ui.item.value);
            $("#id_NamaBahan").val(ui.item.id);
            $("#panjang_potong").val(ui.item.ukuran);
        },
    });

    validasi(id);
}

function nomor_bahanSearch(id) {
    var id_NamaBahan = $("#id_NamaBahan").val();

    $("#nomor_bahan").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "progress/validasi_progress.php",
                type: "POST",
                data: {
                    tipe_validasi: "autocomplete_NoBahan",
                    id_NamaBahan: id_NamaBahan,
                    term: request.term,
                },
                dataType: "json",
                success: function (data) {
                    response(
                        $.map(data, function (item) {
                            return {
                                label: item.no_bahan,
                                value: item.no_bahan,
                                id: item.bid,
                            };
                        })
                    );
                },
            });
        },
        minLength: 1,
        autoFocus: true,
        select: function (event, ui) {
            $("#nomor_bahan").val(ui.item.value);
            $("#id_nomor_bahan").val(ui.item.id);
        },
        change: function (event, ui) {
            $("#nomor_bahan").val(ui.item.value);
            $("#id_nomor_bahan").val(ui.item.id);
        },
    });

    validasi_NoBahan(id);
}

function restan() {

    var Akses = [
        "NamaBahan",
        "nomor_bahan"
    ];

    if ($("#restan").is(":checked")) {
        for (i = 0; i < Akses.length; i++) {
            $("#" + Akses[i]).prop("disabled", true);
            $("#validasi_" + Akses[i] + "").val(1);
            $("#Alert_Val" + Akses[i] + "").html(
                "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
            );
        }
    } else {
        for (i = 0; i < Akses.length; i++) {
            $("#" + Akses[i]).prop("disabled", false);
            $("#validasi_" + Akses[i] + "").val(0);
            $("#Alert_Val" + Akses[i] + "").html("");
        }
    }
}