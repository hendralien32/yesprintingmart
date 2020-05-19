$(document).ready(function() {

    $('#tanggal').keyup(function(e) { 
        if (e.keyCode == 13) { // Enter keycode
            $('#loader').show();
            onload();
            return false;
        }
    });

    onload();
});

$( item ).autocomplete({
    selectFirst: true
});

function onload() {
    $('#loader').show();
    var search          = $('#search').val();
    var warna_wo        = $('#warna_wo').val();
    var Dari_Tanggal    = $('#dari_tanggal').val();
    var Ke_Tanggal      = $('#ke_tanggal').val();
    var Check_box       = $("#Check_box").val();

    var fdata = new FormData()
    fdata.append("search",search);
    fdata.append("warna_wo",warna_wo);
    fdata.append("Dari_Tanggal",Dari_Tanggal);
    fdata.append("Ke_Tanggal", Ke_Tanggal);
    fdata.append("Check_box", Check_box);

    $.ajax({
        type: "POST",
        url: "Ajax/WO_List_yescom_ajax.php",
        cache: false,
		processData : false,
		contentType : false,
        data: fdata,
        success: function(data){
            $('#loader').hide();
            $("#list_WO_Yescom").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function calc_meter() {
    //perhitungan meter
    var data = $('#panjang').val()/100 + " x " + $('#lebar').val()/100 + " M ";
    $('#perhitungan_meter').html(data);
}

function satuan_val() {
    if($('#satuan').val().toLowerCase() == "kotak" && $('#kode_barng').val().split('.')[0].toLowerCase() == "digital" && $('#id_order').val() == null ) {
        $('#alat_tambahan').val("KotakNC.Kotak Kartu Nama");
        $('#Ptg_Pts').prop( "checked", true );
    } else {
        // $('#alat_tambahan').val("");
        // $('#Ptg_Pts').prop( "checked", false );
    }
    return false;
}

function satuan_val() {
    if($('#satuan').val().toLowerCase() == "kotak" && $('#kode_barng').val().split('.')[0].toLowerCase() == "digital" && $('#id_order').val() == null ) {
        $('#alat_tambahan').val("KotakNC.Kotak Kartu Nama");
        $('#Ptg_Pts').prop( "checked", true );
    } else {
        // $('#alat_tambahan').val("");
        // $('#Ptg_Pts').prop( "checked", false );
    }
    return false;
}

function ChangeKodeBrg() {
    var kode_barang = $('#kode_barng').val().split('.');

    var All_Element =  ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "laminating_floor", "KotakNC", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "CuttingSticker"];

    var digital_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi"];
    var digital_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Hekter_Tengah", "Blok", "Ring_Spiral"];

    var etc_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "Hekter_Tengah",  "Blok", "Ring_Spiral"];
    var etc_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi"];

    var LF_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker"];
    var LF_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral"];

    var indoor_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker"];
    var indoor_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral"];

    var i;
    
    if( kode_barang[0] == "digital") {
        satuan_val();

        $('#ukuran_LF').hide();
        $('#panjang').val("");
        $('#lebar').val("");

        for (i = 0; i < digital_show.length; i++) {
            $('.'+digital_show[i]+'').show();
        }

        for (i = 0; i < digital_hide.length; i++) {
            $('.'+digital_hide[i]+'').hide();
            $('#'+digital_hide[i]+'').prop("checked", false);
        }

        $('#ukuran').show();
        $('#ukuran').attr("readonly", true);
        $('#ukuran').attr("disabled", true);
        $('#ukuran').val("A3");
        $('#perhitungan_meter').hide();

    } else if( kode_barang[0] == "etc") {
        satuan_val();

        $('#ukuran_LF').hide();
        $('#panjang').val("");
        $('#lebar').val("");

        for (i = 0; i < etc_show.length; i++) {
            $('.'+etc_show[i]+'').show();
        }

        for (i = 0; i < etc_hide.length; i++) {
            $('.'+etc_hide[i]+'').hide();
            $('#'+etc_hide[i]+'').prop("checked", false);
        }

        $('#ukuran').show();
        $('#ukuran').val("");
        $('#ukuran').attr("readonly", false);
        $('#ukuran').attr("disabled", false);
        $('#perhitungan_meter').hide();


    } else if( kode_barang[0] == "indoor" || kode_barang[0] == "Xuli" ) {

        for (i = 0; i < indoor_show.length; i++) {
            $('.'+indoor_show[i]+'').show();
        }

        for (i = 0; i < indoor_hide.length; i++) {
            $('.'+indoor_hide[i]+'').hide();
            $('#'+indoor_hide[i]+'').prop("checked", false);
        }

        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#ukuran_LF').show();
        $('#perhitungan_meter').show();
        $('#perhitungan_meter').html("");

    } else if( kode_barang[0] == "large format") {

        for (i = 0; i < LF_show.length; i++) {
            $('.'+LF_show[i]+'').show();
        }

        for (i = 0; i < LF_hide.length; i++) {
            $('.'+LF_hide[i]+'').hide();
            $('#'+LF_hide[i]+'').prop("checked", false);
        }

        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#ukuran_LF').show();
        $('#perhitungan_meter').show();
        $('#perhitungan_meter').html("");

    } else {

        for (i = 0; i < All_Element.length; i++) {
            $('.'+All_Element[i]+'').hide();
            $('#'+All_Element[i]+'').prop("checked", false);
        }

        $('#ukuran_LF').hide();
        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#panjang').val("");
        $('#lebar').val("");
    }
}