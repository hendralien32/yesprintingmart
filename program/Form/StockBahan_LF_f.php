<?php
session_start();
require_once "../../function.php";

if (isset($_POST['ID_Order'])) {
    $status_submit = "update('Update_StockFlowLF')";
    $nama_submit = "Update Stock";
    $sql =
        "SELECT
        flow_bahanlf.kode_pemesanan,
        flow_bahanlf.id_supplier,
        flow_bahanlf.tanggal_order,
        GROUP_CONCAT(concat(barang_sub_lf.nama_barang,'.',barang_sub_lf.ukuran,'.',flow_bahanlf.no_bahan)) as Nama_Bahan,
        GROUP_CONCAT(flow_bahanlf.panjang) AS panjang,
        GROUP_CONCAT(flow_bahanlf.lebar) AS lebar,
        GROUP_CONCAT(flow_bahanlf.harga) AS harga,
        GROUP_CONCAT(flow_bahanlf.bid) AS bid,
        GROUP_CONCAT(
            (CASE 
                WHEN flow_bahanlf.diterima = 'Y' THEN 'active'
                ELSE ''
            END)
        ) AS css_diterima,
        flow_bahanlf.diterima
    FROM
        flow_bahanlf
    LEFT JOIN
        (
            SELECT
                barang_sub_lf.ID_BarangLF, 
                barang_sub_lf.ID_barang,
                barang_sub_lf.ukuran,
                barang.nama_barang
            FROM
                barang_sub_lf
            LEFT JOIN
                (
                    SELECT
                        barang.id_barang, 
                        barang.nama_barang
                    FROM
                        barang
                ) barang
            ON
                barang.ID_barang = barang_sub_lf.ID_barang
        ) barang_sub_lf
    ON
        barang_sub_lf.ID_BarangLF = flow_bahanlf.id_bahanLF
    WHERE
        flow_bahanlf.kode_pemesanan = '$_POST[ID_Order]' and
        flow_bahanlf.hapus = 'N'
    GROUP BY
        flow_bahanlf.kode_pemesanan
    ";
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        $d = $result->fetch_assoc();
    endif;
} else {
    $status_submit = "submit('Insert_StockFlowLF')";
    $nama_submit = "Order Stock";
}

if (isset($d)) {
    $diterima = $d['diterima'];
    $kode_pemesanan = $d['kode_pemesanan'];
    $id_supplier = $d['id_supplier'];
    $tanggal_order = $d['tanggal_order'];
    $Nama_Bahan = explode(",", "$d[Nama_Bahan]");
    $panjang = explode(",", "$d[panjang]");
    $lebar = explode(",", "$d[lebar]");
    $harga = explode(",", "$d[harga]");
    $bid = explode(",", "$d[bid]");
    $count_NamaBahan = count($Nama_Bahan);
    $css_diterima = explode(",", "$d[css_diterima]");
    $disabled_tglOrdr = "disabled";
} else {
    $diterima = "";
    $kode_pemesanan = "";
    $id_supplier = "";
    $tanggal_order = "$date";
    $Nama_Bahan = "";
    $count_NamaBahan = 0;
    $panjang = "";
    $lebar = "";
    $harga = 0;
    $bid = "";
    $css_diterima = "";
    $disabled_tglOrdr = "";
}


