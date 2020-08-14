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
            large_format.keterangan,
            GROUP_CONCAT(penjualan.id_yes) as id_yes,
            GROUP_CONCAT(penjualan.client) as client,
            GROUP_CONCAT(penjualan.description) as description,
            GROUP_CONCAT(penjualan.bahan) as bahan,
            GROUP_CONCAT(penjualan.ukuran) as ukuran,
            GROUP_CONCAT(large_format.lid) as lid,
            GROUP_CONCAT(large_format.oid) as oid,
            GROUP_CONCAT(large_format.qty_cetak) as qty_cetak
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
        LEFT JOIN
            (
                SELECT
                    penjualan.oid,
                    penjualan.id_yes,
                    (CASE
                        WHEN penjualan.client_yes != '' THEN penjualan.client_yes
                        ELSE customer.nama_client 
                    END) AS client,
                    penjualan.description,
                    (CASE
                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                        ELSE penjualan.bahan
                    END) as bahan,
                    (CASE
                        WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                        WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                        ELSE ''
                    END) as ukuran,
                    IFNULL(penjualan.qty,0) as Qty_Order,
                    penjualan.status
                FROM
                    penjualan
                LEFT JOIN 
                    (
                        SELECT 
                            customer.cid, 
                            customer.nama_client 
                        FROM 
                            customer
                    ) customer
                ON
                    penjualan.client = customer.cid  
                LEFT JOIN 
                    (
                        SELECT 
                            barang.id_barang, 
                            barang.nama_barang 
                        FROM 
                            barang
                    ) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang
            ) penjualan
        ON
            large_format.oid = penjualan.oid
        WHERE
            large_format.SO_Kerja = '$_POST[SO_Kerja]' and
            ( large_format.cancel = '' or large_format.cancel = 'N' )
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
        $id_yes = explode(",", "$d[id_yes]");
        $client = explode(",", "$d[client]");
        $description = explode(",", "$d[description]");
        $bahan = explode(",", "$d[bahan]");
        $ukuran = explode(",", "$d[ukuran]");
        $lid = explode(",", "$d[lid]");
        $oid = explode(",", "$d[oid]");
        $qty_cetak = explode(",", "$d[qty_cetak]");
        $count_lid = count($lid);
        $next_count = $count_lid + 1;
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
        $id_yes = "";
        $client = "";
        $description = "";
        $bahan = "";
        $ukuran = "";
        $lid = "";
        $oid = "";
        $qty_cetak = "";
        $count_lid = "0";
        $next_count = 1;
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
    $id_yes = "";
    $client = "";
    $description = "";
    $bahan = "";
    $ukuran = "";
    $lid = "";
    $oid = "";
    $qty_cetak = "";
    $count_lid = "0";
    $next_count = 1;
    $status_submit = "Insert_PemotonganLF_Rusak";
    $nama_submit = "Buka Order";
endif;
?>

<script>
    $(document).ready(function() {
        var i = <?= $next_count ?>;

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr  id="row' + i + '"><td name="Jmlh_Data"><input type="text" class="form sd" id="OID' + i + '" autocomplete="off" onkeyup="find_ID(\'OID\',\'' + i + '\')" onChange="validasi_ID(\'OID\',\'' + i + '\')"><input type="hidden" name="OID[]" id="id_OID' + i + '" class="form sd" readonly disabled><input type="hidden" name="validasi_OID[]" id="validasi_OID' + i + '" class="form sd" readonly disabled><span id="Alert_ValOID' + i + '"></span></td><td><span id="client' + i + '" style="font-weight:bold"></span> <span id="description' + i + '"></span></td><td><span id="bahan' + i + '"></span></td><td class="a-center"><span id="ukuran' + i + '"></span></td><td class="a-center"><input type="number" class="form sd" id="qty_$n" name="qty[]" min="0" max="$sisa_cetak"></td><td class="btn_remove" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></td></tr>'
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
                    <th width="13%">ID Order</th>
                    <th width="49%">Client - Deskripsi</th>
                    <th width="12%">Bahan</th>
                    <th width="13%">Ukuran</th>
                    <th width="10%">Qty Cetak</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <?php
<<<<<<< HEAD
                if(isset($count_lid)) {
=======
                if (isset($count_lid)) {
>>>>>>> ec784bc972a2b490412ddeaa0ab18b9236705303
                    for ($i = 0; $i < $count_lid; $i++) :
                        $n = $i + 1;
                        if ($id_yes[$i] != '0') {
                            $Detail_IdYes = "$id_yes[$i] - ";
                        } else {
                            $Detail_IdYes = "";
                        }

                        echo "
                            <tr>
                                <td name='Jmlh_Data'>
                                <input type='text' class='form sd' id='OID$n' autocomplete='off' onkeyup='find_ID(\"OID\",\"$n\")' onChange='validasi_ID(\"OID\",\"$n\")' onkeyup='validasi_ID(\"OID\",\"$n\")' value='$oid[$i]'>
                                <input type='hidden' name='OID[]' value='$oid[$i]' id='id_OID$n' class='form sd' readonly disabled>
                                <input type='hidden' name='validasi_OID[]' id='validasi_OID$n' value='1' class='form sd' readonly disabled>
                                <span id='Alert_ValOID$n'></span>
                                </td>
                                <td><strong>$Detail_IdYes $client[$i]</strong> - $description[$i]</td>
                                <td>$bahan[$i]</td>
                                <td class='a-center'>$ukuran[$i]</td>
                                <td class='a-center'>
                                    <input id='oid_NamaBahan$n' type='hidden' name='oid_NamaBahan[]' value='$bahan[$i]'>
                                    <input type='number' class='form sd' id='qty_$n' name='qty[]' value='$qty_cetak[$i]'>
                                </td>
<<<<<<< HEAD
                                <td><span class='icon_status' onclick=''><i class='far fa-trash-alt text-danger'></i></span></td>
=======
                                <td><span class='icon_status' onclick='hapus_sub_ID(\"Hapus_rusakSUB_ID\", \"$lid[$i]\", \"$oid[$i]\", \"$so_kerja\")'><i class='far fa-trash-alt text-danger'></i></span></td>
>>>>>>> ec784bc972a2b490412ddeaa0ab18b9236705303
                            </tr>
                        ";
                    endfor;
                }
                ?>
                <tr>
                    <td name='Jmlh_Data'>
                        <input type="text" class="form sd" id="OID<?= $next_count ?>" autocomplete="off" onkeyup="find_ID('OID','<?= $next_count ?>')" onChange="validasi_ID('OID','<?= $next_count ?>')" onkeyup="validasi_ID('OID','<?= $next_count ?>')">
                        <input type="hidden" name="OID[]" id="id_OID<?= $next_count ?>" class="form sd" readonly disabled>
                        <input type="hidden" name="validasi_OID[]" id="validasi_OID<?= $next_count ?>" class="form sd" readonly disabled>
                        <span id="Alert_ValOID<?= $next_count ?>"></span>
                    </td>
                    <td><span id="client<?= $next_count ?>" style="font-weight:bold"></span> <span id="description<?= $next_count ?>"></span></td>
                    <td><span id="bahan<?= $next_count ?>"></span></td>
                    <td class="a-center"><span id="ukuran<?= $next_count ?>"></span></td>
                    <td class="a-center">
                        <input id='oid_NamaBahan<?= $next_count ?>' type='hidden' name='oid_NamaBahan[]' value=''>
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