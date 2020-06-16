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

function toggle(pilih) {
    checkboxes = $('[name="option"]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = pilih.checked;
    }
}

function onload() {
    $("#loader").show();
    var search = $("#search").val();
    var Tanggal = $("#tanggal").val();
    var session_bahan = $("#session_bahan").val();

    $.ajax({
        type: "POST",
        data: {
            data: search,
            date: Tanggal,
            session_bahan: session_bahan,
        },
        url: "Ajax/LargeFormat_List_ajax.php",
        cache: false,
        success: function (data) {
            $("#loader").hide();
            $("#setter_penjualan").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
    });
}