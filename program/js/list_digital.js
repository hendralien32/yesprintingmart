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
  var Tanggal = $("#tanggal").val();
  var session_bahan = $("#session_kertas").val();
  var type_mesin = $("#type_mesin").val();

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
      session_bahan: session_bahan,
      type_mesin: type_mesin,
    },
    url: "Ajax/DigitalPrinting_List_ajax.php",
    cache: false,
    success: function (data) {
      if (type_mesin != "") {
        $("#button_rusak").css("display", "");
      } else {
        $("#button_rusak").css("display", "none");
      }
      $("#loader").hide();
      $("#setter_penjualan").html(data);
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}

function BahanSearch() {
  var Bahan_Sort = $("#BahanSearch").val();
  $("#session_kertas").val(Bahan_Sort);
  $("#loader").show();
  onload();
}

function SearchDate() {
  $("#loader").show();
  onload();
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

function session_mesin() {
  var type_mesin = $("#type_mesin").val();
  $("#session_mesinDP").val(type_mesin);
  if (type_mesin != "") {
    $("#button_rusak").css("display", "");
  } else {
    $("#button_rusak").css("display", "none");
  }
  $("#loader").show();
  onload();
}

function BahanDigital_Search(id) {
  $("#BahanDigital").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_BahanDigital",
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
    minLength: 1,
    autoFocus: true,
    select: function (event, ui) {
      $("#BahanDigital").val(ui.item.value);
      $("#id_BahanDigital").val(ui.item.id);
    },
    change: function (event, ui) {
      $("#BahanDigital").val(ui.item.value);
      $("#id_BahanDigital").val(ui.item.id);
    },
  });

  validasi(id);
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
      if (data > 0) {
        $("#validasi_" + id).val(data);
        $("#Alert_Val" + id).html(
          "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
        );
      } else {
        $("#validasi_" + id).val("0");
        $("#id_" + id).val("");
        $("#Alert_Val" + id).html(
          "<i class='fas fa-times-octagon' style='color:red; margin-left:10px; margin-right:5px;'></i>"
        );
      }
    },
  });
}

function submit(id) {
  if ($("#validasi_BahanDigital").val() == 0) {
    alert("Nama Bahan Tidak terdaftar");
    return false;
  } else if ($("#Qty").val() > $("#Val_Qty").val()) {
    alert("Qty Input Lebih besar dari Qty Order");
    return false;
  }

  let id_order = $("#id_order").val();
  let tanggal_ptg = $("#tanggal_ptg").val();
  let id_BahanDigital = $("#id_BahanDigital").val();
  let BahanDigital = $("#BahanDigital").val();
  let sisi = $("#sisi").val();
  let Qty = $("#Qty").val();
  let Qty_AlatTambahan = $("#Qty_AlatTambahan").val();
  let id_tambahan = $("#id_tambahan").val();
  let Jammed = $("#Jammed").val();
  let warna_cetakan = $("#warna_cetakan").val();
  let Error = $("#Error").val();
  let Kesalahan = $("#Kesalahan").val();
  let alasan_error = $("#alasan_error").val();
  let status_Cetak = $("#status_Cetak").val();
  let jumlah_click;

  if ($("#jumlah_click").prop("checked") == true) {
    jumlah_click = "Y";
  } else {
    jumlah_click = "N";
  }

  let fdata = new FormData();
  fdata.append("id_order", id_order);
  fdata.append("tanggal_ptg", tanggal_ptg);
  fdata.append("id_BahanDigital", id_BahanDigital);
  fdata.append("BahanDigital", BahanDigital);
  fdata.append("sisi", sisi);
  fdata.append("Qty", Qty);
  fdata.append("Qty_AlatTambahan", Qty_AlatTambahan);
  fdata.append("id_tambahan", id_tambahan);
  fdata.append("Jammed", Jammed);
  fdata.append("warna_cetakan", warna_cetakan);
  fdata.append("Error", Error);
  fdata.append("Kesalahan", Kesalahan);
  fdata.append("alasan_error", alasan_error);
  fdata.append("status_Cetak", status_Cetak);
  fdata.append("jumlah_click", jumlah_click);
  fdata.append("jenis_submit", id);

  $.ajax({
    type: "POST",
    url: "progress/setter_penjualan_prog.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    beforeSend: function () {
      $("#submitBtn").attr("disabled", "disabled");
    },
    success: function (data) {
      // $("#Result").html(data);
      hideBox();
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#Result").html(XMLHttpRequest);
    },
  });
}

function submit_maintenance(id) {
  let tanggal_ptg = $("#tanggal_ptg").val();
  let id_BahanDigital = $("#id_BahanDigital").val();
  let BahanDigital = $("#BahanDigital").val();
  let sisi = $("#sisi").val();
  let Qty = $("#Qty").val();
  let warna_cetakan = $("#warna_cetakan").val();
  let jumlah_click;

  if ($("#jumlah_click").prop("checked") == true) {
    jumlah_click = "Y";
  } else {
    jumlah_click = "N";
  }

  let fdata = new FormData();
  fdata.append("tanggal_ptg", tanggal_ptg);
  fdata.append("id_BahanDigital", id_BahanDigital);
  fdata.append("BahanDigital", BahanDigital);
  fdata.append("sisi", sisi);
  fdata.append("Qty", Qty);
  fdata.append("warna_cetakan", warna_cetakan);
  fdata.append("jumlah_click", jumlah_click);
  fdata.append("jenis_submit", id);

  $.ajax({
    type: "POST",
    url: "progress/setter_penjualan_prog.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    beforeSend: function () {
      $("#submitBtn").attr("disabled", "disabled");
    },
    success: function (data) {
      // $("#Result").html(data);
      hidesubBox();
      onload();
      return false;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      $("#Result").html(XMLHttpRequest);
    },
  });
}
