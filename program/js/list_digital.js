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
      $("#loader").hide();
      $("#setter_penjualan").html(data);
      return false;
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
    },
  });
}
