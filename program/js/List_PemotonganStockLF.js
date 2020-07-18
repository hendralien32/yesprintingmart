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
            $("#bagDetail").html(data);
        },
    });
}