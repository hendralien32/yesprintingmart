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
  var type_mesin = $("#type_mesin").val();

  $.ajax({
    type: "POST",
    data: {
      data: search,
      date: Tanggal,
      session_bahan: session_bahan,
      type_mesin: type_mesin,
    },
    url: "Ajax/LargeFormat_List_ajax.php",
    cache: false,
    success: function (data) {
      if (type_mesin != "") {
        $("#button_order").css("display", "");
        $("#button_rusak").css("display", "");
      } else {
        $("#button_order").css("display", "none");
        $("#button_rusak").css("display", "none");
      }
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

function session_mesin() {
  var type_mesin = $("#type_mesin").val();
  $("#session_mesin").val(type_mesin);
  if (type_mesin != "") {
    $("#button_order").css("display", "");
    $("#button_rusak").css("display", "");
  } else {
    $("#button_order").css("display", "none");
    $("#button_rusak").css("display", "none");
  }
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
      idx: si_no,
      status: "",
    },
    url: "Form/" + id + "_f.php",

    success: function (data) {
      showBox();
      $("#bagDetail").html(data);
      restan();
    },
  });
}

function copy_sisa(qty, no) {
  $("#qty_" + no).val(qty);
}

function copy_all() {
  checkboxes = $('[name="Jmlh_Data"]');
  for (let i = 1; i <= checkboxes.length; i++) {
    abc = $("#CopyQty_" + [i]).val();
    $("#qty_" + [i]).val(abc);
  }
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

function validasi_NoBahan(id) {
  var id_NamaBahan = $("#id_NamaBahan").val();
  var ID_Data = $("#" + id).val();

  $.ajax({
    type: "POST",
    data: {
      tipe_validasi: "Search_" + id,
      term: ID_Data,
      id_NamaBahan: id_NamaBahan,
    },
    url: "progress/validasi_progress.php",
    success: function (data) {
      if (data > 0) {
        $("#id_" + id).val(ID_Data);
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

function test(id) {
  $("#NamaBahan").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_stockLF",
          term: request.term,
        },
        dataType: "json",
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.nama_barang + "." + item.ukuran,
                value: item.nama_bahan,
                id: item.ID_BarangLF,
                ukuran: item.ukuran,
              };
            })
          );
        },
      });
    },
    minLength: 2,
    autoFocus: true,
    select: function (event, ui) {
      $("#NamaBahan").val(ui.item.value);
      $("#id_NamaBahan").val(ui.item.id);
      $("#panjang_potong").val(ui.item.ukuran);
    },
    change: function (event, ui) {
      $("#NamaBahan").val(ui.item.value);
      $("#id_NamaBahan").val(ui.item.id);
      $("#panjang_potong").val(ui.item.ukuran);
    },
  });

  validasi(id);
}

function nomor_bahanSearch(id) {
  var id_NamaBahan = $("#id_NamaBahan").val();

  $("#nomor_bahan").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_NoBahan",
          id_NamaBahan: id_NamaBahan,
          term: request.term,
        },
        dataType: "json",
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.no_bahan,
                value: item.no_bahan,
                id: item.no_bahan,
              };
            })
          );
        },
      });
    },
    minLength: 1,
    autoFocus: true,
    select: function (event, ui) {
      $("#nomor_bahan").val(ui.item.value);
      $("#id_nomor_bahan").val(ui.item.id);
    },
    change: function (event, ui) {
      $("#nomor_bahan").val(ui.item.value);
      $("#id_nomor_bahan").val(ui.item.id);
    },
  });

  validasi_NoBahan(id);
}

function restan() {
  var Akses = ["NamaBahan", "nomor_bahan"];

  if ($("#restan").is(":checked")) {
    for (i = 0; i < Akses.length; i++) {
      $("#" + Akses[i]).prop("disabled", true);
      $("#" + Akses[i]).val("");
      $("#validasi_" + Akses[i] + "").val(1);
      $("#Alert_Val" + Akses[i] + "").html(
        "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>"
      );
    }
  } else {
    for (i = 0; i < Akses.length; i++) {
      $("#" + Akses[i]).prop("disabled", false);
      $("#" + Akses[i]).val("");
      $("#validasi_" + Akses[i] + "").val(0);
      $("#Alert_Val" + Akses[i] + "").html("");
    }
  }
}

