<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

$string = strlen($_POST['idy']);
?>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Kode Bahan</td>
                <td>
                    <input type="text" class="form md" id="NamaBahan" autocomplete="off" onkeyup="test('NamaBahan')" onChange="validasi('NamaBahan')">
                    <input type="hidden" name="nama_bahan" id="id_NamaBahan" class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_NamaBahan" class="form sd" readonly disabled>
                    <span id="Alert_ValNamaBahan"></span>
                    -
                    <input type="text" class="form sd" id="nomor_bahan" autocomplete="off" onkeyup="nomor_bahanSearch('nomor_bahan')" onkeyup="validasi_NoBahan('nomor_bahan')">
                    <input type="hidden" name="nama_bahan" id="id_nomor_bahan" class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_nomor_bahan" class="form sd" readonly disabled>
                    <span id="Alert_Valnomor_bahan"></span>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Ukuran Potong</td>
                <td><input class='form sd' type="number" id='panjang_potong'> x <input class='form sd' type="number" id='lebar_potong'></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Qty Jalan</td>
                <td><input class='form sd' value='1' type="number" id='qty_jalan'></td>
            </tr>
            <tr>
                <td style='width:145px'>Pass</td>
                <td><input class='form sd' value='3' type="number" id='jumlah_pass'></td>
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
            <th width="10%">Qty</th>
            <th width="10%" onclick='copy_all()' class='pointer'>Qty Cetak <i class="fas fa-copy"></i></th>
        </tr>
        <?php
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
                        <td><center>$d[oid] <input type='hidden' name='oid[]' value='$d[oid]'></center></td>
                        <td><strong>$id_yes $d[client]</strong> - $d[description]</td>
                        <td>$d[bahan]</td>
                        <td><center>$d[ukuran]</center></td>
                        <td class='pointer' onclick='copy_sisa($sisa_cetak,$n)'><center><strong>$d[Qty_Order] <i style='color:red'>( - $sisa_cetak )</i></strong> Pcs </center></td>
                        <td name='Jmlh_Data'>
                            <input id='CopyQty_$n' type='hidden' name='qty_sisa[]' value='$sisa_cetak'>
                            <center><input type='number' class='form sd' id='qty_$n' name='qty[]' min='0' max='$sisa_cetak'></center>
                        </td>
                    </tr>
                ";
            endwhile;
        else :
            echo "<tr><td colspan='7'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td></tr>";
        endif;

        ?>
    </table>
</div>
<div id="submit_menu">
    <button onclick="submit('Insert_PemotonganLF')" id="submitBtn">Buka Order</button>
</div>
<div id="Result">

</div>