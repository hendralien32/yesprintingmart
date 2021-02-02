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

function outstandinglist(id) {
  var InvoiceList_setter_check = $("#InvoiceList_setter_check").val();
  var InvoiceList_client_check = $("#InvoiceList_client_check").val();
  var InvoiceList_Qty_check = $("#InvoiceList_Qty_check").val();
  var no_invoice = $("#no_invoice").val();

  $.ajax({
    type: "POST",
    data: {
      Invoice_Number: no_invoice,
      InvoiceList_setter_check: InvoiceList_setter_check,
      InvoiceList_client_check: InvoiceList_client_check,
      InvoiceList_Qty_check: InvoiceList_Qty_check,
      status: id,
    },
    url: "Form/Outstanding_PenjualanInvoice_list.php",
    success: function (data) {
      $("#outstandinglist").html(data);
    },
  });
}

function invoice_outstanding() {
  var form_setter = $("#form_setter").val();
  var form_client = $("#form_client").val().split(",");

  $("#InvoiceList_setter_check").val(form_setter);
  $("#InvoiceList_client_check").val(form_client[0]);
  $("#InvoiceList_Qty_check").val(form_client[1]);
  outstandinglist("setter_penjualan_invoice");
}

function onload() {
  $("#loader").show();
  var search = $("#search").val();
  var client = $("#Search_Client").val();
  var Tanggal = $("#tanggal").val();
  var Setter_Sort = $("#test").val();

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
      client: client,
      Setter_Sort: Setter_Sort,
    },
    url: "Ajax/setter_penjualan_Ajax.php",
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

function SearchData() {
  $("#tanggal").val("");
  var Validasi_Search = $("#search").val().length;

  if (Validasi_Search > 3) {
    $("#loader").show();
    onload();
  } else {
    alert("Jumlah Character Harus Lebih dari 3 huruf");
    return false;
  }
}

function SearchClient() {
  $("#tanggal").val("");
  var Validasi_Search = $("#Search_Client").val().length;

  if (Validasi_Search > 3) {
    $("#loader").show();
    onload();
  } else {
    alert("Jumlah Character Harus Lebih dari 3 huruf");
    return false;
  }
}

function SearchDate() {
  $("#loader").show();
  onload();
}

function SetterSearch() {
  var Setter_Sort = $("#SetterSearch").val();
  $("#test").val(Setter_Sort);
  $("#loader").show();
  onload();
}

function satuan_val() {
  if (
    $("#satuan").val().toLowerCase() == "kotak" &&
    $("#kode_barng").val().split(".")[0].toLowerCase() == "digital" &&
    $("#id_order").val() == null
  ) {
    $("#alat_tambahan").val("KotakNC.Kotak Kartu Nama");
    $("#Ptg_Pts").prop("checked", true);
  } else {}
  return false;
}

function upload_design() {
  if ($("#Design").is(":checked")) {
    $(".upload_design").show();
  } else {
    $(".upload_design").hide();
  }
}

