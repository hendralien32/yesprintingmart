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
    minLength: 2,
    autoFocus: true,
    select: function (event, ui) {
      $("#BahanDigital" + no).val(ui.item.value);
      $("#id_BahanDigital" + no).val(ui.item.id);
    },
    change: function (event, ui) {
      $("#BahanDigital" + no).val(ui.item.value);
      $("#id_BahanDigital" + no).val(ui.item.id);
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
            "<i class='fad fa-check-double' style='margin-left:10px;'></i>"
          );
        } else {
          $("#validasi_" + id + no).val("0");
          $("#id_" + id + no).val("");
          $("#Alert_Val" + id + no).html(
            "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i>"
          );
        }
      },
    });
  } else {
    $("#validasi_" + id + no).val("0");
    $("#id_" + id + no).val("");
    $("#Alert_Val" + id + no).html(
      "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i>"
    );
  }
}

function hapus(fid, NoDO, status_NoDO) {
  if (status_NoDO == "N") {
    var abc = "hapus";
  } else {
    var abc = "kembalikan";
  }

  if (confirm(abc + ' No DO "' + NoDO + '" ?')) {
    $.ajax({
      type: "POST",
      url: "progress/setter_penjualan_prog.php",
      data: {
        fid: fid,
        jenis_submit: "delete_NoDO",
        status_NoDO: status_NoDO,
      },
      success: function (data) {
        alert('No DO "' + NoDO + '" sudah di' + abc);
        onload();
        return false;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $("#bagDetail").html(XMLHttpRequest);
      },
    });
  }
}

function submit_stock(id) {
  // alert("masuk");
  if ($("#validasi_NoDO").val() >= 1) {
    alert("No DO Sudah ada");
    $("#Alert_ValNoDO").html(
      "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i>"
    );
    return false;
  } else if ($("#validasi_NoDO").val() == "") {
    alert("No DO Tidak Boleh Kosong");
    $("#Alert_ValNoDO").html(
      "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i>"
    );
    return false;
  }

  let NoDO = $("#NoDO").val();
  let jenis_stock = $("#jenis_stock").val();
  let Tanggal_Stock = $("#Tanggal_Stock").val();
  let jumlah_array = $('input[name="id_BahanDigital[]"]').length;

  let fid = [];
  $("input[name='fid[]']").each(function () {
    fid.push(parseInt($(this).val()));
  });

  let BahanDigital = [];
  $("input[name='id_BahanDigital[]']").each(function () {
    BahanDigital.push($(this).val());
  });

  let qty = [];
  $("input[name='qty[]']").each(function () {
    qty.push($(this).val());
  });

  let harga = [];
  $("input[name='harga[]']").each(function () {
    harga.push($(this).val());
  });

  $("input[name='validasi_BahanDigital[]']").each(function () {
    if ($(this).val() == "0") {
      check_validasi_BahanDigital = false;
      alert("Nama Bahan bermasalah");
      return false;
    } else if ($(this).val() == "" || $(this).val() == "1") {
      check_validasi_BahanDigital = true;
    }
  });

  if (!check_validasi_BahanDigital) {
    return false;
  }

  let fdata = new FormData();
  fdata.append("fid", fid);
  fdata.append("NoDO", NoDO);
  fdata.append("jenis_stock", jenis_stock);
  fdata.append("Tanggal_Stock", Tanggal_Stock);
  fdata.append("BahanDigital", BahanDigital);
  fdata.append("qty", qty);
  fdata.append("harga", harga);
  fdata.append("jumlah_array", jumlah_array);
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

function hapus_sub_ID(status, lid, oid, SO_LF) {
  if (confirm('Hapus Bahan "' + oid + '" ?')) {
    var fdata = new FormData();
    fdata.append("fid", lid);
    fdata.append("nama_bahan", oid);
    fdata.append("jenis_submit", status);
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
        onload();
        LaodForm("Tambah_StockDP", SO_LF);
        return false;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        $("#bagDetail").html(XMLHttpRequest);
      },
    });
  } else {
    return false;
  }
}
