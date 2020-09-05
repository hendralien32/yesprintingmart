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
