$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    
    var nama_pricelist      = $('#nama_pricelist').val();
    var kode_barang         = $('#Form_KodeBrg').val();
    var sisi_cetak          = $('#sisi_cetak').val();
    var warna               = $('#warna').val();
    var show_delete;        if($('#Check_box').prop("checked") == true) { show_delete = "n"; } else { show_delete = "a"; }

    $.ajax({
        type: "POST",
        data: {Form_KodeBrg:kode_barang, sisi_cetak:sisi_cetak, warna:warna, data:nama_pricelist, show_delete:show_delete},
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

function search_typedata() {
    onload();
    return false;
}

function search_data() {
    onload();
    return false;
}

function Show_delete() {
    $('#nama_pricelist').val("");
    $('#loader').show();
    onload();
}

function ChangeKodeBrg() {
    var kode_barang = $('#kode_barng').val().split('.');

    var All_Element =  ["digital", "large_format", "indoor"];
    var All_Element_Input = ["1_lembar", "2_lembar", "3sd5_lembar", "6sd9_lembar", "10_lembar", "20_lembar", "50_lembar", "100_lembar", "250_lembar", "500_lembar", "1_kotak", "2sd19_kotak", "20_kotak", "1sd2m", "3sd9m", "10m", "50m", "harga_indoor", "6sd8pass_indoor", "12pass_indoor", "20pass_indoor"]
    
    var digital_show = ["digital"];
    var digital_hide = ["large_format", "indoor"];
    var digital_input = ["1_lembar", "2_lembar", "3sd5_lembar", "6sd9_lembar", "10_lembar", "20_lembar", "50_lembar", "100_lembar", "250_lembar", "500_lembar", "1_kotak", "2sd19_kotak", "20_kotak"]

    var LF_show = ["large_format"];
    var LF_hide = ["digital", "indoor"];
    var LF_input = ["1sd2m", "3sd9m", "10m", "50m"]

    var indoor_show = ["indoor"];
    var indoor_hide = ["digital", "large_format"];
    var indoor_input = ["harga_indoor", "6sd8pass_indoor", "12pass_indoor", "20pass_indoor"];

    var i;
    
    if( kode_barang[0] == "digital") {

        for (i = 0; i < digital_show.length; i++) {
            $('*#'+digital_show[i]+'').show();
        }

        for (i = 0; i < digital_hide.length; i++) {
            $('*#'+digital_hide[i]+'').hide();
        }

        for (i = 0; i < LF_input.length; i++) {
            $('*#'+LF_input[i]+'').val("");
        }

        for (i = 0; i < indoor_input.length; i++) {
            $('*#'+indoor_input[i]+'').val("");
        }

    } else if( kode_barang[0] == "indoor" ) {

        for (i = 0; i < indoor_show.length; i++) {
            $('*#'+indoor_show[i]+'').show();
        }

        for (i = 0; i < indoor_hide.length; i++) {
            $('*#'+indoor_hide[i]+'').hide();
        }

        for (i = 0; i < digital_input.length; i++) {
            $('*#'+digital_input[i]+'').val("");
        }

        for (i = 0; i < LF_input.length; i++) {
            $('*#'+LF_input[i]+'').val("");
        }

    } else if( kode_barang[0] == "large format") {

        for (i = 0; i < LF_show.length; i++) {
            $('*#'+LF_show[i]+'').show();
        }

        for (i = 0; i < LF_hide.length; i++) {
            $('*#'+LF_hide[i]+'').hide();
        }

        for (i = 0; i < digital_input.length; i++) {
            $('*#'+digital_input[i]+'').val("");
        }

        for (i = 0; i < indoor_input.length; i++) {
            $('*#'+indoor_input[i]+'').val("");
        }

    } else {
        for (i = 0; i < All_Element.length; i++) {
            $('*#'+All_Element[i]+'').hide();
        }

        for (i = 0; i < All_Element_Input.length; i++) {
            $('*#'+All_Element_Input[i]+'').val("");
        }
    }
}

function test_valid(id) {
    var ID_Data = $('#'+id+'FC').val();

    $.ajax({
        type: "POST",
        data: {tipe_validasi: "Search_"+id, term:ID_Data},
        url: "progress/validasi_progress.php",
        success: function(data){
            if( data > 0 ) {
                $("#validasi_"+id).val(data);
            } else {
                $("#validasi_"+id).val("0");
            }
        }
    });
}

function validasi(id) {
    var ID_Data       = $('#'+id).val();
    var Sisi;         if($('#satu_sisi').prop("checked") == true) { Sisi = "1"; } else { Sisi = "2"; }
    var kode_barng    = $('#kode_barng').val().split('.');
    var Warna         = $('#from_Warna').val();

    $.ajax({
        type: "POST",
        data: {tipe_validasi: "Search_"+id, term:ID_Data, kode_barng:kode_barng[0], Warna:Warna, Sisi:Sisi },
        url: "progress/validasi_progress.php",
        success: function(data){
            if( data > 0 ) {
                $("#validasi_"+id).val(data);
                $("#Alert_Val"+id).html("<b style='color:red'> Pricelist bahan sudah terdaftar</b>");
            } else {
                $("#validasi_"+id).val(data);
                $("#Alert_Val"+id).html("<b style='color:green'> Pricelist bahan belum terdaftar</b>");
            }
        }
    });
}

function test(id) {
    var kode_barang = $('#kode_barng').val().split('.');

    if( kode_barang[0] == "large format" || kode_barang[0] == "indoor" || kode_barang[0] == "Xuli" ) {
        var data_barang = "LF"
    } else {
        var data_barang = "Digital"
    }

    $( "#bahanFC" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "progress/validasi_progress.php",
                type: "POST",
                data: {
                    tipe_validasi: "autocomplete_Bahan"+data_barang,
                    term: request.term
                },
                dataType: 'json',
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.nama_barang,
                            value: item.nama_barang,
                            id: item.id_barang
                        };
                    }));
                }
            });
        },
        minLength:2, 
        autoFocus: true,
        select: function(event, ui) {
            $("#bahanFC").val(ui.item.value);
            $("#id_bahanFC").val(ui.item.id);
        },
        change: function(event, ui) {
            $("#bahanFC").val(ui.item.value);
            $("#id_bahanFC").val(ui.item.id);
            validasi(id);
        }
    });
}

