<?php
session_start();
require_once "../../function.php";

$ID_Order = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

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
            left(flow_barang.tanggal, 7)>='$months' AND 
            left(flow_barang.tanggal, 7)<='$months' AND
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
            left(flow_barang.tanggal, 7)>='$months' AND 
            left(flow_barang.tanggal, 7)<='$months' AND
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
            left(digital_printing.tgl_cetak, 7)>='$months' AND 
            left(digital_printing.tgl_cetak, 7)<='$months'
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
            left(digital_printing.tgl_cetak, 7)>='$months' AND 
            left(digital_printing.tgl_cetak, 7)<='$months'
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
            left(flow_barang.tanggal, 7)<'$months' AND
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
            left(flow_barang.tanggal, 7)<'$months' AND
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
            left(digital_printing.tgl_cetak, 7)<'$months'
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
            left(digital_printing.tgl_cetak, 7)<'$months'
        GROUP BY 
            digital_printing.kode_bahan
        ) OLD_digital_KodeBrg
    ON
        OLD_digital_KodeBrg.kode_bahan = barang.kode_barang

    WHERE
        barang.jenis_barang = 'KRTS' and
        barang.status_bahan != 'n' and
        barang.id_barang != '69' and
        barang.id_barang != '8'

    order BY
        barang.nama_barang
    asc
";
$no = 0;
$result = $conn_OOP->query($sql);

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<div class='row'>
    <div class="col-sm">
        <table class='form_table'>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="32%">Nama Bahan</th>
                    <th width="22%">Kode Bahan</th>
                    <th width="20%">Qty Akhir</th>
                    <th width="20%">Qty</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <?php
                if ($result->num_rows > 0) :
                    while ($d = $result->fetch_assoc()) :
                        $no++;
                        $stock_lama = $d['OLD_barang_masuk'] - $d['OLD_digitalKodeBrg_qty'] - $d['OLD_digital_qty'] - $d['OLD_barang_keluar'];
                        $stock_keluar = $d['barang_keluar'] + $d['digital_qty'] + $d['digitalKodeBrg_qty'];
                        $sisa_stock = $d['barang_masuk'] + $stock_lama - $stock_keluar;

                        echo "
                            <tr>
                                <input type='hidden' class='form sd' value='$d[id_barang]' id='ID_Brg_$no' name='ID_Brg[]' min='0'>
                                <td>$no</td>
                                <td>$d[nama_barang]</td>
                                <td>$d[kode_barang]</td>
                                <td class='a-right' style='padding-right:15px'><input type='hidden' class='form sd' value='$sisa_stock' id='QtyAkhir_$no' name='QtyAkhir[]' min='0'>" . number_format($sisa_stock) . " $d[satuan]</td>
                                <td class='a-center'><input type='number' class='form sd' value='0' id='qty_$no' name='qty[]' min='0'> $d[satuan]</td>
                            </tr>
                        ";
                    endwhile;
                else :

                endif;
                ?>
            </tbody>
        </table>
    </div>
    <div id="submit_menu">
        <button onClick="submit_adj('submit_AdjustStock')" id="submitBtn">Submit Adjusting Stock</button>
    </div>
    <div id="Result">

    </div>
</div>

<?php $conn->close(); ?>