function ChangeKodeBrg() {
  var kode_barang = $("#kode_barng").val().split(".");

  var All_Element = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "kilatdingin1",
    "doffdingin1",
    "hard_lemit",
    "laminating_floor",
    "KotakNC",
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "Ptg_Pts",
    "Ptg_Gantung",
    "Hekter_Tengah",
    "Pon_Garis",
    "Perporasi",
    "Blok",
    "Ring_Spiral",
    "CuttingSticker",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var digital_show = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "kilatdingin1",
    "doffdingin1",
    "hard_lemit",
    "KotakNC",
    "laminating_floor",
    "CuttingSticker",
    "Ptg_Pts",
    "Ptg_Gantung",
    "Pon_Garis",
    "Perporasi",
  ];
  var digital_hide = [
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "Hekter_Tengah",
    "Blok",
    "Ring_Spiral",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var etc_show = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "kilatdingin1",
    "doffdingin1",
    "hard_lemit",
    "Ptg_Pts",
    "Ptg_Gantung",
    "KotakNC",
    "laminating_floor",
    "Hekter_Tengah",
    "Blok",
    "Ring_Spiral",
  ];
  var etc_hide = [
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "CuttingSticker",
    "Pon_Garis",
    "Perporasi",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var LF_show = [
    "kilatdingin1",
    "doffdingin1",
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "laminating_floor",
    "CuttingSticker",
  ];
  var LF_hide = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "hard_lemit",
    "KotakNC",
    "Ptg_Pts",
    "Ptg_Gantung",
    "Hekter_Tengah",
    "Pon_Garis",
    "Perporasi",
    "Blok",
    "Ring_Spiral",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var indoor_show = [
    "kilatdingin1",
    "doffdingin1",
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "laminating_floor",
    "CuttingSticker",
  ];
  var indoor_hide = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "hard_lemit",
    "KotakNC",
    "Ptg_Pts",
    "Ptg_Gantung",
    "Hekter_Tengah",
    "Pon_Garis",
    "Perporasi",
    "Blok",
    "Ring_Spiral",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var Offset_show = [
    "kilat1",
    "kilat2",
    "doff1",
    "doff2",
    "KotakNC",
    "Hekter_Tengah",
    "Blok",
    "Ring_Spiral",
    "Ptg_Gantung",
    "Hekter_Tengah",
    "Pon_Garis",
    "Perporasi",
    "Blok",
    "Ring_Spiral",
  ];
  var Offset_hide = [
    "kilatdingin1",
    "doffdingin1",
    "hard_lemit",
    "Ybanner",
    "RU_60",
    "RU_80",
    "RU_85",
    "Tripod",
    "Softboard",
    "laminating_floor",
    "CuttingSticker",
    "b_digital",
    "b_lf",
    "b_indoor",
    "b_offset",
    "b_lain",
    "b_xbanner",
    "b_kotak",
    "b_laminating",
    "b_finishing",
    "b_design",
    "b_delivery",
    "b_discount",
  ];

  var i;

  if (kode_barang[0] == "digital") {
    satuan_val();

    $("#ukuran_LF").hide();
    $("#panjang").val("");
    $("#lebar").val("");

    for (i = 0; i < digital_show.length; i++) {
      $("." + digital_show[i] + "").show();
    }

    for (i = 0; i < digital_hide.length; i++) {
      $("." + digital_hide[i] + "").hide();
      $("#" + digital_hide[i] + "").prop("checked", false);
    }

    $("#ukuran").show();
    $("#ukuran").attr("readonly", true);
    $("#ukuran").attr("disabled", true);
    $("#ukuran").val("A3");
    $("#perhitungan_meter").hide();
  } else if (kode_barang[0] == "etc") {
    satuan_val();

    $("#ukuran_LF").hide();
    $("#panjang").val("");
    $("#lebar").val("");

    for (i = 0; i < etc_show.length; i++) {
      $("." + etc_show[i] + "").show();
    }

    for (i = 0; i < etc_hide.length; i++) {
      $("." + etc_hide[i] + "").hide();
      $("#" + etc_hide[i] + "").prop("checked", false);
    }

    $("#ukuran").show();
    $("#ukuran").val("");
    $("#ukuran").attr("readonly", false);
    $("#ukuran").attr("disabled", false);
    $("#perhitungan_meter").hide();
  } else if (kode_barang[0] == "indoor" || kode_barang[0] == "Xuli") {
    for (i = 0; i < indoor_show.length; i++) {
      $("." + indoor_show[i] + "").show();
    }

    for (i = 0; i < indoor_hide.length; i++) {
      $("." + indoor_hide[i] + "").hide();
      $("#" + indoor_hide[i] + "").prop("checked", false);
    }

    $("#ukuran").hide();
    $("#ukuran").val("");
    $("#ukuran_LF").show();
    $("#perhitungan_meter").show();
    $("#perhitungan_meter").html("");
  } else if (kode_barang[0] == "large format") {
    for (i = 0; i < LF_show.length; i++) {
      $("." + LF_show[i] + "").show();
    }

    for (i = 0; i < LF_hide.length; i++) {
      $("." + LF_hide[i] + "").hide();
      $("#" + LF_hide[i] + "").prop("checked", false);
    }

    $("#ukuran").hide();
    $("#ukuran").val("");
    $("#ukuran_LF").show();
    $("#perhitungan_meter").show();
    $("#perhitungan_meter").html("");
  } else if (kode_barang[0] == "offset") {
    for (i = 0; i < Offset_show.length; i++) {
      $("." + Offset_show[i] + "").show();
    }

    for (i = 0; i < Offset_hide.length; i++) {
      $("." + Offset_hide[i] + "").hide();
      $("#" + Offset_hide[i] + "").prop("checked", false);
    }

    $("#ukuran_LF").hide();
    $("#panjang").val("");
    $("#lebar").val("");
    $("#ukuran").show();
    $("#ukuran").val("");
    $("#ukuran").attr("readonly", false);
    $("#ukuran").attr("disabled", false);
    $("#perhitungan_meter").hide();
  } else {
    for (i = 0; i < All_Element.length; i++) {
      $("." + All_Element[i] + "").hide();
      $("#" + All_Element[i] + "").prop("checked", false);
    }

    $("#ukuran_LF").hide();
    $("#ukuran").hide();
    $("#ukuran").val("");
    $("#panjang").val("");
    $("#lebar").val("");
  }
}

