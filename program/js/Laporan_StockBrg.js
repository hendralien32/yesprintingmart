$(document).ready(function () {
  $("#dari_bulan").keyup(function (e) {
    if (e.keyCode == 13) {
      // Enter keycode
      $("#loader").show();
      onload();
      return false;
    }
  });

  $("#ke_bulan").keyup(function (e) {
    if (e.keyCode == 13) {
      // Enter keycode
      $("#loader").show();
      onload();
      return false;
    }
  });

  onload();
});

function onload() {
  $("#loader").show();
  var dari_bulan = $("#dari_bulan").val();
  var ke_bulan = $("#ke_bulan").val();
  var type_stock = $("#type_stock").val();

  var fdata = new FormData();
  fdata.append("dari_bulan", dari_bulan);
  fdata.append("ke_bulan", ke_bulan);
  fdata.append("jenis_stock", type_stock);

  $.ajax({
    type: "POST",
    url: "Ajax/Laporan_StockBrg_ajax.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    success: function (data) {
      $("#loader").hide();
      $("#list_LapStockBrg").html(data);
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}

function SearchFrom() {
  let dari_tanggal = $("#dari_bulan").val();
  let All_Element = ["ke_bulan"];

  for (i = 0; i < All_Element.length; i++) {
    $("#" + All_Element[i] + "").prop("disabled", false);
    $("#" + All_Element[i] + "").prop("readonly", false);
  }

  $("#ke_bulan").attr("min", dari_tanggal);
  $("#loader").show();
  onload();
}

function SearchTo() {
  $("#loader").show();
  onload();
}

function jenis_stock() {
  $("#loader").show();
  onload();
}

function submit_adj(id) {

  let ID_Brg = [];
  $("input[name='ID_Brg[]']").each(function () {
    ID_Brg.push(parseInt($(this).val()));
  });

  let QtyAkhir = [];
  $("input[name='QtyAkhir[]']").each(function () {
    QtyAkhir.push($(this).val());
  });

  let qty = [];
  $("input[name='qty[]']").each(function () {
    qty.push($(this).val());
  });

  let fdata = new FormData();
  fdata.append("ID_Brg", ID_Brg);
  fdata.append("QtyAkhir", QtyAkhir);
  fdata.append("qty", qty);
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
      $(".icon-close").removeAttr("onclick");
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