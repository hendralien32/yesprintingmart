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
  var search = $("#search").val();
  var dari_bulan = $("#dari_bulan").val();
  var ke_bulan = $("#ke_bulan").val();

  var fdata = new FormData();
  fdata.append("search", search);
  fdata.append("date_from", dari_bulan);
  fdata.append("date_to", ke_bulan);

  $.ajax({
    type: "POST",
    url: "Ajax/list_pemesanan_kertas_ajax.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    success: function (data) {
      $("#loader").hide();
      $("#list_pemesanan_kertas").html(data);
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}

function SearchFrom() {
  $("#search").val("");

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

function search_data() {
  $("#loader").show();
  onload();
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
          "<i class='fas fa-times-octagon' style='color:red; margin-left:10px; margin-right:5px;'></i>"
        );
      } else {
        $("#validasi_" + id).val("0");
        $("#Alert_Val" + id).html(
          "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
        );
      }
    },
  });
}

function find_ID(id, no) {
  $("#BahanDigital" + no).autocomplete({
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
          // alert(data);
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
    minLength: 3,
    autoFocus: true,
    select: function (event, ui) {
      $("#BahanDigital" + no).val(ui.item.value);
      $("#id_BahanDigital" + no).val(ui.item.value);
    },
    change: function (event, ui) {
      $("#BahanDigital" + no).val(ui.item.value);
      $("#id_BahanDigital" + no).val(ui.item.value);
    },
  });

  validasi_ID(id, no);
}

function validasi_ID(id, no) {
  var ID_Data = $("#" + id + no).val();

  if (ID_Data.length > 3) {
    $.ajax({
      type: "POST",
      minLength: 3,
      data: {
        tipe_validasi: "Search_" + id,
        term: ID_Data,
      },
      url: "progress/validasi_progress.php",
      success: function (data) {
        if (data > 0) {
          $("#validasi_" + id + no).val(data);
          $("#Alert_Val" + id + no).html(
            "<i class='fad fa-check-double' style='margin-left:10px;'></i> "
          );
        } else {
          $("#validasi_" + id + no).val("0");
          $("#id_" + id + no).val("");
          $("#Alert_Val" + id + no).html(
            "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i> "
          );
        }
      },
    });
  } else {
    $("#validasi_" + id + no).val("0");
    $("#id_" + id + no).val("");
    $("#Alert_Val" + id + no).html(
      "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i> "
    );
  }
}