function submit(id) {
  if ($("#validasi_NamaBahan").val() < 1) {
    alert("Nama Bahan tidak ada dalam Daftar Stock Barang");
    return false;
  } else if ($("#validasi_nomor_bahan").val() < 1) {
    alert("Nomor Bahan tidak ada dalam Daftar Stock Barang");
    return false;
  } else if ($("#lebar_potong").val() == "" || $("#lebar_potong").val() < 0) {
    alert("Lebar Potong Tidak boleh Kosong / Kurang dari 0");
    return false;
  }

  let id_NamaBahan = $("#id_NamaBahan").val();
  let id_nomor_bahan = $("#id_nomor_bahan").val();
  let panjang_potong = $("#panjang_potong").val();
  let lebar_potong = $("#lebar_potong").val();
  let qty_jalan = $("#qty_jalan").val();
  let restan;
  if ($("#restan").prop("checked") == true) {
    restan = "Y";
  } else {
    restan = "N";
  }
  let jumlah_pass = $("#jumlah_pass").val();

  let oid = [];
  $("input[name='oid[]']").each(function () {
    oid.push($(this).val());
  });

  let NamaBahan = [];
  $("input[name='oid_NamaBahan[]']").each(function () {
    NamaBahan.push($(this).val());
  });

  let qty_sisa = [];
  $("input[name='qty_sisa[]']").each(function () {
    qty_sisa.push($(this).val());
  });

  let qty = [];
  $("input[name='qty[]']").each(function () {
    if ($(this).val() == "" || $(this).val() <= 0) {
      check_qty = false;
      alert("Qty Tidak Boleh Kosong & Tidak Boleh Kurang dari 0");
      return false;
    }
    check_qty = true;
    qty.push($(this).val());
  });

  if (!check_qty) {
    return false;
  }

  let jumlah_array = $('[name="oid[]"]').length;

  var fdata = new FormData();
  fdata.append("id_NamaBahan", id_NamaBahan);
  fdata.append("id_nomor_bahan", id_nomor_bahan);
  fdata.append("panjang_potong", panjang_potong);
  fdata.append("lebar_potong", lebar_potong);
  fdata.append("qty_jalan", qty_jalan);
  fdata.append("jumlah_pass", jumlah_pass);
  fdata.append("oid", oid);
  fdata.append("qty_sisa", qty_sisa);
  fdata.append("qty", qty);
  fdata.append("jumlah_array", jumlah_array);
  fdata.append("restan", restan);
  fdata.append("NamaBahan", NamaBahan);
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
      $("#bagDetail").html(XMLHttpRequest);
    },
  });
}

