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

function onload() {
  $("#loader").show();
  var search = $("#search").val();
  var Tanggal = $("#tanggal").val();
  var session_bahan = $("#session_bahan").val();

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
      session_bahan: session_bahan,
    },
    url: "Ajax/LargeFormat_List_ajax.php",
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
  $("#session_bahan").val(Bahan_Sort);
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

function tombol_selesai(id) {
  if (confirm("No. order " + id + " Sudah Selesai dicetak ?")) {
    $.ajax({
      type: "POST",
      url: "progress/setter_penjualan_prog.php",
      cache: false,
      data: {
        oid: id,
        jenis_submit: "LF_Selesai",
      },
      beforeSend: function () {
        $("#tombol_selesai").attr("disabled", "disabled");
      },
      success: function (data) {
        alert("No. order " + id + " sudah selesai dicetak");
        onload();
        $("#tombol_selesai").removeAttr("disabled");
        return false;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $("#bagDetail").html(XMLHttpRequest);
      },
    });
  }
}

function LaodFormLF(id) {
  var judul = "Form Order Kerja Large Format";

  var j = $("#total_check").val();
  var x = new Array();
  var y = new Array();

  for (var i = 1; i <= j; i++) {
    if ($("#cek_" + i).prop("checked") && $("#cek_" + i).val() != "") {
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
    data: {
      data: id,
      judul_form: judul,
      idy: si_yes,
      idx: si_no
    },
    url: "Form/" + id + "_f.php",

    success: function (data) {
      showBox();
      $("#bagDetail").html(data);
    }
  });
}

function copy_sisa(qty, no) {
  $("#qty_" + no).val(qty);
}

function copy_all() {
  checkboxes = $('[name="Jmlh_Data"]');
  for (let i = 1; i <= checkboxes.length; i++) {
    abc = $('#CopyQty_' + [i]).val();
    $('#qty_' + [i]).val(abc);
  }
}