function calc_meter() {
  //perhitungan meter
  var data =
    $("#panjang").val() / 100 + " x " + $("#lebar").val() / 100 + " M ";
  $("#perhitungan_meter").html(data);
}

function akses(a, ID_Order) {
  $.ajax({
    type: "POST",
    url: "progress/setter_penjualan_prog.php",
    data: {
      ID_Order: ID_Order,
      jenis_akses: a,
      jenis_submit: "Akses_Edit",
    },
    success: function (data) {
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#bagDetail").html(XMLHttpRequest);
    },
  });
}

function AksesEdit() {
  var kode_barang = $("#kode_barng").val().split(".");
  var AksesEdit;
  if ($("#akses_edit").prop("checked") == true) {
    AksesEdit = "Y";
  } else {
    AksesEdit = "N";
  }
  var AutoCalc;
  if ($("#Auto_Calc").prop("checked") == true) {
    AutoCalc = "Y";
  } else {
    AutoCalc = "N";
  }
  var Akses = [
    "b_digital",
    "b_kotak",
    "b_finishing",
    "b_lf",
    "b_indoor",
    "b_xbanner",
    "b_laminate",
  ];

  if (AksesEdit === "Y") {
    for (i = 0; i < Akses.length; i++) {
      $("#" + Akses[i] + "").prop("disabled", false);
      $("#" + Akses[i] + "").prop("readonly", false);
    }
    $("#Auto_Calc").prop("checked", true);
  } else {
    for (i = 0; i < Akses.length; i++) {
      $("#" + Akses[i] + "").prop("disabled", true);
      $("#" + Akses[i] + "").prop("readonly", true);
    }
    $("#Auto_Calc").prop("checked", true);
  }
}

function validasi(id) {
  var ID_Data = $("#" + id).val();

  $.ajax({
    type: "POST",
    data: {
      tipe_validasi: "Search_" + id,
      term: ID_Data,
    },
    url: "progress/validasi_progress.php",
    success: function (data) {
      if (data > 0 && $("#id_client").val() != "") {
        $("#validasi_" + id).val(data);
        $("#Alert_Val" + id).html(
          "<i class='fad fa-check-double' style='margin-left:10px;'></i>"
        );
      } else {
        $("#validasi_" + id).val("0");
        $("#id_" + id).val("");
        $("#Alert_Val" + id).html(
          "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i>"
        );
      }
    },
  });
}

