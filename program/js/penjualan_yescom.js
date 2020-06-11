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

$(item).autocomplete({
    selectFirst: true
});

function onload() {
    $('#loader').show();
    var search = $('#search_data').val();
    var Dari_Tanggal = $('#dari_tanggal').val();
    var Ke_Tanggal = $('#ke_tanggal').val();

    var fdata = new FormData()
    fdata.append("search", search);
    fdata.append("Dari_Tanggal", Dari_Tanggal);
    fdata.append("Ke_Tanggal", Ke_Tanggal);

    $.ajax({
        type: "POST",
        url: "Ajax/penjualan_yescom_ajax.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        success: function (data) {
            $('#loader').hide();
            $("#list_PenjualanYes").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function SearchFrom() {
    $('#search').val("");

    var dari_tanggal = $('#dari_tanggal').val();
    var All_Element = ["ke_tanggal", "Check_box"];

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

function search_data() {
    $('#tanggal').val("");
    var Validasi_Search = $('#search_data').val().length;

    if (Validasi_Search > 3) {
        $('#loader').show();
        onload();
    } else {
        alert("Jumlah Character Harus Lebih dari 3 huruf");
        return false;
    }
}

function submit_GeneratorCode() {
    var id = "Submit_Generator_Code";
    var str = $('#generator_select').val();
    var Start_Val = str.includes("////// ACTION START ---->>>");
    var End_Val = str.includes("<<<---- ACTION END //////");

    if (Start_Val == false && End_Val == false) {
        alert("Error Code Submit");
        return false
    } else {
        $.ajax({
            type: "POST",
            data: {
                generator: str,
                jenis_submit: id
            },
            url: "progress/setter_penjualan_prog.php",
            cache: false,
            success: function (data) {
                // $("#result").html(data);
                alert("Generate Data Complete");
                hidesubBox();
                onload();
                return false;
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    }
}

function hapus(cid, nama_PenjualanYES, status_PenjualanYES) {
    if (status_PenjualanYES == "Y") {
        var abc = "kembalikan";
    } else {
        var abc = "hapus";
    }

    if (confirm(abc + ' WO List "' + nama_PenjualanYES + '" ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                PenjualanYES_ID: cid,
                jenis_submit: "delete_PenjualanYes",
                status_PenjualanYES: status_PenjualanYES
            },
            success: function (data) {
                alert('Order ID "' + nama_PenjualanYES + '" sudah di' + abc);
                onload();
                return false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        });
    }
}

function calc_meter() {
    //perhitungan meter
    var data = $('#panjang').val() / 100 + " x " + $('#lebar').val() / 100 + " M ";
    $('#perhitungan_meter').html(data);
}

function satuan_val() {
    if ($('#satuan').val().toLowerCase() == "kotak" && $('#kode_barng').val().split('.')[0].toLowerCase() == "digital" && $('#id_order').val() == null) {
        $('#alat_tambahan').val("KotakNC.Kotak Kartu Nama");
        $('#Ptg_Pts').prop("checked", true);
    } else {
        // $('#alat_tambahan').val("");
        // $('#Ptg_Pts').prop( "checked", false );
    }
    return false;
}

function ChangeKodeBrg() {
    var kode_barang = $('#kode_barng').val().split('.');

    var All_Element = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "laminating_floor", "KotakNC", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "CuttingSticker"];

    var digital_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi", "b_digital", "b_finishing", "b_kotak", "b_laminate"];
    var digital_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "Hekter_Tengah", "Blok", "Ring_Spiral", "b_lain", "b_large", "b_indoor", "b_xbanner"];

    var etc_show = ["kilat1", "kilat2", "doff1", "doff2", "kilatdingin1", "doffdingin1", "hard_lemit", "KotakNC", "laminating_floor", "Hekter_Tengah", "Blok", "Ring_Spiral", "b_lain", "b_kotak", "b_laminate", "b_finishing"];
    var etc_hide = ["Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "CuttingSticker", "Ptg_Pts", "Ptg_Gantung", "Pon_Garis", "Perporasi", "b_digital", "b_large", "b_indoor", "b_xbanner"];

    var LF_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker", "b_finishing", "b_large", "b_xbanner", "b_finishing", "b_laminate"];
    var LF_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "b_indoor", "b_digital", "b_lain", "b_kotak"];

    var indoor_show = ["kilatdingin1", "doffdingin1", "Ybanner", "RU_60", "RU_80", "RU_85", "Tripod", "Softboard", "laminating_floor", "CuttingSticker", "b_finishing", "b_indoor", "b_xbanner", "b_laminate"];
    var indoor_hide = ["kilat1", "kilat2", "doff1", "doff2", "hard_lemit", "KotakNC", "Ptg_Pts", "Ptg_Gantung", "Hekter_Tengah", "Pon_Garis", "Perporasi", "Blok", "Ring_Spiral", "b_digital", "b_lain", "b_large", "b_kotak"];

    var i;

    if (kode_barang[0] == "digital") {
        satuan_val();

        $('#ukuran_LF').hide();
        $('#panjang').val("");
        $('#lebar').val("");

        for (i = 0; i < digital_show.length; i++) {
            $('.' + digital_show[i] + '').show();
        }

        for (i = 0; i < digital_hide.length; i++) {
            $('.' + digital_hide[i] + '').hide();
            $('#' + digital_hide[i] + '').prop("checked", false);
        }

        $('#ukuran').show();
        $('#ukuran').attr("readonly", true);
        $('#ukuran').attr("disabled", true);
        $('#ukuran').val("A3");
        $('#perhitungan_meter').hide();

    } else if (kode_barang[0] == "etc") {
        satuan_val();

        $('#ukuran_LF').hide();
        $('#panjang').val("");
        $('#lebar').val("");

        for (i = 0; i < etc_show.length; i++) {
            $('.' + etc_show[i] + '').show();
        }

        for (i = 0; i < etc_hide.length; i++) {
            $('.' + etc_hide[i] + '').hide();
            $('#' + etc_hide[i] + '').prop("checked", false);
        }

        $('#ukuran').show();
        $('#ukuran').val("");
        $('#ukuran').attr("readonly", false);
        $('#ukuran').attr("disabled", false);
        $('#perhitungan_meter').hide();


    } else if (kode_barang[0] == "indoor" || kode_barang[0] == "Xuli") {

        for (i = 0; i < indoor_show.length; i++) {
            $('.' + indoor_show[i] + '').show();
        }

        for (i = 0; i < indoor_hide.length; i++) {
            $('.' + indoor_hide[i] + '').hide();
            $('#' + indoor_hide[i] + '').prop("checked", false);
        }

        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#ukuran_LF').show();
        $('#perhitungan_meter').show();
        $('#perhitungan_meter').html("");

    } else if (kode_barang[0] == "large format") {

        for (i = 0; i < LF_show.length; i++) {
            $('.' + LF_show[i] + '').show();
        }

        for (i = 0; i < LF_hide.length; i++) {
            $('.' + LF_hide[i] + '').hide();
            $('#' + LF_hide[i] + '').prop("checked", false);
        }

        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#ukuran_LF').show();
        $('#perhitungan_meter').show();
        $('#perhitungan_meter').html("");

    } else {

        for (i = 0; i < All_Element.length; i++) {
            $('.' + All_Element[i] + '').hide();
            $('#' + All_Element[i] + '').prop("checked", false);
        }

        $('#ukuran_LF').hide();
        $('#ukuran').hide();
        $('#ukuran').val("");
        $('#panjang').val("");
        $('#lebar').val("");
    }
}

function validasi(id) {
    var ID_Data = $('#' + id).val();

    $.ajax({
        type: "POST",
        data: {
            tipe_validasi: "Search_" + id,
            term: ID_Data
        },
        url: "progress/validasi_progress.php",
        success: function (data) {
            if (data > 0) {
                $("#validasi_" + id).val(data);
                $("#Alert_Val" + id).html("");
            } else {
                $("#validasi_" + id).val("0");
                $("#id_" + id).val("");
                $("#Alert_Val" + id).html("<b style='color:red'>" + id + " Belum terdaftar</b>");
            }
        }
    });
}

function test(id) {
    var kode_barang = $('#kode_barng').val().split('.');

    $("#client").autocomplete({
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
        minLength: 2,
        autoFocus: true,
        select: function (event, ui) {
            $("#client").val(ui.item.value);
            $("#id_client").val(ui.item.id);
        },
        change: function (event, ui) {
            $("#client").val(ui.item.value);
            $("#id_client").val(ui.item.id);
        }
    });

    if (kode_barang[0] == "large format" || kode_barang[0] == "indoor" || kode_barang[0] == "Xuli") {
        var data_barang = "LF"
    } else {
        var data_barang = "Digital"
    }

    $("#bahan").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "progress/validasi_progress.php",
                type: "POST",
                data: {
                    tipe_validasi: "autocomplete_Bahan" + data_barang,
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
        minLength: 2,
        autoFocus: true,
        select: function (event, ui) {
            $("#bahan").val(ui.item.value);
            $("#id_bahan").val(ui.item.id);
        },
        change: function (event, ui) {
            $("#bahan").val(ui.item.value);
            $("#id_bahan").val(ui.item.id);
        }
    });

    validasi(id);
}

function Check_KertasSendiri() {
    if ($('#bahan').val() == "Kertas Sendiri") {
        $('#bahan_sendiri').show();
    } else {
        $('#bahan_sendiri').hide();
        $('#bahan_sendiri').val("");
    }
}

function submit_order(id) {
    var id_Order = $('#id_Order').val();
    var Kode_Brg = $('#kode_barng').val().split('.');
    var id_yescom = $('#id_yescom').val();
    var client = $('#client').val();
    var deskripsi = $('#deskripsi').val();
    var ukuran = $('#ukuran').val();
    var panjang = $('#panjang').val();
    var lebar = $('#lebar').val();
    var Sisi;
    if ($('#satu_sisi').prop("checked") == true) {
        Sisi = "1";
    } else {
        Sisi = "2";
    }
    var bahan = $('#bahan').val();
    var id_bahan = $('#id_bahan').val();
    var bahan_sendiri = $('#bahan_sendiri').val();
    var notes = $('#notes').val();
    var qty = $('#qty').val();
    var satuan = $('#satuan').val();
    var wo_yescom = $('#wo_yescom').val();
    var so_yescom = $('#so_yescom').val();
    var warna_cetakan = $('#warna_cetakan').val();
    var Laminating = $('#laminating').val().split('.');
    var alat_tambahan = $('#alat_tambahan').val().split('.');
    var Ptg_Pts;
    if ($('#Ptg_Pts').prop("checked") == true) {
        Ptg_Pts = "Y";
    } else {
        Ptg_Pts = "N";
    }
    var Ptg_Gantung;
    if ($('#Ptg_Gantung').prop("checked") == true) {
        Ptg_Gantung = "Y";
    } else {
        Ptg_Gantung = "N";
    }
    var Pon_Garis;
    if ($('#Pon_Garis').prop("checked") == true) {
        Pon_Garis = "Y";
    } else {
        Pon_Garis = "N";
    }
    var Perporasi;
    if ($('#Perporasi').prop("checked") == true) {
        Perporasi = "Y";
    } else {
        Perporasi = "N";
    }
    var CuttingSticker;
    if ($('#CuttingSticker').prop("checked") == true) {
        CuttingSticker = "Y";
    } else {
        CuttingSticker = "N";
    }
    var Hekter_Tengah;
    if ($('#Hekter_Tengah').prop("checked") == true) {
        Hekter_Tengah = "Y";
    } else {
        Hekter_Tengah = "N";
    }
    var Blok;
    if ($('#Blok').prop("checked") == true) {
        Blok = "Y";
    } else {
        Blok = "N";
    }
    var Spiral;
    if ($('#Spiral').prop("checked") == true) {
        Spiral = "Y";
    } else {
        Spiral = "N";
    }
    var urgent;
    if ($('#urgent').prop("checked") == true) {
        urgent = "Y";
    } else {
        urgent = "N";
    }
    var Proffing;
    if ($('#proffing').prop("checked") == true) {
        Proffing = "Y";
    } else {
        Proffing = "N";
    }
    var Ditunggu;
    if ($('#ditunggu').prop("checked") == true) {
        Ditunggu = "Y";
    } else {
        Ditunggu = "N";
    }
    var b_digital = $('#b_digital').val();
    var b_lain = $('#b_lain').val();
    var b_finishing = $('#b_finishing').val();
    var b_large = $('#b_large').val();
    var b_indoor = $('#b_indoor').val();
    var b_xbanner = $('#b_xbanner').val();
    var b_kotak = $('#b_kotak').val();
    var b_laminate = $('#b_laminate').val();
    var no_invoice = $('#no_invoice').val();
    if (Laminating[1] == null) {
        deskripsi_Laminating = "";
    } else {
        deskripsi_Laminating = Laminating[1];
    }
    if (alat_tambahan[1] == null) {
        deskripsi_alat_tambahan = "";
    } else {
        deskripsi_alat_tambahan = alat_tambahan[1];
    }

    var fdata = new FormData()
    fdata.append("id_Order", id_Order);
    fdata.append("no_invoice", no_invoice);
    fdata.append("Kode_Brg", Kode_Brg[0]);
    fdata.append("Desc_Kode_Brg", Kode_Brg[1]);
    fdata.append("id_yescom", id_yescom);
    fdata.append("Nama_Client", client);
    fdata.append("Deskripsi", deskripsi);
    fdata.append("ukuran", ukuran);
    fdata.append("panjang", panjang);
    fdata.append("lebar", lebar);
    fdata.append("Sisi", Sisi);
    fdata.append("Nama_Bahan", bahan);
    fdata.append("id_bahan", id_bahan);
    fdata.append("bahan_sendiri", bahan_sendiri);
    fdata.append("Notes", notes);
    fdata.append("qty", qty);
    fdata.append("Satuan", satuan);
    fdata.append("wo_yescom", wo_yescom);
    fdata.append("so_yescom", so_yescom);
    fdata.append("warna_cetakan", warna_cetakan);
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
    fdata.append("urgent", urgent);
    fdata.append("Proffing", Proffing);
    fdata.append("Ditunggu", Ditunggu);
    fdata.append("b_digital", b_digital);
    fdata.append("b_lain", b_lain);
    fdata.append("b_finishing", b_finishing);
    fdata.append("b_large", b_large);
    fdata.append("b_indoor", b_indoor);
    fdata.append("b_xbanner", b_xbanner);
    fdata.append("b_kotak", b_kotak);
    fdata.append("b_laminate", b_laminate);
    fdata.append("jenis_submit", id);

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        beforeSend: function () {
            // $('#submitBtn').attr("disabled", "disabled");
            // $(".icon-close").removeAttr('onclick');
        },
        success: function (data) {
            $("#Result").html(data);
            // hideBox();
            // onload();
            // return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });
}

function acc_progress(status, id) {
    if (confirm(status + ' Sudah Ok ?')) {
        $.ajax({
            type: "POST",
            url: "progress/setter_penjualan_prog.php",
            data: {
                oid: id,
                jenis_submit: "acc_penjualan"
            },
            success: function (data) {
                alert(status + ' sudah di ACC');
                onload();
                return false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#bagDetail").html(XMLHttpRequest);
            }
        });
    }
}

function print_report() {
    var dari_tanggal = $('#dari_tanggal').val();
    var ke_tanggal = $('#ke_tanggal').val();

    window.open('print_WOList_report.php?Status_Print=print_invoice&dari_tgl=' + dari_tanggal + '&ke_tgl=' + ke_tanggal, '_blank');
}

function akses(a, ID_Order) {
    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order: ID_Order,
            jenis_akses: a,
            jenis_submit: "Akses_Edit"
        },
        success: function (data) {
            onload();
            return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });
}

function AksesEdit() {
    var kode_barang = $('#kode_barng').val().split('.');
    var level_user = $('#level_user').val()
    var AksesEdit;
    if ($('#akses_edit').prop("checked") == true) {
        AksesEdit = "Y";
    } else {
        AksesEdit = "N";
    }
    var AutoCalc;
    if ($('#Auto_Calc').prop("checked") == true) {
        AutoCalc = "Y";
    } else {
        AutoCalc = "N";
    }
    var Akses = ["b_digital", "b_kotak", "b_finishing", "b_large", "b_indoor", "b_xbanner", "b_laminate"];

    if (AksesEdit === "Y") {
        for (i = 0; i < Akses.length; i++) {
            if (level_user != "admin") {
                $('#' + Akses[i] + '').prop("disabled", true);
                $('#' + Akses[i] + '').prop("readonly", true);
                $('#Auto_Calc').prop("checked", true);
                $('#Auto_Calc').prop("disabled", true);
            } else {
                $('#' + Akses[i] + '').prop("disabled", false);
                $('#' + Akses[i] + '').prop("readonly", false);
                if (kode_barang[0] == "offset" || kode_barang[0] == "etc") {
                    $('#Auto_Calc').prop("checked", false);
                    $('#Auto_Calc').prop("disabled", true);
                } else {
                    $('#Auto_Calc').prop("checked", false);
                    $('#Auto_Calc').prop("disabled", true);
                }
            }
        }
    } else {
        for (i = 0; i < Akses.length; i++) {
            $('#' + Akses[i] + '').prop("disabled", true);
            $('#' + Akses[i] + '').prop("readonly", true);
        }

        if (kode_barang[0] == "offset" || kode_barang[0] == "etc") {
            $('#Auto_Calc').prop("checked", false);
            $('#Auto_Calc').prop("disabled", true);
        } else {
            $('#Auto_Calc').prop("checked", true);
            $('#Auto_Calc').prop("disabled", false);
        }
    }
}

function toggle(pilih) {
    checkboxes = $('[name="option"]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = pilih.checked;
    }
}

function outstandinglist(id) {
    var InvoiceList_JenisWO_check = $('#InvoiceList_JenisWO_check').val();
    var InvoiceList_Kode_check = $('#InvoiceList_Kode_check').val();
    var no_invoice = $('#no_invoice').val();

    $.ajax({
        type: "POST",
        data: {
            Invoice_Number: no_invoice,
            InvoiceList_JenisWO_check: InvoiceList_JenisWO_check,
            InvoiceList_Kode_check: InvoiceList_Kode_check,
            status: id
        },
        url: "Form/Outstanding_PenjualanInvoice_list.php",
        success: function (data) {
            $("#outstandinglist").html(data);
        }
    });
}

function invoice_outstanding() {
    var form_jenisWO = $('#form_jenisWO').val();
    var form_Kode = $('#form_Kode').val().split(',');

    $('#InvoiceList_JenisWO_check').val(form_jenisWO);
    $('#InvoiceList_Kode_check').val(form_Kode[0]);
    $('#InvoiceList_Qty_check').val(form_Kode[1]);
    outstandinglist('penjualan_invoice_yescom');
}

function submitInvoice(type) {
    var si = $("#no_invoice").val();
    var j = $('#InvoiceList_Qty_check').val();
    var x = new Array();
    var y = new Array();

    for (var i = 1; i <= j; i++) {
        if ($('#cek_' + i).prop('checked')) {
            y[i] = $('#cek_' + i).val();
        } else {
            x[i] = $('#cek_' + i).val();
        }
    }
    var si_yes;
    si_yes = y.join();
    var si_no;
    si_no = x.join();

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            idy: si_yes,
            idx: si_no,
            no_invoice: si,
            jenis_submit: type
        },
        beforeSend: function () {
            // $('.myinput').attr("disabled", "disabled");
        },
        success: function (data) {
            $("#Result").html(data);
            // hideBox();
            // onload();
            // return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });
}

function autoCalc() {
    var ID_Order = $('#id_Order').val();
    var no_invoice = $('#no_invoice').val();
    var Kode_Brg = $('#kode_barng').val().split('.');
    var Panjang = $('#panjang').val();
    var Lebar = $('#lebar').val();
    var Sisi;
    if ($('#satu_sisi').prop("checked") == true) {
        Sisi = "1";
    } else {
        Sisi = "2";
    }
    var ID_Bahan = $('#id_bahan').val();
    var Laminating = $('#laminating').val().split('.');
    var alat_tambahan = $('#alat_tambahan').val().split('.');
    var Ptg_Pts;
    if ($('#Ptg_Pts').prop("checked") == true) {
        Ptg_Pts = "Y";
    } else {
        Ptg_Pts = "N";
    }
    var Ptg_Gantung;
    if ($('#Ptg_Gantung').prop("checked") == true) {
        Ptg_Gantung = "Y";
    } else {
        Ptg_Gantung = "N";
    }
    var Pon_Garis;
    if ($('#Pon_Garis').prop("checked") == true) {
        Pon_Garis = "Y";
    } else {
        Pon_Garis = "N";
    }
    var Perporasi;
    if ($('#Perporasi').prop("checked") == true) {
        Perporasi = "Y";
    } else {
        Perporasi = "N";
    }
    var CuttingSticker;
    if ($('#CuttingSticker').prop("checked") == true) {
        CuttingSticker = "71";
    } else {
        CuttingSticker = "";
    }
    var Qty = $('#qty').val();
    var Satuan = $('#satuan').val();

    var fdata = new FormData()

    fdata.append("ID_Order", ID_Order);
    fdata.append("no_invoice", no_invoice);
    fdata.append("Kode_Brg", Kode_Brg[0]);
    fdata.append("Panjang", Panjang);
    fdata.append("Lebar", Lebar);
    fdata.append("Sisi", Sisi);
    fdata.append("ID_Bahan", ID_Bahan);
    fdata.append("Laminating", Laminating[0]);
    fdata.append("alat_tambahan", alat_tambahan[0]);
    fdata.append("Ptg_Pts", Ptg_Pts);
    fdata.append("Ptg_Gantung", Ptg_Gantung);
    fdata.append("Pon_Garis", Pon_Garis);
    fdata.append("Perporasi", Perporasi);
    fdata.append("ID_CuttingStiker", CuttingSticker);
    fdata.append("Qty", Qty);
    fdata.append("Satuan", Satuan);
    fdata.append("tipe_validasi", 'AutoCalc_Price');

    $.ajax({
        type: "POST",
        url: "progress/validasi_progress.php",
        cache: false,
        processData: false,
        contentType: false,
        data: fdata,
        success: function (data) {
            var obj = $.parseJSON(data);
            // $('#result').html(obj.b_large);
            $('#b_digital').val(obj.b_digital);
            $('#b_kotak').val(obj.b_kotak);
            $('#b_finishing').val(obj.b_finishing);
            $('#b_large').val(obj.b_large);
            $('#b_indoor').val(obj.b_indoor);
            $('#b_xbanner').val(obj.b_xbanner);
            $('#b_laminate').val(obj.b_laminate);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });
}