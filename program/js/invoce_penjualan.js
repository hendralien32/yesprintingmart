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

function toggle(pilih) {
    checkboxes = $('[name="option"]'); 
    for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = pilih.checked;
    }
}

function akses(a,ID_Order) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order,
            jenis_akses     : a,
            jenis_submit    : "Akses_Edit"
        },
        success: function(data){
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function force_paid(ID_Order) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order,
            jenis_submit    : "force_paid"
        },
        success: function(data){
            alert("Status Pelunasan sudah LUNAS");
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function check_invoice(ID_Order, Sales) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order,
            jenis_submit    : "check_invoice"
        },
        success: function(data){
            alert("Sales Invoice No # " + ID_Order + " Sudah di check oleh " + Sales);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function AksesEdit() {
    var kode_barang = $('#kode_barng').val().split('.');
    var AksesEdit;        if($('#akses_edit').prop("checked") == true) { AksesEdit = "Y"; } else { AksesEdit = "N"; }
    var AutoCalc;        if($('#Auto_Calc').prop("checked") == true) { AutoCalc = "Y"; } else { AutoCalc = "N"; }
    var Akses = ["b_digital", "b_kotak", "b_finishing", "b_large", "b_indoor", "b_xbanner", "b_laminate"];

    if(AksesEdit === "Y") {
        for (i = 0; i < Akses.length; i++) {
            $('#'+Akses[i]+'').prop("disabled", false);
            $('#'+Akses[i]+'').prop("readonly", false);
        }
        if( kode_barang[0] == "offset" || kode_barang[0] == "etc") {
            $('#Auto_Calc').prop("checked", false);
            $('#Auto_Calc').prop("disabled", true);
        } else {
            $('#Auto_Calc').prop("checked", false);
            $('#Auto_Calc').prop("disabled", true);
        }
    } else {
        for (i = 0; i < Akses.length; i++) {
            $('#'+Akses[i]+'').prop("disabled", true);
            $('#'+Akses[i]+'').prop("readonly", true);
        }

        if( kode_barang[0] == "offset" || kode_barang[0] == "etc") {
            $('#Auto_Calc').prop("checked", false);
            $('#Auto_Calc').prop("disabled", true);
        } else {
            $('#Auto_Calc').prop("checked", true);
            $('#Auto_Calc').prop("disabled", false);
        }
    }
}

function outstandinglist() {
    var InvoiceList_setter_check = $('#InvoiceList_setter_check').val();
    var InvoiceList_client_check = $('#InvoiceList_client_check').val();
    var InvoiceList_Qty_check = $('#InvoiceList_Qty_check').val();
    var no_invoice = $('#no_invoice').val();

    $.ajax({
        type: "POST",
        data: {no_invoice:no_invoice, InvoiceList_setter_check:InvoiceList_setter_check, InvoiceList_client_check:InvoiceList_client_check, InvoiceList_Qty_check:InvoiceList_Qty_check},
        url: "Form/Outstanding_PenjualanInvoice_list.php",
        success: function(data){
            $("#outstandinglist").html(data);
        }
    });
}

function invoice_outstanding() {
    var form_setter = $('#form_setter').val();
    var form_client = $('#form_client').val().split(',');
    
    $('#InvoiceList_setter_check').val(form_setter);
    $('#InvoiceList_client_check').val(form_client[0]);
    $('#InvoiceList_Qty_check').val(form_client[1]);
    outstandinglist();
}

function onload() {
    $('#loader').show();
    var search      = $('#search').val();
    var client      = $('#Search_Client').val();
    var Tanggal     = $('#tanggal').val();
    if( search == "" ) {
        var display = "";
    } else {
        var display = "none";
    }

    $.ajax({
        type: "POST",
        data: {data:search, date:Tanggal, client:client, display:display},
        url: "Ajax/invoce_penjualan_Ajax.php",
        success: function(data){
            $('#loader').hide();
            $("#invoce_penjualan").html(data);
        }
    });
}

function SearchData() {
    $('#tanggal').val("");
    var Validasi_Search = $('#search').val().length;

    if(Validasi_Search > 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 3 huruf");
        return false;
    }
}

function SearchClient() {
    $('#tanggal').val("");
    var Validasi_Search = $('#Search_Client').val().length;

    if(Validasi_Search > 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 3 huruf");
        return false;
    }
}

function SearchDate() {
    $('#loader').show();
    onload();
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

function upload_design() {
    if($("#Design").is(':checked')) {
        $(".upload_design").show();
    } else {
        $(".upload_design").hide();
    }
}

function ChangeKodeBrg() {
    var kode_barang = $('#kode_barng').val().split('.');

    var All_Element =  ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "laminating_floor", "KotakNC", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "CuttingSticker", "b_digital", "b_lf", "b_indoor", "b_offset", "b_lain", "b_xbanner", "b_kotak", "b_laminating", "b_finishing", "b_design", "b_delivery", "b_discount"];

    var digital_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi", "b_digital",  "b_kotak", "b_laminating", "b_finishing", "b_design", "b_discount", "b_delivery"];
    var digital_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Hekter_Tengah", "Blok", "Ring_Spiral", "b_lf", "b_indoor", "b_offset", "b_lain", "b_xbanner"];

    var etc_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "Hekter_Tengah",  "Blok", "Ring_Spiral", "b_finishing", "b_lain", "b_discount"];
    var etc_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi", "b_digital", "b_lf", "b_indoor", "b_offset", "b_xbanner", "b_kotak", "b_laminating", "b_design", "b_delivery"];

    var LF_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker",  "b_lf", "b_xbanner", "b_laminating", "b_design", "b_delivery", "b_discount"];
    var LF_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "b_digital", "b_indoor", "b_offset", "b_lain", "b_kotak", "b_finishing"];

    var indoor_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker",  "b_indoor",  "b_xbanner",  "b_laminating","b_design", "b_delivery", "b_discount"];
    var indoor_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "b_lf", "b_digital", "b_offset", "b_lain", "b_kotak", "b_finishing"];

    var Offset_show = ["kilat1", "kilat2", "doff1", "doff2", "KotakNC", "Hekter_Tengah", "Blok", "Ring_Spiral", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "b_offset","b_design", "b_delivery", "b_discount"];
    var Offset_hide = ["kilatdingin1", "doffdingin1", "hard_lemit", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker", "b_digital", "b_lf", "b_indoor",  "b_lain", "b_xbanner", "b_kotak", "b_laminating", "b_finishing"];

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
        $('#finishing').show();

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
        $('#Auto_Calc').prop("checked", false);
        $('#finishing').show();
        
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
        $('#finishing').show();

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
        $('#finishing').show();

    } else if( kode_barang[0] == "offset") {

        var Finishing = ["Ptg_Pts", "Ptg_Gantung", "CuttingSticker", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Spiral", "ukuran", "Auto_Calc"]

        for (i = 0; i < Finishing.length; i++) {
            $('#'+Finishing[i]+'').prop("checked", false);
        }

        for (i = 0; i < Offset_show.length; i++) {
            $('.'+Offset_show[i]+'').show();
        }

        for (i = 0; i < Offset_hide.length; i++) {
            $('.'+Offset_hide[i]+'').hide();
            $('#'+Offset_hide[i]+'').prop("checked", false);
        }

        $('#ukuran_LF').hide();
        $('#panjang').val("");
        $('#lebar').val("");
        $('#ukuran').show();
        $('#ukuran').val("");
        $('#perhitungan_meter').hide();
        $('#finishing').hide();

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
        $('#finishing').show();
    }
}

