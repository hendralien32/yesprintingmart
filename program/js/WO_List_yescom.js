$(document).ready(function() {

    $('#dari_tanggal').keyup(function(e) { 
        if (e.keyCode == 13) { // Enter keycode
            $('#loader').show();
            onload();
            return false;
        }
    });

    $('#ke_tanggal').keyup(function(e) { 
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
    var Check_box;      if($('#Check_box').prop("checked") == true) { Check_box = "Y"; } else { Check_box = "N"; }

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

function search_data() {
    var Validasi_Search = $('#search').val().length;

    if(Validasi_Search >= 4) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 3 huruf");
        return false;
    }
}

function search_typedata() {
    $('#dari_tanggal').val("");
    $('#ke_tanggal').val("");
    $('#loader').show();
    onload();
    return false;
}

function SearchFrom() {
    $('#search').val("");

    var dari_tanggal = $('#dari_tanggal').val();
    var All_Element =  ["ke_tanggal", "Check_box"];
    
    for (i = 0; i < All_Element.length; i++) {
        $('#'+All_Element[i]+'').prop("disabled", false);
        $('#'+All_Element[i]+'').prop("readonly", false);
    }

    $('#ke_tanggal').attr('min' , dari_tanggal);
    $('#loader').show();
    onload();
}

function SearchTo() {
    $('#loader').show();
    onload();
}

function Show_delete() {
    $('#loader').show();
    onload();
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

function validasi(id) {
    var ID_Data = $('#'+id).val();

    $.ajax({
        type: "POST",
        data: {tipe_validasi: "Search_"+id, term:ID_Data},
        url: "progress/validasi_progress.php",
        success: function(data){
            if( data > 0 ) {
                $("#validasi_"+id).val(data);
                $("#Alert_Val"+id).html("");
            } else {
                $("#validasi_"+id).val("0");
                $("#id_"+id).val("");
                $("#Alert_Val"+id).html("<b style='color:red'>"+id+" Belum terdaftar</b>");
            }
        }
    });
}

function test(id) {
    var kode_barang = $('#kode_barng').val().split('.');

    $( "#client" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "progress/validasi_progress.php",
                type: "POST",
                data: {
                    tipe_validasi: "autocomplete_client",
                    term: request.term
                },
                dataType: 'json',
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.nama_client + " - " + item.no_telp,
                            value: item.nama_client,
                            id: item.cid
                        };
                    }));
                }
            });
        },
        minLength:2, 
        autoFocus: true,
        select: function(event, ui) {
            $("#client").val(ui.item.value);
            $("#id_client").val(ui.item.id);
        },
        change: function(event, ui) {
            $("#client").val(ui.item.value);
            $("#id_client").val(ui.item.id);
        }
    });
    
    if( kode_barang[0] == "large format" || kode_barang[0] == "indoor" || kode_barang[0] == "Xuli" ) {
        var data_barang = "LF"
    } else {
        var data_barang = "Digital"
    }

    $( "#bahan" ).autocomplete({
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
            $("#bahan").val(ui.item.value);
            $("#id_bahan").val(ui.item.id);
        },
        change: function(event, ui) {
            $("#bahan").val(ui.item.value);
            $("#id_bahan").val(ui.item.id);
        }
    });
    
    validasi(id);
}

