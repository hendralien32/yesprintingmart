<?php
session_start();
require_once "../../function.php";

$jenis_stock = ($_POST['jenis_stock'] != "") ? $_POST['jenis_stock'] : "KRTS";
$dari_bulan = ($_POST['dari_bulan'] != "") ? $_POST['dari_bulan'] : $monts;
$ke_bulan = ($_POST['ke_bulan'] != "") ? $_POST['ke_bulan'] : $_POST['dari_bulan'];

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="25%">Nama</th>
            <th width="10%">Kode</th>
            <th width="8%">Jenis</th>
            <th width="11%">Min Stock</th>
            <th width="11%">Stock Lama</th>
            <th width="11%">Stock Masuk</th>
            <th width="11%">Stock Keluar</th>
            <th width="11%">Sisa Stock</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql =
            "SELECT
                barang.id_barang,
                barang.nama_barang,
                (CASE
                    WHEN barang.jenis_barang = 'KRTS' THEN 'Kertas'
                    WHEN barang.jenis_barang = 'TNT' THEN 'Tinta & Tonner'
                    WHEN barang.jenis_barang = 'SPRT' THEN 'Sparepart'
                    WHEN barang.jenis_barang = 'LF' THEN 'Bahan Large Format'
                    ELSE '- - -'
                END) as jenis_barang,
                barang.kode_barang,
                barang.min_stock,
                barang.satuan,
                Brg_Masuk.barang_masuk,
                Brg_Keluar.barang_keluar,
                digital.Qty as digital_qty,
                digital_KodeBrg.Qty as digitalKodeBrg_qty,
                OLD_digital.Qty as OLD_digital_qty,
                OLD_digital_KodeBrg.Qty as OLD_digitalKodeBrg_qty,
                OLD_Brg_Masuk.barang_masuk as OLD_barang_masuk,
                OLD_Brg_Keluar.barang_keluar as OLD_barang_keluar
            FROM
                barang
            LEFT JOIN 
                (SELECT
                    flow_barang.ID_Bahan,
                    SUM(flow_barang.barang_masuk) as barang_masuk
                FROM
                    flow_barang
                WHERE
                    left(flow_barang.tanggal, 7)>='$dari_bulan' AND 
                    left(flow_barang.tanggal, 7)<='$ke_bulan' AND
                    flow_barang.hapus != 'Y'
                GROUP BY 
                    flow_barang.ID_Bahan
                ) as Brg_Masuk
            ON
                Brg_Masuk.ID_Bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    flow_barang.ID_Bahan,
                    SUM(flow_barang.barang_keluar) as barang_keluar
                FROM
                    flow_barang
                WHERE
                    left(flow_barang.tanggal, 7)>='$dari_bulan' AND 
                    left(flow_barang.tanggal, 7)<='$ke_bulan' AND
                    flow_barang.hapus != 'Y'
                GROUP BY 
                    flow_barang.ID_Bahan
                ) as Brg_Keluar
            ON
                Brg_Keluar.ID_Bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.id_bahan,
                    (SUM(digital_printing.jam) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END))) as Qty
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)>='$dari_bulan' AND 
                    left(digital_printing.tgl_cetak, 7)<='$ke_bulan'
                GROUP BY 
                    digital_printing.id_bahan
                ) digital
            ON
                digital.id_bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.kode_bahan,
                    (SUM(digital_printing.jam) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END))) as Qty
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)>='$dari_bulan' AND 
                    left(digital_printing.tgl_cetak, 7)<='$ke_bulan'
                GROUP BY 
                    digital_printing.kode_bahan
                ) digital_KodeBrg
            ON
                digital_KodeBrg.kode_bahan = barang.kode_barang

            LEFT JOIN 
                (SELECT
                    flow_barang.ID_Bahan,
                    SUM(flow_barang.barang_masuk) as barang_masuk
                FROM
                    flow_barang
                WHERE
                    left(flow_barang.tanggal, 7)<'$dari_bulan' AND
                    flow_barang.hapus != 'Y'
                GROUP BY 
                    flow_barang.ID_Bahan
                ) as OLD_Brg_Masuk
            ON
                OLD_Brg_Masuk.ID_Bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    flow_barang.ID_Bahan,
                    SUM(flow_barang.barang_keluar) as barang_keluar
                FROM
                    flow_barang
                WHERE
                    left(flow_barang.tanggal, 7)<'$dari_bulan' AND
                    flow_barang.hapus != 'Y'
                GROUP BY 
                    flow_barang.ID_Bahan
                ) as OLD_Brg_Keluar
            ON
                OLD_Brg_Keluar.ID_Bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.id_bahan,
                    (SUM(digital_printing.jam) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END))) as Qty
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)<'$dari_bulan'
                GROUP BY 
                    digital_printing.id_bahan
                ) OLD_digital
            ON
                OLD_digital.id_bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.kode_bahan,
                    (SUM(digital_printing.jam) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) +
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END))) as Qty
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)<'$dari_bulan'
                GROUP BY 
                    digital_printing.kode_bahan
                ) OLD_digital_KodeBrg
            ON
                OLD_digital_KodeBrg.kode_bahan = barang.kode_barang

            WHERE
                barang.jenis_barang = '$jenis_stock' and
                barang.status_bahan = 'a' and
                barang.id_barang != '69' and
                barang.id_barang != '8'
            order BY
				barang.nama_barang
			asc
        ";
        $no = 0;

        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $no++;
                $stock_lama = $d['OLD_barang_masuk'] - $d['OLD_digitalKodeBrg_qty'] - $d['OLD_digital_qty'] - $d['OLD_barang_keluar'];
                $stock_keluar = $d['barang_keluar'] + $d['digital_qty'] + $d['digitalKodeBrg_qty'];
                $sisa_stock = $d['barang_masuk'] + $stock_lama - $stock_keluar;

                if ($sisa_stock < $d['min_stock']) {
                    $alert = "background-color:#e74c3c; color:white;";
                } else {
                    $alert = "";
                }
                echo "
                    <tr style='$alert'>
                        <td>$no</td>
                        <td>$d[nama_barang]</td>
                        <td>$d[kode_barang]</td>
                        <td>$d[jenis_barang]</td>
                        <td class='a-right' style='padding-right:15px'><b>" . number_format($d['min_stock']) . "</b> $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'><b>" . number_format($stock_lama) . "</b> $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'><b>" . number_format($d['barang_masuk']) . "</b> $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'><b>" . number_format($stock_keluar) . "</b> $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'><b>" . number_format($sisa_stock) . "</b> $d[satuan]</td>
                    </tr>
                ";
            endwhile;
        else :
            echo "
                <tr>
                    <td colspan='8'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='fafa-empty-set'></i></b></center></td>
                </tr>
            ";
        endif;
        ?>
    </tbody>
</table>