<?php
session_start();
require_once "../../function.php";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="28%">Nama</th>
            <th width="10%">Kode</th>
            <th width="5%">Jenis</th>
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
                ( digital.jam + digital.qty_cetak + digital.error + digital_KodeBrg.jam + digital_KodeBrg.qty_cetak + digital_KodeBrg.error ) as digital_keluar
            FROM
                barang
            LEFT JOIN 
                (SELECT
                    flow_barang.ID_Bahan,
                    SUM(flow_barang.barang_masuk) as barang_masuk
                FROM
                    flow_barang
                WHERE
                    left(flow_barang.tanggal, 7)>='2020-09' AND 
                    left(flow_barang.tanggal, 7)<='2020-09' AND
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
                    left(flow_barang.tanggal, 7)>='2020-09' AND 
                    left(flow_barang.tanggal, 7)<='2020-09' AND
                    flow_barang.hapus != 'Y'
                GROUP BY 
                    flow_barang.ID_Bahan
                ) as Brg_Keluar
            ON
                Brg_Keluar.ID_Bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.id_bahan,
                    SUM(digital_printing.jam) as jam,
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) as qty_cetak,
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END)) as error
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)>='2020-09' AND 
                    left(digital_printing.tgl_cetak, 7)<='2020-09'
                GROUP BY 
                    digital_printing.id_bahan
                ) digital
            ON
                digital.id_bahan = barang.id_barang

            LEFT JOIN 
                (SELECT
                    digital_printing.kode_bahan,
                    SUM(digital_printing.jam) as jam,
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END)) as qty_cetak,
                    SUM((CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END)) as error
                FROM
                    digital_printing
                WHERE
                    left(digital_printing.tgl_cetak, 7)>='2020-09' AND 
                    left(digital_printing.tgl_cetak, 7)<='2020-09'
                GROUP BY 
                    digital_printing.kode_bahan
                ) digital_KodeBrg
            ON
                digital_KodeBrg.kode_bahan = barang.kode_barang

            WHERE
                barang.jenis_barang = 'KRTS'
            order BY
				barang.nama_barang
			asc
        ";
        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $no++;
                $stock_keluar = $d['barang_keluar'] + $d['digital_keluar'];
                echo "
                    <tr>
                        <td>$no</td>
                        <td>$d[nama_barang]</td>
                        <td>$d[kode_barang]</td>
                        <td>$d[jenis_barang]</td>
                        <td class='a-right' style='padding-right:15px'>" . number_format($d['min_stock']) . " $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'></td>
                        <td class='a-right' style='padding-right:15px'>" . number_format($d['barang_masuk']) . " $d[satuan]</td>
                        <td class='a-right' style='padding-right:15px'>" . number_format($stock_keluar) . " $d[satuan]</td>
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