if ($_SESSION["level"] == "admin") {
    $display = "number";
    $status_disabled = "";
    $style_css = "";
} else {
    $display = "hidden";
    $status_disabled = "disabled";
    $style_css = "display:none;";
}

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<script>
    $(document).ready(function() {
        var i = 1;

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr id="row' + i + '"><td><input type="text" class="form md" id="NamaBahan' + i + '" autocomplete="off" onkeyup="test(\'NamaBahan\',\'' + i + '\')" onChange="validasi(\'NamaBahan\',\'' + i + '\')"><input type="hidden" id="id_NamaBahan' + i + '" class="form sd" name="nama_bahan[]" readonly disabled><input type="hidden" id="validasi_NamaBahan' + i + '" class="form sd" name="validasi_bahan[]" readonly disabled><span id="Alert_ValNamaBahan' + i + '"></span></td><td><center><input class="form sd" type="number" name="panjang[]" id="form_Panjang' + i + '" disabled> x <input class="form sd" type="number" name="lebar[]" id="form_Lebar" autocomplete="off"></center></td><td><center><input class="form sd" type="text" name="qty[]" id="form_Qty" autocomplete="off"> Roll</center></td><td><center><input class="form md" type="number" name="Harga[]" id="Harga" autocomplete="off"></center></td><td class="btn_remove" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></tr>'
            );

        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

<div class="row">
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td style="width:120px">Nama Supplier</td>
                <td>
                    <select id='nama_supplier'>
                        <option value=''>Pilih Supplier</option>
                        <?php
                        $sql =
                            "SELECT
                                supplier.id_supplier,
                                supplier.nama_supplier
                            FROM
                                supplier
                            WHERE
                                supplier.hapus = 'N'
                            ORDER BY
                                supplier.nama_supplier 
                        ";

                        $result = $conn_OOP->query($sql);
                        if ($result->num_rows > 0) :
                            while ($row = $result->fetch_assoc()) :
                                if ($id_supplier == "$row[id_supplier]") {
                                    $pilih = "selected";
                                } else {
                                    $pilih = "";
                                }
                                echo "<option value='$row[id_supplier]' $pilih>$row[nama_supplier]</option>";
                            endwhile;
                        endif;
                        ?>
                    </select>

                </td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td>Tanggal Order</td>
                <td><input type="date" data-placeholder="Tgl. Order" id="tanggal_order" class='form md' value="<?= $tanggal_order; ?>" <?= $disabled_tglOrdr ?>></td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm">
        <table class='form_table'>
            <thead>
                <tr>
                    <th width="30%">Nama Bahan</th>
                    <th width="30%">Ukuran</th>
                    <th width="30%">Qty</th>
                    <th width="30%" style="<?= $style_css; ?>">Harga /Meter</th>
                    <th width="2%">Act</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <?php
                if ($count_NamaBahan > 0) :
                    for ($i = 0; $i < $count_NamaBahan; $i++) {
                        echo "
                            <tr>
                                <input type='hidden' name='bid[]' value='$bid[$i]'>
                                <td><input type='text' class='form md' id='NamaBahan' value='$Nama_Bahan[$i]' disabled></td>
                                <td><center><input class='form sd' type='number' value='$panjang[$i]' name='panjang[]' disabled> x <input class='form sd' value='$lebar[$i]' type='number' name='lebar[]' $status_disabled autocomplete='off'></center></td>
                                <td><center>1 Roll</center></td>
                                <td style='$style_css'><center><input class='form md' type='$display' name='Harga[]' value='$harga[$i]' id='Harga' autocomplete='off' $status_disabled></center></td>
                                <td>
                                    <span class='icon_status'><i class='fad fa-hand-holding-box $css_diterima[$i]'></i></span>
                                </td>
                            </tr>
                        ";
                    }
                endif;
                if (!isset($_POST['ID_Order'])) :
                ?>
                    <tr>
                        <td>
                            <input type="text" class="form md" id="NamaBahan1" autocomplete="off" onkeyup="test('NamaBahan','1')" onChange="validasi('NamaBahan','1')">
                            <input type="hidden" name="nama_bahan[]" id="id_NamaBahan1" class="form sd" readonly disabled>
                            <input type="hidden" name="validasi_bahan[]" id="validasi_NamaBahan1" class="form sd" readonly disabled>
                            <span id="Alert_ValNamaBahan1"></span>
                        </td>
                        <td>
                            <center>
                                <input class="form sd" type="number" name="panjang[]" id="form_Panjang1" disabled> x <input class="form sd" type="number" name="lebar[]" id="form_Lebar" autocomplete="off">
                            </center>
                        </td>
                        <td>
                            <center>
                                <input class="form sd" type="text" name="qty[]" id="form_Qty" autocomplete="off"> Roll
                            </center>
                        </td>
                        <td>
                            <center>
                                <input class="form md" type="number" name="Harga[]" id="Harga" autocomplete="off">
                            </center>
                        </td>
                        <td id="add" class='pointer'>
                            <i class="fad fa-plus-square" name="add"></i>
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>
    <div id="submit_menu">
        <?php if ($_SESSION["level"] == "admin") : ?>
            <button onclick="<?= $status_submit; ?>" id="submitBtn"><?= $nama_submit; ?></button>
        <?php
        endif;
        if (isset($_POST['ID_Order']) && $diterima == "N") :
        ?>
            <button onclick="terima_Barang('<?= $kode_pemesanan ?>')" id="submitBtn">Terima Barang</button>
        <?php
        endif;
        ?>
    </div>
    <div id="Result">


    </div>
</div>