function file_validasi(id) {
  if (id == "FileImage") {
    var filename = $("#file_Image").val();

    // Use a regular expression to trim everything before final dot
    var extension = filename.replace(/^.*\./, "");

    if (extension == filename) {
      extension = "";
    } else {
      extension = extension.toLowerCase();
    }

    if (
      extension === "jpg" ||
      extension === "png" ||
      extension === "jpeg" ||
      extension === "gif"
    ) {
      $("#Alert_Val_FileImage").html("");
      $("#Val_FileImage").val("1");
    } else {
      $("#Alert_Val_FileImage").html(
        "<br><b style='color:red'>Extention Image Upload Harus jpg, jpeg, png & gif</b>"
      );
      $("#Val_FileImage").val("0");
    }
  } else if (id == "FileDesign") {
    var filename = $("#file_design").val();

    // Use a regular expression to trim everything before final dot
    var extension = filename.replace(/^.*\./, "");

    if (extension == filename) {
      extension = "";
    } else {
      extension = extension.toLowerCase();
    }

    if (extension === "rar" || extension === "zip") {
      $("#Alert_Val_FileDesign").html("");
      $("#Val_FileDesign").val("1");
    } else {
      $("#Alert_Val_FileDesign").html(
        "<br><b style='color:red'>Extention File Upload Harus .Zip & .Rar</b>"
      );
      $("#Val_FileDesign").val("0");
    }
  }
}

function test(id) {
  var kode_barang = $("#kode_barng").val().split(".");

  $("#client").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_client",
          term: request.term,
        },
        dataType: "json",
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.nama_client + " - " + item.no_telp,
                value: item.nama_client,
                id: item.cid,
              };
            })
          );
        },
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
    },
  });

  if (
    kode_barang[0] == "indoor" ||
    kode_barang[0] == "Xuli"
  ) {
    var data_barang = "Indoor";
  } else if (
    kode_barang[0] == "large format"
  ) {
    var data_barang = "LF";
  } else {
    var data_barang = "Digital";
  }

  $("#bahan").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_Bahan" + data_barang,
          term: request.term,
        },
        dataType: "json",
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.nama_barang,
                value: item.nama_barang,
                id: item.id_barang,
              };
            })
          );
        },
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
    },
  });

  validasi(id);
}

