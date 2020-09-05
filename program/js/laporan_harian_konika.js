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

  var fdata = new FormData();
  fdata.append("date_from", dari_bulan);
  fdata.append("date_to", ke_bulan);

  $.ajax({
    type: "POST",
    url: "Ajax/list_laporan_konika_ajax.php",
    cache: false,
    processData: false,
    contentType: false,
    data: fdata,
    success: function (data) {
      $("#loader").hide();
      $("#list_laporan_konika").html(data);
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

function submit(id) {
  if ($("#validasi").val() == 1) {
    alert("Tidak bisa input Counter, karena Counter Sudah Di Buka");
    return false;
  }

  let billing_id = $("#billing_id").val();
  let tanggal_Counter = $("#tanggal_Counter").val();
  let Counter_Awal_FC = $("#Counter_Awal_FC").val();
  let Counter_Akhir_FC = $("#Counter_Akhir_FC").val();
  let Counter_Awal_BW = $("#Counter_Awal_BW").val();
  let Counter_Akhir_BW = $("#Counter_Akhir_BW").val();

  let fdata = new FormData();
  fdata.append("billing_id", billing_id);
  fdata.append("tanggal_Counter", tanggal_Counter);
  fdata.append("Counter_Awal_FC", Counter_Awal_FC);
  fdata.append("Counter_Akhir_FC", Counter_Akhir_FC);
  fdata.append("Counter_Awal_BW", Counter_Awal_BW);
  fdata.append("Counter_Akhir_BW", Counter_Akhir_BW);
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