function SearchID_YES() {
    if($('#id_yescom').val().length >= 4) {
        var ID_YES = $('#id_yescom').val();

        var fdata = new FormData()
        fdata.append("ID_YES", ID_YES);
        fdata.append("tipe_validasi", 'Auto_YesOrder_Data');

        $.ajax({
            type: "POST",
            url: "progress/validasi_progress.php",
            cache: false,
        	processData : false,
        	contentType : false,
            data: fdata,
            success: function(data){
                // $('#notes').val(data);
                $('#bahan').val("");
                $('#id_bahan').val("");
                var obj=$.parseJSON(data);
                $('#so_yescom').val(obj.so);
                $('#client').val(obj.client); 
                $('#marketing_yescom').val(obj.ae);
                $('#ukuran_yescom').val(obj.ukuran);
                $('#qty_yescom').val(obj.qty);
                $('#deskripsi').val(obj.project);
                $('#notes').val(obj.finishing);
                $('#YES_bahan').html(obj.bahan);
                if(obj.sisi == "2") {
                    $('#dua_sisi').prop("checked", true);
                } else {
                    $('#satu_sisi').prop("checked", true);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        }); 
    } 
}

function Check_KertasSendiri() {
    if($('#bahan').val() == "Kertas Sendiri") {
        $('#bahan_sendiri').show();
    } else {
        $('#bahan_sendiri').hide();
        $('#bahan_sendiri').val("");
    }
}

function submit(id) {

    if($('#kode_barng').val()=="") {
        alert("Kode Barang harus di pilih")
        return false;
    } else if($('#client').val()=="") {
        alert("Nama Client tidak Boleh kosong")
        return false;
    } else if($('#validasi_bahan').val()<1) {
        alert("Nama Bahan tidak ada dalam Daftar Stock Barang")
        return false;
    } else if($('#qty').val()=="") {
        alert("Qty tidak Boleh Kosong")
        return false;
    }
    
    var id_Order            = $('#id_Order').val();
    var id_yescom           = $('#id_yescom').val();
    var wo_yescom           = $('#wo_yescom').val();
    var so_yescom           = $('#so_yescom').val();
    var marketing_yescom    = $('#marketing_yescom').val();
    var ukuran_yescom       = $('#ukuran_yescom').val();
    var qty_yescom          = $('#qty_yescom').val();
    var warna_cetakan       = $('#warna_cetakan').val();
    var urgent;             if($('#urgent').prop("checked") == true) { urgent = "Y"; } else { urgent = "N"; }
    var Kode_Brg            = $('#kode_barng').val().split('.');
    var Nama_Client         = $('#client').val();
    var Deskripsi           = $('#deskripsi').val();
    var Ukuran              = $('#ukuran').val();
    var Panjang             = $('#panjang').val();
    var Lebar               = $('#lebar').val();
    var Sisi;               if($('#satu_sisi').prop("checked") == true) { Sisi = "1"; } else { Sisi = "2"; }
    var ID_Bahan            = $('#id_bahan').val();
    var Nama_Bahan          = $('#bahan').val();
    var Notes               = $('#notes').val();
    var Laminating          = $('#laminating').val().split('.');
    var alat_tambahan       = $('#alat_tambahan').val().split('.');
    var Ptg_Pts;            if($('#Ptg_Pts').prop("checked") == true) { Ptg_Pts = "Y"; } else { Ptg_Pts = "N"; }
    var Ptg_Gantung;        if($('#Ptg_Gantung').prop("checked") == true) { Ptg_Gantung = "Y"; } else { Ptg_Gantung = "N"; }
    var Pon_Garis;          if($('#Pon_Garis').prop("checked") == true) { Pon_Garis = "Y"; } else { Pon_Garis = "N"; }
    var Perporasi;          if($('#Perporasi').prop("checked") == true) { Perporasi = "Y"; } else { Perporasi = "N"; }
    var CuttingSticker;     if($('#CuttingSticker').prop("checked") == true) { CuttingSticker = "Y"; } else { CuttingSticker = "N"; }
    var Hekter_Tengah;      if($('#Hekter_Tengah').prop("checked") == true) { Hekter_Tengah = "Y"; } else { Hekter_Tengah = "N"; }
    var Blok;               if($('#Blok').prop("checked") == true) { Blok = "Y"; } else { Blok = "N"; }
    var Spiral;             if($('#Spiral').prop("checked") == true) { Spiral = "Y"; } else { Spiral = "N"; }
    var Proffing;           if($('#proffing').prop("checked") == true) { Proffing = "Y"; } else { Proffing = "N"; }
    var Ditunggu;           if($('#ditunggu').prop("checked") == true) { Ditunggu = "Y"; } else { Ditunggu = "N"; }
    var Qty                 = $('#qty').val();
    var Satuan              = $('#satuan').val();
    var bahan_sendiri       = $('#bahan_sendiri').val();

    if(Laminating[1]== null) { deskripsi_Laminating = ""; } else { deskripsi_Laminating = Laminating[1]; }
    if(alat_tambahan[1]== null) { deskripsi_alat_tambahan = ""; } else { deskripsi_alat_tambahan = alat_tambahan[1]; }

    var fdata = new FormData()
    fdata.append("id_Order",id_Order);
    fdata.append("id_yescom",id_yescom);
    fdata.append("Kode_Brg", Kode_Brg[0]); 
    fdata.append("Desc_Kode_Brg", Kode_Brg[1]);
    fdata.append("Nama_Client", Nama_Client);
    fdata.append("Deskripsi", Deskripsi);
    fdata.append("Ukuran", Ukuran);
    fdata.append("Panjang", Panjang);
    fdata.append("Lebar", Lebar);
    fdata.append("Sisi", Sisi);
    fdata.append("ID_Bahan", ID_Bahan);
    fdata.append("Nama_Bahan", Nama_Bahan);
    fdata.append("bahan_sendiri", bahan_sendiri);
    fdata.append("Notes", Notes);
    fdata.append("Laminating", Laminating[0]);
    fdata.append("Desc_Laminating", deskripsi_Laminating);
    fdata.append("alat_tambahan", alat_tambahan[0]);
    fdata.append("Desc_alat_tambahan", deskripsi_alat_tambahan);
    fdata.append("Ptg_Pts", Ptg_Pts);
    fdata.append("Ptg_Gantung", Ptg_Gantung);
    fdata.append("Pon_Garis", Pon_Garis);
    fdata.append("Perporasi", Perporasi);
    fdata.append("CuttingSticker", CuttingSticker);
    fdata.append("Hekter_Tengah", Hekter_Tengah);
    fdata.append("Blok", Blok);
    fdata.append("Spiral", Spiral);
    fdata.append("Qty", Qty);
    fdata.append("Satuan", Satuan);
    fdata.append("wo_yescom", wo_yescom);
    fdata.append("so_yescom", so_yescom);
    fdata.append("marketing_yescom", marketing_yescom);
    fdata.append("ukuran_yescom", ukuran_yescom);
    fdata.append("qty_yescom", qty_yescom);
    fdata.append("warna_cetakan", warna_cetakan);
    fdata.append("urgent", urgent);
    fdata.append("Proffing", Proffing);
    fdata.append("Ditunggu", Ditunggu);
    fdata.append("jenis_submit", id);

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        cache: false,
		processData : false,
		contentType : false,
        data: fdata,
        beforeSend: function(){
            $('#submitBtn').attr("disabled","disabled");
            $(".icon-close").removeAttr('onclick');
        },
        success: function(data){
            if(id=="Insert_WO_List") { alert("Data Berhasil Di input ke database !") } 
            else {  alert("Data Berhasil Di Update ke database !") }
            // $("#Result").html(data);
            hideBox();
            onload();
            return false;
        }, 
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function hapus(cid,nama_WO_LIST,status_WO_LIST) {
    if(status_WO_LIST=="deleted") {
        var abc = "kembalikan";
    } else {
        var abc = "hapus";
    }

    if(confirm( abc +' WO List "'+ nama_WO_LIST + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                WO_LIST_ID           : cid,
                jenis_submit         : "delete_WOLIST",
                status_WO_LIST       : status_WO_LIST
            },
            success: function(data){
                alert('WO List "' + nama_WO_LIST + '" sudah di' + abc);
                onload();
                return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        }); 
    }
}

function Copy_text() {
    $("#generator_select").select();
    document.execCommand('copy');
    $("#button_copy").html("<i class='fas fa-copy'></i> Copied Success !");
    $("#button_copy").animate({width: "30%"});
}

function akses(a,ID_Order) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            WO_LIST_ID      : ID_Order,
            jenis_akses     : a,
            jenis_submit    : "WOList_Akses_Edit"
        },
        success: function(data){
            // $('#result').html(data);
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function print_report() {
    var dari_tanggal          = $('#dari_tanggal').val();
    var ke_tanggal          = $('#ke_tanggal').val();

    window.open('print_WOList_report.php?Status_Print=print Wo list daily report&dari_tgl='+ dari_tanggal + '&ke_tgl=' + ke_tanggal, '_blank');
}