function calc_meter() {
    //perhitungan meter
    var data = $('#panjang').val()/100 + " x " + $('#lebar').val()/100 + " M ";
    $('#perhitungan_meter').html(data);
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

function file_validasi(id) {

    if(id=="FileImage") {
        var filename = $("#file_Image").val();

        // Use a regular expression to trim everything before final dot
        var extension = filename.replace(/^.*\./, '');

        if (extension == filename) {
            extension = '';
        } else {
            extension = extension.toLowerCase();
        }

        if(extension==="jpg" || extension==="png" || extension==="jpeg" || extension==="gif") {
            $("#Alert_Val_FileImage").html("");
            $("#Val_FileImage").val("1");
        } else {
            $("#Alert_Val_FileImage").html("<br><b style='color:red'>Extention Image Upload Harus jpg, jpeg, png & gif</b>");
            $("#Val_FileImage").val("0");
        }

    } else if(id=="FileDesign") {
        var filename = $("#file_design").val();

        // Use a regular expression to trim everything before final dot
        var extension = filename.replace(/^.*\./, '');

        if (extension == filename) {
            extension = '';
        } else {
            extension = extension.toLowerCase();
        }

        if(extension==="rar" || extension==="zip") {
            $("#Alert_Val_FileDesign").html("");
            $("#Val_FileDesign").val("1");
        } else {
            $("#Alert_Val_FileDesign").html("<br><b style='color:red'>Extention File Upload Harus .Zip & .Rar</b>");
            $("#Val_FileDesign").val("0");
            
        }
    }
};

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

function submit(id) {

    if($('#kode_barng').val()=="") {
        alert("Kode Barang harus di pilih")
        return false;
    } else if($('#client').val()=="") {
        alert("Nama Client tidak Boleh kosong")
        return false;
    } else if($('#validasi_client').val()<1) {
        alert("Nama Client Belum terdaftar & Minta Customer Service Untuk Mendaftarkan Nama Client Tersebut")
        return false;
    } else if($('#validasi_bahan').val()<1) {
        alert("Nama Bahan tidak ada dalam Daftar Stock Barang")
        return false;
    } else if($('#Val_FileImage').val()<1) {
        alert("Extention Image Upload Harus jpg, jpeg, png & gif")
        return false;
    } else if($('#Val_FileDesign').val()<1) {
        alert("Extention File Upload Harus .Zip & .Rar")
        return false;
    }
    
    var ID_Order        = $('#id_order').val();
    var no_invoice      = $('#no_invoice').val();
    var ID_User         = $('#id_user').val();
    var Kode_Brg        = $('#kode_barng').val().split('.');
    var Nama_Client     = $('#client').val();
    var ID_Client       = $('#id_client').val();
    var Deskripsi       = $('#deskripsi').val();
    var Ukuran          = $('#ukuran').val();
    var Panjang         = $('#panjang').val();
    var Lebar           = $('#lebar').val();
    var Sisi;           if($('#satu_sisi').prop("checked") == true) { Sisi = "1"; } else { Sisi = "2"; }
    var ID_Bahan        = $('#id_bahan').val();
    var Nama_Bahan      = $('#bahan').val();
    var Notes           = $('#notes').val();
    var Laminating      = $('#laminating').val().split('.');
    var alat_tambahan   = $('#alat_tambahan').val().split('.');
    var Ptg_Pts;        if($('#Ptg_Pts').prop("checked") == true) { Ptg_Pts = "Y"; } else { Ptg_Pts = "N"; }
    var Ptg_Gantung;    if($('#Ptg_Gantung').prop("checked") == true) { Ptg_Gantung = "Y"; } else { Ptg_Gantung = "N"; }
    var Pon_Garis;      if($('#Pon_Garis').prop("checked") == true) { Pon_Garis = "Y"; } else { Pon_Garis = "N"; }
    var Perporasi;      if($('#Perporasi').prop("checked") == true) { Perporasi = "Y"; } else { Perporasi = "N"; }
    var CuttingSticker; if($('#CuttingSticker').prop("checked") == true) { CuttingSticker = "Y"; } else { CuttingSticker = "N"; }
    var Hekter_Tengah;  if($('#Hekter_Tengah').prop("checked") == true) { Hekter_Tengah = "Y"; } else { Hekter_Tengah = "N"; }
    var Blok;           if($('#Blok').prop("checked") == true) { Blok = "Y"; } else { Blok = "N"; }
    var Spiral;         if($('#Spiral').prop("checked") == true) { Spiral = "Y"; } else { Spiral = "N"; }
    var Qty             = $('#qty').val();
    var Satuan          = $('#satuan').val();
    var Proffing;       if($('#proffing').prop("checked") == true) { Proffing = "Y"; } else { Proffing = "N"; }
    var Ditunggu;       if($('#ditunggu').prop("checked") == true) { Ditunggu = "Y"; } else { Ditunggu = "N"; }
    var Design;         if($('#Design').prop("checked") == true) { Design = "Y"; } else { Design = "N"; }
    if(Laminating[1]== null) { deskripsi_Laminating = ""; } else { deskripsi_Laminating = Laminating[1]; }
    if(alat_tambahan[1]== null) { deskripsi_alat_tambahan = ""; } else { deskripsi_alat_tambahan = alat_tambahan[1]; }
    var b_digital         = $('#b_digital').val();
    var b_kotak           = $('#b_kotak').val();
    var b_lain            = $('#b_lain').val();
    var b_potong          = $('#b_finishing').val();
    var b_large           = $('#b_large').val();
    var b_indoor          = $('#b_indoor').val();
    var b_xbanner         = $('#b_xbanner').val();
    var b_offset          = $('#b_offset').val();
    var b_laminate        = $('#b_laminate').val();
    var b_design          = $('#b_design').val();
    var b_delivery        = $('#b_delivery').val();
    var discount          = $('#discount').val();
    var Auto_Calc;        if($('#Auto_Calc').prop("checked") == true) { Auto_Calc = "Y"; } else { Auto_Calc = "N"; }
    var akses_edit;        if($('#akses_edit').prop("checked") == true) { akses_edit = "Y"; } else { akses_edit = "N"; }

    var fdata = new FormData()
    
    fdata.append("ID_Order",ID_Order);
    fdata.append("no_invoice",no_invoice);
    fdata.append("ID_User",ID_User);
    fdata.append("Kode_Brg", Kode_Brg[0]); 
    fdata.append("Desc_Kode_Brg", Kode_Brg[1]); 
    fdata.append("ID_Client", ID_Client);
    fdata.append("Nama_Client", Nama_Client);
    fdata.append("Deskripsi", Deskripsi);
    fdata.append("Ukuran", Ukuran);
    fdata.append("Panjang", Panjang);
    fdata.append("Lebar", Lebar);
    fdata.append("Sisi", Sisi);
    fdata.append("ID_Bahan", ID_Bahan);
    fdata.append("Nama_Bahan", Nama_Bahan);
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
    fdata.append("Proffing", Proffing);
    fdata.append("Ditunggu", Ditunggu);
    fdata.append("Design", Design);
    fdata.append("jenis_submit", id);
    fdata.append("DesignFile", $('#file_design')[0].files[0]);
    fdata.append("imageFile", $('#file_Image')[0].files[0]);
    fdata.append("b_digital", b_digital);
    fdata.append("b_kotak", b_kotak);
    fdata.append("b_lain", b_lain);
    fdata.append("b_potong", b_potong);
    fdata.append("b_large", b_large);
    fdata.append("b_indoor", b_indoor);
    fdata.append("b_xbanner", b_xbanner);
    fdata.append("b_offset", b_offset);
    fdata.append("b_laminate", b_laminate);
    fdata.append("b_design", b_design);
    fdata.append("b_delivery", b_delivery);
    fdata.append("discount", discount);
    fdata.append("Auto_Calc", Auto_Calc);
    fdata.append("akses_edit", akses_edit);

    $('.progress_table').show();


    $.ajax({
        xhr : function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e){
                if(e.lengthComputable){
                    console.log('Bytes Loaded : ' + e.loaded);
                    console.log('Total Size : ' + e.total);
                    console.log('Persen : ' + (e.loaded / e.total));
                    
                    var percent = Math.round((e.loaded / e.total) * 100);
                    
                    $('#progressBar').attr('aria-valuenow', percent).css({"width": percent + '%', "color": "white", "font-weight": "bold"}).text(percent + '%');
                }
            });
            return xhr;
        },

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
            if(id=="Insert") {
                alert("Data Berhasil Di input ke database !")
            } else {
                alert("Data Berhasil Di Update ke database !")
            }
            $('.progress').hide();
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

function cancel(id) {
    if($('#alasan_cancel').val()=="") {
        alert("Alasan Cancel tidak boleh kosong")
        return false;
    }

    var ID_Order         = $('#id_order').val();
    var Alasan_Cancel    = $('#alasan_cancel').val();

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order, 
            Alasan_Cancel   : Alasan_Cancel,
            jenis_submit    : id
        },
        beforeSend: function(){
            $('#submitBtn').attr("disabled","disabled");
        },
        success: function(data){
            alert("Data berhasil di Cancel !")
            //$("#bagDetail").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}


function submitInvoice(type) {
    var j   = $('#InvoiceList_Qty_check').val();
    var x   = new Array ();
    var y   = new Array ();
    
    for ( var i=1 ; i<=j ; i++) {
		if ($('#cek_'+i).prop('checked')) {
			y[i] = $('#cek_'+i).val();
		} else {
			x[i] = $('#cek_'+i).val();
		}
	}
	var si_yes; si_yes = y.join();
	var si_no; si_no = x.join();
	
	// var si = document.getElementById("form_invoice").value;
    
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            idy             : si_yes, 
            idx             : si_no,
            jenis_submit    : type
        },
        beforeSend: function(){
            // $('.myinput').attr("disabled","disabled");
        },
        success: function(data){
            alert("Invoice berhasil di Dibuka !")
            //$("#Result").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function check_invoice_form(no_invoice) {
    $.ajax({
        type: "POST",
        data: {data:no_invoice},
        url: "Form/check_invoice_f.php",
 
        success: function(data){
            showBox();
            $("#bagDetail").html(data);
        }
    });
}