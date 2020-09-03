function hideBox() {
  // var result = confirm("Apakah anda mau menutup form ini?");
  // if (result) {
  $("#isidetail").css({
    visibility: "hidden",
  });
  $("#blackout").css({
    visibility: "hidden",
  });
  $("#bagDetail").html();
  // }
}

function hidesubBox() {
  // var result = confirm("Apakah anda mau menutup form ini?");
  // if (result) {
  $("#isidetail_sub").css({
    visibility: "hidden",
  });
  $("#blackout_sub").css({
    visibility: "hidden",
  });
  $("#bagDetail_sub").html();
  // }
}

function showBox() {
  $("#isidetail").css({
    visibility: "visible",
  });
  $("#blackout").css({
    visibility: "visible",
  });
}

function showSubBox() {
  $("#isidetail_sub").css({
    visibility: "visible",
  });
  $("#blackout_sub").css({
    visibility: "visible",
  });
}

function LaodSubForm(id, nid, Akses_Edit) {
  if (id == "setter_penjualan") {
    var judul = "FORM SETTER PENJUALAN";
  } else if (id == "generator_WoList") {
    var judul = "Form Generator Code";
  } else if (id == "penjualan_yescom") {
    var judul = "Form Sales Order YESCOM";
  } else if (id == "maintenance_DP") {
    var judul = "Form Maintenance Digital Printing";
  } else {
    var judul = "404 Not Found";
  }

  $.ajax({
    type: "POST",
    data: {
      data: id,
      judul_form: judul,
      ID_Order: nid,
      AksesEdit: Akses_Edit,
    },
    url: "Form/" + id + "_f.php",

    success: function (data) {
      showSubBox();
      $("#bagDetail_sub").html(data);
      if (id == "generator_WoList") {
        onload();
      } else if (id == "maintenance_DP") {
        validasi("BahanDigital");
      }
    },
  });
}

function LaodForm(id, nid, Akses_Edit) {
  if (id == "setter_penjualan") {
    var judul = "Form Setter Penjualan";
  } else if (id == "setter_penjualan_cancel") {
    var judul = "Form Cancel";
  } else if (id == "log") {
    var judul = "Form Log";
  } else if (id == "pelunasan_invoice") {
    var judul = "Form Pelunasan Invoice";
  } else if (id == "pelunasan_Multi_invoice") {
    var judul = "Form Multi Payment";
  } else if (id == "database_client") {
    var judul = "Form Client";
  } else if (id == "database_user") {
    var judul = "Form User";
  } else if (id == "database_bahan") {
    var judul = "Form Bahan";
  } else if (id == "database_pricelist") {
    var judul = "Form Pricelist";
  } else if (id == "WO_List_yescom") {
    var judul = "Form YES Communication Work Order";
  } else if (id == "penjualan_yescom") {
    var judul = "Form Sales Order YESCOM";
  } else if (id == "LargeFormat") {
    var judul = "Form Order Kerja Large Format";
  } else if (id == "database_supplier") {
    var judul = "Form Database Supplier";
  } else if (id == "StockBahan_LF") {
    var judul = "Form Stock Large Format";
  } else if (id == "DigitalPrinting") {
    var judul = "Form Pemotongan Stock Digital Printing";
  } else if (id == "maintenance_DP") {
    var judul = "Form Maintenance Digital Printing";
  } else if (id == "DigitalPrinting_Update") {
    var judul = "Form Update Pemotongan Stock Digital Printing";
  } else {
    var judul = "404 Not Found";
  }

  $.ajax({
    type: "POST",
    data: {
      data: id,
      judul_form: judul,
      ID_Order: nid,
      AksesEdit: Akses_Edit,
    },
    url: "Form/" + id + "_f.php",

    success: function (data) {
      showBox();
      $("#bagDetail").html(data);

      if (id == "setter_penjualan") {
        AksesEdit();
        ChangeKodeBrg();
        upload_design();
        if ($("#client").val() != "") {
          validasi("client");
        }
        if ($("#panjang").val() != "" || $("#lebar").val() != "") {
          calc_meter();
        }
        if ($("#bahan").val() != "") {
          validasi("bahan");
        }
      } else if (id == "setter_penjualan_cancel") {
        $("#alasan_cancel").focus();
      } else if (
        id == "setter_penjualan_invoice" ||
        id == "pelunasan_Multi_invoice" ||
        id == "penjualan_invoice_yescom"
      ) {
        if (
          id == "setter_penjualan_invoice" ||
          id == "penjualan_invoice_yescom"
        ) {
          outstandinglist(id);
        } else {
          outstandinglist();
        }
      } else if (id == "database_pricelist") {
        ChangeKodeBrg();
      } else if (id == "penjualan_yescom" || id == "WO_List_yescom") {
        ChangeKodeBrg();
        Check_KertasSendiri();
        if (id == "penjualan_yescom") {
          validasi("bahan");
          AksesEdit();
        }
      } else if (id == "DigitalPrinting_Update") {
        validasi("BahanDigital");
      }
    },
  });
}