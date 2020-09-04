$(document).ready(function () {

    $('#dari_tanggal').keyup(function (e) {
        if (e.keyCode == 13) { // Enter keycode
            $('#loader').show();
            onload();
            return false;
        }
    });

    $('#ke_tanggal').keyup(function (e) {
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
    var dari_tanggal = $('#dari_tanggal').val();
    var ke_tanggal = $('#ke_tanggal').val();

    var fdata = new FormData()
    fdata.append("dari_tanggal", dari_tanggal);
    fdata.append("ke_tanggal", ke_tanggal);

    $.ajax({
        type: "POST",
        url: "Ajax/list_pemesanan_kertas_ajax.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        success: function (data) {
            $('#loader').hide();
            $("#list_pemesanan_kertas").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}