function submit(id) {
    var kode_barng          = $('#kode_barng').val().split('.');
    var bahanFC             = $('#id_bahanFC').val();
    var id_pricelist        = $('#id_pricelist').val();
    var form_Warna          = $('#from_Warna').val();
    var Sisi;               if($('#satu_sisi').prop("checked") == true) { Sisi = "1"; } else { Sisi = "2"; }
    var f_1_lembar          = $('#1_lembar').val();
    var f_2_lembar          = $('#2_lembar').val();
    var f_3sd5_lembar       = $('#3sd5_lembar').val();
    var f_6sd9_lembar       = $('#6sd9_lembar').val();
    var f_10_lembar         = $('#10_lembar').val();
    var f_20_lembar         = $('#20_lembar').val();
    var f_50_lembar         = $('#50_lembar').val();
    var f_100_lembar        = $('#100_lembar').val();
    var f_250_lembar        = $('#250_lembar').val();
    var f_500_lembar        = $('#500_lembar').val();
    var f_1_kotak           = $('#1_kotak').val();
    var f_2sd19_kotak       = $('#2sd19_kotak').val();
    var f_20_kotak          = $('#20_kotak').val();
    var f_1sd2m             = $('#1sd2m').val();
    var f_3sd9m             = $('#3sd9m').val();
    var f_10m               = $('#10m').val();
    var f_50m               = $('#50m').val();
    var f_harga_indoor      = $('#harga_indoor').val();
    var f_6sd8pass_indoor   = $('#6sd8pass_indoor').val();
    var f_12pass_indoor     = $('#12pass_indoor').val();
    var f_20pass_indoor     = $('#20pass_indoor').val();
    var SpecialPrice        = $('#SpecialPrice').val();
    var Validasi_Pricelist  = $('#validasi_bahanFC').val();
    var Validasi_Bahan      = $('#validasi_bahan').val();

    if(Validasi_Pricelist == 1) {
        alert("Nama Bahan Sudah terdaftar");
        return false;
    }
    if(Validasi_Bahan == 0) {
        alert("Nama Bahan tidak terdaftar");
        return false;
    }
    
    var fdata = new FormData()
    fdata.append("kode_barng", kode_barng[0]);
    fdata.append("bahanFC", bahanFC);
    fdata.append("id_pricelist", id_pricelist);
    fdata.append("form_Warna", form_Warna);
    fdata.append("Sisi", Sisi);
    fdata.append("f_1_lembar", f_1_lembar);
    fdata.append("f_2_lembar", f_2_lembar);
    fdata.append("f_3sd5_lembar", f_3sd5_lembar);
    fdata.append("f_6sd9_lembar", f_6sd9_lembar);
    fdata.append("f_10_lembar", f_10_lembar);
    fdata.append("f_20_lembar", f_20_lembar);
    fdata.append("f_50_lembar", f_50_lembar);
    fdata.append("f_100_lembar", f_100_lembar);
    fdata.append("f_250_lembar", f_250_lembar);
    fdata.append("f_500_lembar", f_500_lembar);
    fdata.append("f_1_kotak", f_1_kotak);
    fdata.append("f_2sd19_kotak", f_2sd19_kotak);
    fdata.append("f_20_kotak", f_20_kotak);
    fdata.append("f_1sd2m", f_1sd2m);
    fdata.append("f_3sd9m", f_3sd9m);
    fdata.append("f_10m", f_10m);
    fdata.append("f_50m", f_50m);
    fdata.append("f_harga_indoor", f_harga_indoor);
    fdata.append("f_6sd8pass_indoor", f_6sd8pass_indoor);
    fdata.append("f_12pass_indoor", f_12pass_indoor);
    fdata.append("f_20pass_indoor", f_20pass_indoor);
    fdata.append("SpecialPrice", SpecialPrice);
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

function hapus(cid,nama_pricelist,status_pricelist) {
    if(status_pricelist=="a") {
        var abc = "hapus";
    } else {
        var abc = "kembalikan";
    }

    if(confirm( abc +' Pricelist "'+ nama_pricelist + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                pricelist_ID            : cid,
                jenis_submit            : "delete_pricelist",
                status_pricelist        : status_pricelist
            },
            success: function(data){
                alert('Pricelist "' + nama_pricelist + '" sudah di' + abc);
                onload();
                return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        }); 
    }
}