function submit(id) {
  if ($("#kode_barng").val() == "") {
    alert("Kode Barang harus di pilih");
    return false;
  } else if ($("#client").val() == "") {
    alert("Nama Client tidak Boleh kosong");
    return false;
  } else if ($("#validasi_client").val() < 1) {
    alert(
      "Nama Client Belum terdaftar & Minta Customer Service Untuk Mendaftarkan Nama Client Tersebut"
    );
    return false;
  } else if ($("#qty").val() == "" || $("#qty").val() == 0) {
    alert("Qty Tidak Boleh Kosong atau 0");
    return false;
  } else if ($("#validasi_bahan").val() < 1) {
    alert("Nama Bahan tidak ada dalam Daftar Stock Barang");
    return false;
  } else if ($("#Val_FileImage").val() < 1) {
    alert("Extention Image Upload Harus jpg, jpeg, png & gif");
    return false;
  } else if ($("#Val_FileDesign").val() < 1) {
    alert("Extention File Upload Harus .Zip & .Rar");
    return false;
  }

  var ID_Order = $("#id_order").val();
  var no_invoice = $("#no_invoice").val();
  var ID_User = $("#id_user").val();
  var Kode_Brg = $("#kode_barng").val().split(".");
  var Nama_Client = $("#client").val();
  var ID_Client = $("#id_client").val();
  var Deskripsi = $("#deskripsi").val();
  var Ukuran = $("#ukuran").val();
  var Panjang = $("#panjang").val();
  var Lebar = $("#lebar").val();
  var warna_cetakan = $("#warna_cetakan").val();
  var ID_Bahan = $("#id_bahan").val();
  var Nama_Bahan = $("#bahan").val();
  var Notes = $("#notes").val();
  var Laminating = $("#laminating").val().split(".");
  var alat_tambahan = $("#alat_tambahan").val().split(".");
  var inv_check = $("#inv_check").val();
  var Qty = $("#qty").val();
  var Satuan = $("#satuan").val();
  var Ptg_Pts;
  var Ptg_Gantung;
  var Pon_Garis;
  var Perporasi;
  var CuttingSticker;
  var Hekter_Tengah;
  var Blok;
  var Spiral;
  var Proffing;
  var Ditunggu;
  var Design;
  var Auto_Calc;
  var akses_edit;
  var Sisi;
  var b_digital;
  var b_kotak;
  var b_lain;
  var b_potong;
  var b_large;
  var b_indoor;
  var b_xbanner;
  var b_offset;
  var b_laminate;
  var b_design;
  var b_delivery;
  var discount;

  if ($("#satu_sisi").prop("checked") == true) {
    Sisi = "1";
  } else {
    Sisi = "2";
  }
  if ($("#Ptg_Pts").prop("checked") == true) {
    Ptg_Pts = "Y";
  } else {
    Ptg_Pts = "N";
  }
  if ($("#Ptg_Gantung").prop("checked") == true) {
    Ptg_Gantung = "Y";
  } else {
    Ptg_Gantung = "N";
  }
  if ($("#Pon_Garis").prop("checked") == true) {
    Pon_Garis = "Y";
  } else {
    Pon_Garis = "N";
  }
  if ($("#Perporasi").prop("checked") == true) {
    Perporasi = "Y";
  } else {
    Perporasi = "N";
  }
  if ($("#CuttingSticker").prop("checked") == true) {
    CuttingSticker = "Y";
  } else {
    CuttingSticker = "N";
  }
  if ($("#Hekter_Tengah").prop("checked") == true) {
    Hekter_Tengah = "Y";
  } else {
    Hekter_Tengah = "N";
  }
  if ($("#Blok").prop("checked") == true) {
    Blok = "Y";
  } else {
    Blok = "N";
  }
  if ($("#Spiral").prop("checked") == true) {
    Spiral = "Y";
  } else {
    Spiral = "N";
  }
  if ($("#proffing").prop("checked") == true) {
    Proffing = "Y";
  } else {
    Proffing = "N";
  }
  if ($("#ditunggu").prop("checked") == true) {
    Ditunggu = "Y";
  } else {
    Ditunggu = "N";
  }
  if ($("#Design").prop("checked") == true) {
    Design = "Y";
  } else {
    Design = "N";
  }
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
  if ($("#Auto_Calc").prop("checked") == true) {
    Auto_Calc = "Y";
  } else {
    Auto_Calc = "N";
  }
  if ($("#akses_edit").prop("checked") == true) {
    akses_edit = "Y";
  } else {
    akses_edit = "N";
  }
  if ($("#b_digital").val() == undefined) {
    b_digital = "0";
  } else {
    b_digital = $("#b_digital").val();
  }
  if ($("#b_kotak").val() == undefined) {
    b_kotak = "0";
  } else {
    b_kotak = $("#b_kotak").val();
  }
  if ($("#b_lain").val() == undefined) {
    b_lain = "0";
  } else {
    b_lain = $("#b_lain").val();
  }
  if ($("#b_finishing").val() == undefined) {
    b_potong = "0";
  } else {
    b_potong = $("#b_finishing").val();
  }
  if ($("#b_lf").val() == undefined) {
    b_large = "0";
  } else {
    b_large = $("#b_lf").val();
  }
  if ($("#b_indoor").val() == undefined) {
    b_indoor = "0";
  } else {
    b_indoor = $("#b_indoor").val();
  }
  if ($("#b_xbanner").val() == undefined) {
    b_xbanner = "0";
  } else {
    b_xbanner = $("#b_xbanner").val();
  }
  if ($("#b_offset").val() == undefined) {
    b_offset = "0";
  } else {
    b_offset = $("#b_offset").val();
  }
  if ($("#b_laminate").val() == undefined) {
    b_laminate = "0";
  } else {
    b_laminate = $("#b_laminate").val();
  }
  if ($("#b_design").val() == undefined) {
    b_design = "0";
  } else {
    b_design = $("#b_design").val();
  }
  if ($("#b_delivery").val() == undefined) {
    b_delivery = "0";
  } else {
    b_delivery = $("#b_delivery").val();
  }
  if ($("#discount").val() == undefined) {
    discount = "0";
  } else {
    discount = $("#discount").val();
  }

  var fdata = new FormData();

  fdata.append("ID_Order", ID_Order);
  fdata.append("no_invoice", no_invoice);
  fdata.append("ID_User", ID_User);
  fdata.append("Kode_Brg", Kode_Brg[0]);
  fdata.append("Desc_Kode_Brg", Kode_Brg[1]);
  fdata.append("ID_Client", ID_Client);
  fdata.append("Nama_Client", Nama_Client);
  fdata.append("Deskripsi", Deskripsi);
  fdata.append("Ukuran", Ukuran);
  fdata.append("Panjang", Panjang);
  fdata.append("Lebar", Lebar);
  fdata.append("warna_cetakan", warna_cetakan);
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
  fdata.append("DesignFile", $("#file_design")[0].files[0]);
  fdata.append("imageFile", $("#file_Image")[0].files[0]);
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
  fdata.append("inv_check", inv_check);

  $(".progress_table").show();

  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function (e) {
        if (e.lengthComputable) {
          console.log("Bytes Loaded : " + e.loaded);
          console.log("Total Size : " + e.total);
          console.log("Persen : " + e.loaded / e.total);

          var percent = Math.round((e.loaded / e.total) * 100);

          $("#progressBar")
            .attr("aria-valuenow", percent)
            .css({
              width: percent + "%",
              color: "white",
              "font-weight": "bold",
            })
            .text(percent + "%");
        }
      });
      return xhr;
    },

    type: "POST",
    url: "progress/setter_penjualan_prog.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    beforeSend: function () {
      $("#submitBtn").attr("disabled", "disabled");
      $(".icon-close").removeAttr("onclick");
    },
    success: function (data) {
      $(".progress").hide();
      // $("#Result").html(data);
      hideBox();
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#bagDetail").html(XMLHttpRequest);
    },
  });
}

