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
            large_format.restan
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
        $status_submit = "Update_PemotonganLF";
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
        $status_submit = "Insert_PemotonganLF";
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
    $status_submit = "Insert_PemotonganLF";
    $nama_submit = "Buka Order";
endif;
?>

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
        </table>
    </div>
</div>
<br>
<div class='row'>
    <table class='form_table'>
        <tr>
            <th width="1%">#</th>
            <th width="10%">ID Order</th>
            <th width="46%">Client - Deskripsi</th>
            <th width="10%">Bahan</th>
            <th width="13%">Ukuran</th>
            <?php
            if ($_POST['status'] == "Edit_PemotonganStockLF") :
            ?>
                <th width="8%">Qty</th>
                <th width="10%" onclick='copy_all()' class='pointer'>Qty Cetak <i class="fas fa-copy"></i></th>
                <th width="2%"></th>
            <?php
            else :
            ?>
                <th width="10%">Qty</th>
                <th width="10%" onclick='copy_all()' class='pointer'>Qty Cetak <i class="fas fa-copy"></i></th>
            <?php
            endif;
            ?>
        </tr>
        <?php
        if ($_POST['status'] == "Edit_PemotonganStockLF") :
            $sql =
                "SELECT
                    GROUP_CONCAT(large_format.lid) as lid,
                    GROUP_CONCAT(large_format.oid) as oid,
                    GROUP_CONCAT(penjualan.id_yes) as id_yes,
                    GROUP_CONCAT(penjualan.client) as client,
                    GROUP_CONCAT(penjualan.description) as description,
                    GROUP_CONCAT(penjualan.bahan) as bahan,
                    GROUP_CONCAT(penjualan.ukuran) as ukuran,
                    GROUP_CONCAT(penjualan.Qty_Order) as Qty_Order,
                    GROUP_CONCAT(large_format.qty_cetak) as qty_cetak,
                    GROUP_CONCAT(penjualan.test) as test,
                    large_format.restan
                FROM
                    large_format
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
                            penjualan.status,
                            IFNULL(jumlah_cetak.test,0) as test
                        FROM
                            penjualan
                        LEFT JOIN
                            (
                                SELECT
                                    large_format.oid,
                                    sum(large_format.qty_cetak) as test
                                FROM
                                    large_format
                                WHERE
                                    large_format.SO_Kerja != '$_POST[SO_Kerja]' and
                                    ( large_format.cancel = '' or large_format.cancel = 'N' )
                                GROUP BY
                                    large_format.oid
                            ) jumlah_cetak
                        ON
                            jumlah_cetak.oid = penjualan.oid
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
                        -- LEFT JOIN 
                        --     (
                        --         SELECT 
                        --             large_format.oid,
                        --             sum(large_format.qty_cetak) as qty_cetak
                        --         FROM 
                        --             large_format
                        --         WHERE
                        --             large_format.SO_Kerja != '$_POST[SO_Kerja]' and
                        --             ( large_format.cancel = '' or large_format.cancel = 'N' )
                        --     ) qty_sisa
                        -- ON
                        --     qty_sisa.oid = penjualan.oid
                    ) penjualan
                ON
                    large_format.oid = penjualan.oid
                WHERE
                    large_format.SO_Kerja = '$_POST[SO_Kerja]' and
                    ( large_format.cancel = '' or large_format.cancel = 'N' )
                GROUP BY
                    large_format.SO_Kerja
            ";
            $result = $conn_OOP->query($sql);
            if ($result->num_rows > 0) :
                $d = $result->fetch_assoc();

                $lid = explode(",", "$d[lid]");
                $oid = explode(",", "$d[oid]");
                $id_yes = explode(",", "$d[id_yes]");
                $client = explode(",", "$d[client]");
                $description = explode(",", "$d[description]");
                $bahan = explode(",", "$d[bahan]");
                $ukuran = explode(",", "$d[ukuran]");
                $Qty_Order = explode(",", "$d[Qty_Order]");
                $qty_cetak = explode(",", "$d[qty_cetak]");
                $test = explode(",", "$d[test]");

                $count_lid = count($lid);

                for ($i = 0; $i < $count_lid; $i++) :
                    $n = $i + 1;

                    if ($id_yes[$i] != '0') {
                        $Detail_IdYes = "$id_yes[$i] - ";
                    } else {
                        $Detail_IdYes = "";
                    }

                    $sisa_cetak = ($Qty_Order[$i] - $test[$i]);

                    echo "
                        <tr>
                            <td>$n</td>
                            <td class='a-center'>$oid[$i] <input type='hidden' name='oid[]' value='$oid[$i]'></td>
                            <td><strong>$Detail_IdYes $client[$i]</strong> - $description[$i]</td>
                            <td>$bahan[$i]</td>
                            <td><center>$ukuran[$i]</center></td>
                            <td onclick='copy_sisa($sisa_cetak,$n)'><strong>$Qty_Order[$i] <i style='color:red'>( - $sisa_cetak )</i></strong> Pcs</td>
                            <td name='Jmlh_Data'>
                                <center>
                                <input id='oid_NamaBahan_$n' type='hidden' name='oid_NamaBahan[]' value='$bahan[$i]'>
                                <input id='OldQty_$n' type='hidden' name='qty_old[]' value='$qty_cetak[$i]'>
                                <input id='CopyQty_$n' type='hidden' name='qty_sisa[]' value='$sisa_cetak'>
                                <input type='number' class='form sd' id='qty_$n' name='qty[]' min='0' max='$sisa_cetak' value='$qty_cetak[$i]'></center>
                            </td>
                            <td><span class='icon_status' onclick='hapus_lf(\"$sisa_cetak\",\"$qty_cetak[$i]\",\"$lid[$i]\",\"$oid[$i]\")'><i class='far fa-trash-alt text-danger'></i></span></td>
                        </tr>
                    ";
                endfor;
            endif;
        else :
            $string = strlen($_POST['idy']);
            if ($string > 0) {
                $list_yes = "$_POST[idy]";

                $reid = explode(",", "$list_yes");
                foreach ($reid as $yes) {
                    if ($yes != "") {
                        $y[] = "$yes";
                    }
                }
                $aid = implode("','", $y);
            } else {
                $aid = '';
            }

            $sql =
                "SELECT
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
                    CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                    ((penjualan.panjang * penjualan.lebar * penjualan.qty) / 10000) as total,
                    large_format.Invoice_Check,
                    penjualan.urgent,
                    (CASE
                        WHEN penjualan.laminate !='' THEN 'Y'
                        ELSE 'N'
                    END) as laminating,
                    (CASE
                        WHEN penjualan.alat_tambahan !='' THEN 'Y'
                        ELSE 'N'
                    END) as alat_tambahan,
                    ((penjualan.panjang * penjualan.lebar * large_format.total_cetak) / 10000) as total_cetak,
                    IFNULL(penjualan.qty,0) as Qty_Order,
                    IFNULL(large_format.total_cetak,0) as Qty_Ctk,
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
                LEFT JOIN 
                    (
                        SELECT 
                            large_format.oid, 
                            sum(large_format.qty_cetak) as total_cetak,
                            (CASE
                                WHEN large_format.so_kerja != '' THEN 'Y'
                                ELSE 'N'
                            END) as Invoice_Check
                        FROM 
                            large_format
                        WHERE
                            large_format.cancel != 'Y'
                        GROUP BY
                            large_format.oid
                    ) large_format
                ON
                    penjualan.oid = large_format.oid
                WHERE
                    penjualan.oid in ('$aid') and
                    IFNULL(penjualan.qty,0) != IFNULL(large_format.total_cetak,0) and
                    status != 'selesai'
            ";

            $result = $conn_OOP->query($sql);

            $n = 0;

            if ($result->num_rows > 0) :
                while ($d = $result->fetch_assoc()) :
                    $n++;
                    $sisa_cetak = $d['Qty_Order'] - $d['Qty_Ctk'];
                    if ($d['id_yes'] != '0') {
                        $id_yes = "$d[id_yes] - ";
                    } else {
                        $id_yes = "";
                    }
                    echo "
                        <tr>
                            <td>$n</td>
                            <td class='a-center'>$d[oid] <input type='hidden' name='oid[]' value='$d[oid]'></td>
                            <td><strong>$id_yes $d[client]</strong> - $d[description]</td>
                            <td>$d[bahan]</td>
                            <td class='a-center'>$d[ukuran]</td>
                            <td class='pointer a-center' onclick='copy_sisa($sisa_cetak,$n)'><strong>$d[Qty_Order] <i style='color:red'>( - $sisa_cetak )</i></strong> Pcs</td>
                            <td name='Jmlh_Data' class='a-center'>
                                <input id='oid_NamaBahan_$n' type='hidden' name='oid_NamaBahan[]' value='$d[bahan]'>
                                <input id='CopyQty_$n' type='hidden' name='qty_sisa[]' value='$sisa_cetak'>
                                <input type='number' class='form sd' id='qty_$n' name='qty[]' min='0' max='$sisa_cetak'>
                            </td>
                        </tr>
                    ";
                endwhile;
            else :
                echo "<tr><td colspan='7'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td></tr>";
            endif;
        endif;

        ?>
    </table>
</div>
<div id="submit_menu">
    <button onclick="submit('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
</div>
<div id="Result">

</div>