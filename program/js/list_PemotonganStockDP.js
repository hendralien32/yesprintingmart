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

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
    },
    url: "Ajax/list_PemotonganStockDP_ajax.php",
    cache: false,
    success: function (data) {
      $("#loader").hide();
      $("#list_PemotonganStockDP").html(data);
      onload_Counter();
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}

function onload_Counter() {
  var search = $("#search").val();
  var Tanggal = $("#tanggal").val();

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
    },
    url: "Ajax/Counter_Mesin_Plugin.php",
    cache: false,
    success: function (data) {
      $("#Counter_mesin").html(data);
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}

function SearchDate() {
  $("#loader").show();
  onload();
  onload_Counter();
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
