<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

if ($_POST['status'] == "Edit_PemotonganStockLF") :
    $sql_PemotonganStock =
        "SELECT
            large_format.panjang_potong,
            large_format.lebar_potong,
            large_format.qty_jalan,
            large_format.pass,
            flow_bahanlf.nama_bahan,
            flow_bahanlf.no_bahan,
            flow_bahanlf.bid,
            flow_bahanlf.id_bahanLF,
            large_format.restan,
            large_format.kesalahan,
            large_format.keterangan
        FROM
            large_format
        LEFT JOIN
            (
                SELECT
                    flow_bahanlf.bid,
                    flow_bahanlf.id_bahanLF,
                    flow_bahanlf.no_bahan,
                    concat(barang_sub_lf.nama_barang,'.',barang_sub_lf.ukuran) as nama_bahan
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
                            barang_sub_lf.ID_barang = barang.ID_barang
                    ) barang_sub_lf
                ON
                    flow_bahanlf.id_bahanLF = barang_sub_lf.ID_BarangLF
            ) flow_bahanlf
        ON 
            large_format.id_BrngFlow = flow_bahanlf.bid
        WHERE
            large_format.SO_Kerja = '$_POST[SO_Kerja]'
        GROUP BY
            large_format.SO_Kerja
    ";
    $result = $conn_OOP->query($sql_PemotonganStock);
    if ($result->num_rows > 0) :
        $d = $result->fetch_assoc();

        $so_kerja = "$_POST[SO_Kerja]";
        $restan = "$d[restan]";
        $nama_bahan = "$d[nama_bahan]";
        $bid = "$d[bid]";
        $id_bahanLF = "$d[id_bahanLF]";
        $no_bahan = "$d[no_bahan]";
        $panjang_potong = "$d[panjang_potong]";
        $lebar_potong = "$d[lebar_potong]";
        $qty_jalan = "$d[qty_jalan]";
        $pass = "$d[pass]";
        $kesalahan = "$d[kesalahan]";
        $keterangan = "$d[keterangan]";
        $status_submit = "Update_PemotonganLF_Rusak";
        $nama_submit = "Update Order";
    else :
        $so_kerja = "";
        $restan = "";
        $nama_bahan = "";
        $bid = "";
        $id_bahanLF = "";
        $no_bahan = "";
        $panjang_potong = "";
        $lebar_potong = "";
        $qty_jalan = "1";
        $pass = "3";
        $kesalahan = "";
        $keterangan = "";
        $status_submit = "Insert_PemotonganLF_Rusak";
        $nama_submit = "Buka Order";
    endif;
else :
    $so_kerja = "";
    $restan = "";
    $nama_bahan = "";
    $bid = "";
    $id_bahanLF = "";
    $no_bahan = "";
    $panjang_potong = "";
    $lebar_potong = "";
    $qty_jalan = "1";
    $pass = "3";
    $kesalahan = "";
    $keterangan = "";
    $status_submit = "Insert_PemotonganLF_Rusak";
    $nama_submit = "Buka Order";
endif;
?>

