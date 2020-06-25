$(document).ready(function () {
    onload();
});

function onload() {
    $('#loader').show();

    $.ajax({
        type: "POST",
        data: {

        },
        url: "Ajax/StockBahan_LF_ajax.php",
        cache: false,
        success: function (data) {
            $('#loader').hide();
            $("#list_StockBahan_LF").html(data);
            return false;
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

function validasi(id, no) {
    var ID_Data = $("#" + id + no).val();

    $.ajax({
        type: "POST",
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
}

function test(id, no) {
    $("#NamaBahan" + no).autocomplete({
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
                                size: item.ukuran,
                            };
                        })
                    );
                },
            });
        },
        minLength: 2,
        autoFocus: true,
        select: function (event, ui) {
            $("#NamaBahan" + no).val(ui.item.value);
            $("#id_NamaBahan" + no).val(ui.item.id);
            $("#form_Panjang" + no).val(ui.item.size);
        },
        change: function (event, ui) {
            $("#NamaBahan" + no).val(ui.item.value);
            $("#id_NamaBahan" + no).val(ui.item.id);
            $("#form_Panjang" + no).val(ui.item.size);
        },
    });

    validasi(id, no);
}

function submit(id) {
    var supplier = $("#nama_supplier").val();
    var Tgl_Order = $("#tanggal_order").val();
    var ID_bahanSubLF = [];
    $("input[name='nama_bahan[]']").each(function () {
        ID_bahanSubLF.push($(this).val());
    });

    var panjang = [];
    $("input[name='panjang[]']").each(function () {
        panjang.push($(this).val());
    });

    var lebar = [];
    $("input[name='lebar[]']").each(function () {
        lebar.push($(this).val());
    });

    var qty = [];
    $("input[name='qty[]']").each(function () {
        if ($(this).val() == "" || $(this).val() <= 0) {
            alert("Qty Tidak Boleh Kosong & Tidak Boleh Kurang dari 0");
            return false;
        }
        qty.push($(this).val());
    });

    var Harga = [];
    $("input[name='Harga[]']").each(function () {
        Harga.push($(this).val());
    });

    var jumlah_array = $('[name="nama_bahan[]"]').length;

    $("input[name='validasi_bahan[]']").each(function () {
        if ($(this).val() != "1") {
            alert("Nama Bahan Bermasalah");
            return false;
        }
    });

    var fdata = new FormData()
    fdata.append("supplier", supplier);
    fdata.append("Tgl_Order", Tgl_Order);
    fdata.append("ID_bahanSubLF", ID_bahanSubLF);
    fdata.append("panjang", panjang);
    fdata.append("lebar", lebar);
    fdata.append("qty", qty);
    fdata.append("Harga", Harga);
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
            // $('#submitBtn').attr("disabled", "disabled");
            // $(".icon-close").removeAttr('onclick');
        },
        success: function (data) {
            $("#Result").html(data);
            // hideBox();
            // onload();
            // return false;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    });

}