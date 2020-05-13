$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    
    var nama_pricelist      = $('#nama_pricelist').val();
    var kode_barang         = $('#kode_barang').val();
    var sisi_cetak          = $('#sisi_cetak').val();
    var show_delete;        if($('#Check_box').prop("checked") == true) { show_delete = "T"; } else { show_delete = "A"; }

    $.ajax({
        type: "POST",
        data: {kode_barang:kode_barang, sisi_cetak:sisi_cetak, data:nama_pricelist, show_delete:show_delete},
        url: "Ajax/database_pricelist_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#list_DatabasePricelist").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}