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