<script>
    $(document).ready(function() {
        var i = 1;

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr  id="row' + i + '"><td><input type="text" class="form sd" id="OID' + i + '" autocomplete="off" onkeyup="find_ID(\'OID\',\'' + i + '\')" onChange="validasi_ID(\'OID\',\'' + i + '\')"><input type="hidden" name="OID[]" id="id_OID' + i + '" class="form sd" readonly disabled><input type="hidden" name="validasi_OID[]" id="validasi_OID' + i + '" class="form sd" readonly disabled><span id="Alert_ValOID' + i + '"></span></td><td><span id="client' + i + '" style="font-weight:bold"></span> <span id="description' + i + '"></span></td><td><span id="bahan' + i + '"></span></td><td class="a-center"><span id="ukuran' + i + '"></span></td><td class="a-center"><input type="number" class="form sd" id="qty_$n" name="qty[]" min="0" max="$sisa_cetak"></td><td class="btn_remove" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></td></tr>'
            );

        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <input type='hidden' id='NO_SOKerja' value="<?= $so_kerja ?>">
            <tr>
                <td style='width:145px'>Kode Bahan</td>
                <td>
                    <input type="text" class="form md" style="width:145px" id="NamaBahan" autocomplete="off" onkeyup="test('NamaBahan')" onChange="validasi('NamaBahan')" value='<?= $nama_bahan ?>'>
                    <input type="hidden" name="nama_bahan" id="id_NamaBahan" value='<?= $id_bahanLF ?>' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_NamaBahan" class="form sd" readonly disabled>
                    <span id="Alert_ValNamaBahan"></span>
                    -
                    <input type="text" class="form sd" id="nomor_bahan" autocomplete="off" onkeyup="nomor_bahanSearch('nomor_bahan')" onkeyup="validasi_NoBahan('nomor_bahan')" value='<?= $no_bahan ?>'>
                    <input type="hidden" name="nama_bahan" value='<?= $bid ?>' id="id_nomor_bahan" class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_nomor_bahan" class="form sd" readonly disabled>
                    <span id="Alert_Valnomor_bahan"></span>

                    <?php
                    if ($restan == "Y") {
                        $checked = "checked";
                    } else {
                        $checked = "";
                    }
                    ?>
                    <div class="contact100-form-checkbox" style='float:right; margin-top:4px; margin-left:11px'>
                        <input class="input-checkbox100" id="restan" type="checkbox" name="remember" <?= $checked; ?> onclick="restan();">
                        <label class="label-checkbox100" for="restan"> Restan</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Ukuran Potong</td>
                <td><input class='form sd' type="number" id='panjang_potong' value="<?= $panjang_potong; ?>"> x <input class='form sd' type="number" id='lebar_potong' value="<?= $lebar_potong; ?>"></td>
            </tr>
            <tr>
                <td style='width:145px'>Keterangan Rusak</td>
                <td><textarea id='keterangan_rusak' class='form ld' style="height:50px;"><?= $keterangan; ?></textarea></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Qty Jalan</td>
                <td><input class='form sd' value='<?= $qty_jalan; ?>' type="number" id='qty_jalan'></td>
            </tr>
            <tr>
                <td style='width:145px'>Pass</td>
                <td><input class='form sd' value='<?= $pass; ?>' type="number" id='jumlah_pass'></td>
            </tr>
            <tr>
                <td style='width:145px'>Kesalahan</td>
                <td><input class='form md' value='<?= $kesalahan; ?>' type="text" id='kesalahan_siapa'></td>
            </tr>
        </table>
    </div>
</div>
<br>
<div class='row'>
    <div class="col-sm">
        <table class='form_table'>
            <thead>
                <tr>
                    <th width="10%">ID Order</th>
                    <th width="52%">Client - Deskripsi</th>
                    <th width="12%">Bahan</th>
                    <th width="13%">Ukuran</th>
                    <th width="10%">Qty Cetak</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <tr>
                    <td>
                        <input type="text" class="form sd" id="OID1" autocomplete="off" onkeyup="find_ID('OID','1')" onChange="validasi_ID('OID','1')" onkeyup="validasi_ID('OID','1')">
                        <input type="hidden" name="OID[]" id="id_OID1" class="form sd" readonly disabled>
                        <input type="hidden" name="validasi_OID[]" id="validasi_OID1" class="form sd" readonly disabled>
                        <span id="Alert_ValOID1"></span>
                    </td>
                    <td><span id="client1" style="font-weight:bold"></span> <span id="description1"></span></td>
                    <td><span id="bahan1"></span></td>
                    <td class="a-center"><span id="ukuran1"></span></td>
                    <td class="a-center">
                        <input id='oid_NamaBahan1' type='hidden' name='oid_NamaBahan[]' value=''>
                        <input type="number" class="form sd" id="qty_$n" name="qty[]" min="0">
                    </td>
                    <td id="add" class="pointer">
                        <i class="fad fa-plus-square" name="add"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="submit_menu">
        <button onClick="submit_Rusak('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
    </div>
    <div id="Result">


    </div>
</div>
                            
                            