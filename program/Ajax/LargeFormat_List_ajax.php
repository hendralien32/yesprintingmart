<?php
session_start();
require_once '../../function.php';
$test = "";
$bahan_Sort = isset($_POST['session_bahan']) ? $_POST['session_bahan'] : ' ';

if (isset($bahan_Sort)) :
    $test .= $bahan_Sort;
else :
    $test .= "$_SESSION[ListOrder_BahanLF]";
endif;

$_SESSION['ListOrder_BahanLF'] = "$test";

if ($_SESSION['ListOrder_BahanLF'] != '') :
    $Add_Bahan = "and bahan='$_SESSION[ListOrder_BahanLF]'";
else :
    $Add_Bahan = "";
endif;

?>
<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>

<table>
    <tbody>
        <tr>
            <th width="2%" class="contact100-form-checkbox" style='padding-top:13px;'>
                <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='toggle(this)'>
                <label class="label-checkbox100" for="Check_box"></label>
            </th>
            <th width="6%">ID Order</th>
            <th width="3%">K</th>
            <th width="42%">Client - Description</th>
            <th width="12%">
                <select name="BahanSearch" id="BahanSearch" onchange="BahanSearch();">
                    <option value="">Bahan</option>
                    <?php
                    $sql =
                        "SELECT
                            bahan,
                            count(bahan) as qty
                        FROM
                            (
                                SELECT
                                    penjualan.oid,
                                    (CASE
                                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                                        ELSE penjualan.bahan
                                    END) as bahan
                                FROM
                                    penjualan
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
                                WHERE
                                    penjualan.kode = 'large format' and
                                    penjualan.inv_check = 'Y' and
                                    penjualan.status != 'selesai' and
                                    penjualan.cancel != 'Y'
                            ) penjualan  
                        GROUP BY
                            bahan
                    ";

                    // Perform query
                    $result = $conn_OOP->query($sql);

                    if ($result->num_rows > 0) :
                        // output data of each row
                        while ($d = $result->fetch_assoc()) :
                            if ($d['bahan'] == "$test") {
                                $pilih = "selected";
                            } else {
                                $pilih = "";
                            }
                            echo "<option value='$d[bahan]' $pilih>$d[bahan] ($d[qty])</option>";
                        endwhile;
                    else :

                    endif;
                    ?>
                </select>
            </th>
            <th width="8%">Ukuran File</th>
            <th width="7%">Icons</th>
            <th width="7%">Qty</th>
            <th width="8%">Total (M<sup>2</sup>)</th>
            <th>Status</th>
        </tr>
        <?php
        $sql =
            "SELECT
                penjualan.oid,
                LEFT(penjualan.kode, 1) as code,
                penjualan.kode as kode_barang,
                (CASE
                    WHEN penjualan.id_yes != '' THEN penjualan.id_yes
                    ELSE ''
                END) AS id_yes,
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
                ((penjualan.panjang * penjualan.lebar * large_format.total_cetak) / 10000) as total_cetak,
                IFNULL(penjualan.qty,0) as Qty_Order,
                IFNULL(large_format.total_cetak,0) as Qty_Ctk
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
                ( penjualan.kode = 'large format' or penjualan.kode='indoor' or penjualan.kode='Xuli' ) and
                penjualan.inv_check = 'Y' and
                penjualan.status != 'selesai' and
                penjualan.cancel != 'Y'
                $Add_Bahan
        ";

        $n = 0;
        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;

                $array_kode = array("Invoice_Check", "urgent", "laminating");
                foreach ($array_kode as $kode) :
                    if ($d[$kode] != "" && $d[$kode] != "N") : ${'check_' . $kode} = "active";
                    else : ${'check_' . $kode} = "deactive";
                    endif;
                endforeach;

                $kode_class = str_replace(" ", "_", $d['kode_barang']);
                $sisa_cetak = $d['total'] - $d['total_cetak'];

                if ($d['id_yes'] != '') {
                    $id_yes = "$d[id_yes] - ";
                } else {
                    $id_yes = "";
                }

                echo "
                    <tr>
                        <td class='contact100-form-checkbox' style='padding-top:16px;'>
                            <input class='input-checkbox100' id='cek_$n' type='checkbox' name='option' value='$d[oid]'>
                            <label class='label-checkbox100' for='cek_$n'></label>
                        </td>
                        <td><center>$d[oid]</center></td>
                        <td><span class='KodeProject " . $kode_class . "'>" . strtoupper($d['code']) . "</span></td>
                        <td><strong>$id_yes $d[client]</strong> - $d[description]</td>
                        <td>$d[bahan]</td>
                        <td><center>$d[ukuran]</center></td>
                        <td>
                            <center>
                                <span class='icon_status'><i class='fas fa-exclamation-triangle " . $check_urgent . "'></i></span>
                                <span class='icon_status'><i class='fas fa-receipt " . $check_Invoice_Check . "'></i></span>
                                <span class='icon_status'><i class='fas fa-toilet-paper-alt " . $check_laminating . "'></i></span>
                            </center>
                        </td>
                        <td>$d[qty]</td>
                        <td><center><strong>" . number_format($d['total'], 2) . " <i style='color:red'>( " . number_format($sisa_cetak, 2) . " )</i></strong> M<sup>2</sup></center></td>
                        <td><center>$d[Qty_Ctk] / $d[Qty_Order]</center></td>
                    </tr>
                ";

                $total_SisaCtk[]   = $sisa_cetak;
                $Nilai_total_SisaCtk = number_format(array_sum($total_SisaCtk), 2);
            endwhile;
        else :
            $Nilai_Total = 0;
            echo "
                <tr>
                    <td colspan='10'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                </tr>
            ";
        endif;
        ?>
        <tr>
            <th colspan="8">Total Order Large Format <?= $hr . ", " . date("d M Y", strtotime($date)); ?></th>
            <th style='text-align:right'>
                <center><?= $Nilai_total_SisaCtk; ?> M<sup>2</sup></center>
            </th>
        </tr>
    </tbody>
</table>