function cancel(id) {
  if ($("#alasan_cancel").val() == "") {
    alert("Alasan Cancel tidak boleh kosong");
    return false;
  }

  var ID_Order = $("#id_order").val();
  var Alasan_Cancel = $("#alasan_cancel").val();

  $.ajax({
    type: "POST",
    url: "progress/setter_penjualan_prog.php",
    data: {
      ID_Order: ID_Order,
      Alasan_Cancel: Alasan_Cancel,
      jenis_submit: id,
    },
    beforeSend: function () {
      $("#submitBtn").attr("disabled", "disabled");
    },
    success: function (data) {
      alert("Data berhasil di Cancel !");
      //$("#bagDetail").html(data);
      hideBox();
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#bagDetail").html(XMLHttpRequest);
    },
  });
}

function submitInvoice(type) {
  var si = $("#no_invoice").val();
  var j = $("#InvoiceList_Qty_check").val();
  var x = new Array();
  var y = new Array();

  for (var i = 1; i <= j; i++) {
    if ($("#cek_" + i).prop("checked")) {
      y[i] = $("#cek_" + i).val();
    } else {
      x[i] = $("#cek_" + i).val();
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
      jenis_submit: type,
    },
    beforeSend: function () {
      $(".myinput").attr("disabled", "disabled");
    },
    success: function (data) {
      // $("#Result").html(data);
      hideBox();
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#bagDetail").html(XMLHttpRequest);
    },
  });
}

function acc_progress(status, id) {
  if (confirm(status + " Sudah Ok ?")) {
    $.ajax({
      type: "POST",
      url: "progress/setter_penjualan_prog.php",
      data: {
        oid: id,
        jenis_submit: "acc_penjualan",
      },
      success: function (data) {
        alert(status + " sudah di ACC");
        onload();
        return false;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $("#bagDetail").html(XMLHttpRequest);
      },
    });
  }
}

function selesai_prog(status, id, finished) {
  if (confirm(status + " mau diubah Statusny ?")) {
    $.ajax({
      type: "POST",
      url: "progress/setter_penjualan_prog.php",
      data: {
        oid: id,
        jenis_submit: "selesai_penjualan",
        finished: finished,
      },
      success: function (data) {
        onload();
        return false;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $("#bagDetail").html(XMLHttpRequest);
      },
    });
  }
}