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