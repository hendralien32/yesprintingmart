$(document).ready(function () {
    $("#dari_bulan").keyup(function (e) {
        if (e.keyCode == 13) {
            // Enter keycode
            $("#loader").show();
            onload();
            return false;
        }
    });

    $("#ke_bulan").keyup(function (e) {
        if (e.keyCode == 13) {
            // Enter keycode
            $("#loader").show();
            onload();
            return false;
        }
    });

    onload();
});

function onload() {
    $("#loader").show();
    var dari_bulan = $("#dari_bulan").val();
    var ke_bulan = $("#ke_bulan").val();
    var jenis_laporan = $("#jenis_laporan").val();

    var fdata = new FormData();
    fdata.append("dari_bulan", dari_bulan);
    fdata.append("ke_bulan", ke_bulan);
    fdata.append("jenis_laporan", jenis_laporan);

    $.ajax({
        type: "POST",
        url: "Ajax/laporan_SetoranBank_ajax.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        success: function (data) {
            $("#loader").hide();
            $("#list_LapSetoranBank").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
    });
}

function SearchFrom() {
    let dari_tanggal = $("#dari_bulan").val();
    let All_Element = ["ke_bulan"];

    for (i = 0; i < All_Element.length; i++) {
        $("#" + All_Element[i] + "").prop("disabled", false);
        $("#" + All_Element[i] + "").prop("readonly", false);
    }

    $("#ke_bulan").attr("min", dari_tanggal);
    $("#loader").show();
    onload();
}

function SearchTo() {
    $("#loader").show();
    onload();
}

function jenis_laporan() {
    $("#loader").show();
    onload();
}

function export_xls() {
    var dari_bulan = $("#dari_bulan").val();
    var ke_bulan = $("#ke_bulan").val();
    var jenis_laporan = $("#jenis_laporan").val();

    window.open(
        "export_xls.php?jenis_laporan=" +
        jenis_laporan +
        "&dari_bulan=" +
        dari_bulan +
        "&ke_bulan=" +
        ke_bulan +
        "&type_export=laporan_Setoran_Bank"
    );
}