function find_ID(id, no) {
  $("#OID" + no).autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "progress/validasi_progress.php",
        type: "POST",
        data: {
          tipe_validasi: "autocomplete_findOID",
          term: request.term,
        },
        dataType: "json",
        success: function (data) {
          // alert(data);
          response(
            $.map(data, function (item) {
              return {
                label: item.oid + " - " + item.client + "" + item.description,
                value: item.oid,
                client: item.client,
                desc: item.description,
                bahan: item.bahan,
                size: item.ukuran,
              };
            })
          );
        },
      });
    },
    minLength: 3,
    autoFocus: true,
    select: function (event, ui) {
      $("#OID" + no).val(ui.item.value);
      $("#id_OID" + no).val(ui.item.value);
      $("#client" + no).html(ui.item.client);
      $("#description" + no).html(ui.item.desc);
      $("#bahan" + no).html(ui.item.bahan);
      $("#oid_NamaBahan" + no).html(ui.item.bahan);
      $("#ukuran" + no).html(ui.item.size);
    },
    change: function (event, ui) {
      $("#OID" + no).val(ui.item.value);
      $("#id_OID" + no).val(ui.item.value);
      $("#client" + no).html(ui.item.client);
      $("#description" + no).html(ui.item.desc);
      $("#bahan" + no).html(ui.item.bahan);
      $("#oid_NamaBahan" + no).val(ui.item.bahan);
      $("#ukuran" + no).html(ui.item.size);
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
          $("#client" + no).html("");
          $("#description" + no).html("");
          $("#bahan" + no).html("");
          $("#oid_NamaBahan" + no).val("");
          $("#ukuran" + no).html("");
          $("#Alert_Val" + id + no).html(
            "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i> "
          );
        }
      },
    });
  } else {
    $("#validasi_" + id + no).val("0");
    $("#id_" + id + no).val("");
    $("#client" + no).html("");
    $("#description" + no).html("");
    $("#bahan" + no).html("");
    $("#oid_NamaBahan" + no).val("");
    $("#ukuran" + no).html("");
    $("#Alert_Val" + id + no).html(
      "<i class='fas fa-times-octagon' style='color:red; margin-left:10px;'></i> "
    );
  }
}

function submit_Rusak(id) {
  if ($("#validasi_NamaBahan").val() < 1) {
    alert("Nama Bahan tidak ada dalam Daftar Stock Barang");
    return false;
  } else if ($("#validasi_nomor_bahan").val() < 1) {
    alert("Nomor Bahan tidak ada dalam Daftar Stock Barang");
    return false;
  } else if ($("#lebar_potong").val() == "" || $("#lebar_potong").val() < 0) {
    alert("Lebar Potong Tidak boleh Kosong / Kurang dari 0");
    return false;
  }

  let id_NamaBahan = $("#id_NamaBahan").val();
  let id_nomor_bahan = $("#id_nomor_bahan").val();
  let panjang_potong = $("#panjang_potong").val();
  let lebar_potong = $("#lebar_potong").val();
  let qty_jalan = $("#qty_jalan").val();
  let restan;
  if ($("#restan").prop("checked") == true) {
    restan = "Y";
  } else {
    restan = "N";
  }
  let jumlah_pass = $("#jumlah_pass").val();
  let keterangan_rusak = $("#keterangan_rusak").val();
  let kesalahan_siapa = $("#kesalahan_siapa").val();
  let jumlah_array = $('input[name="OID[]"]').length;
  let OID = [];
  $("input[name='OID[]']").each(function () {
    OID.push($(this).val());
  });

  let NamaBahan = [];
  $("input[name='oid_NamaBahan[]']").each(function () {
    NamaBahan.push($(this).val());
  });

  let qty = [];
  $("input[name='qty[]']").each(function () {
    if ($(this).val() == "" || $(this).val() <= 0) {
      check_qty = false;
      alert("Qty Tidak Boleh Kosong & Tidak Boleh Kurang dari 0");
      return false;
    }
    check_qty = true;
    qty.push($(this).val());
  });

  $("input[name='validasi_OID[]']").each(function () {
    if ($(this).val() != "1") {
      check_validasi_OID = false;
      alert("No ID Bermasalah");
      return false;
    }
    check_validasi_OID = true;
  });

  if (!check_qty) {
    return false;
  }

  if (!check_validasi_OID) {
    return false;
  }

  let fdata = new FormData();
  fdata.append("id_NamaBahan", id_NamaBahan);
  fdata.append("id_nomor_bahan", id_nomor_bahan);
  fdata.append("panjang_potong", panjang_potong);
  fdata.append("lebar_potong", lebar_potong);
  fdata.append("qty_jalan", qty_jalan);
  fdata.append("restan", restan);
  fdata.append("jumlah_pass", jumlah_pass);
  fdata.append("keterangan_rusak", keterangan_rusak);
  fdata.append("kesalahan_siapa", kesalahan_siapa);
  fdata.append("jumlah_array", jumlah_array);
  fdata.append("NamaBahan", NamaBahan);
  fdata.append("oid", OID);
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