<?php
require_once "../../function.php";

?>

    <table>
        <?php
        $sql =
            "SELECT
                stock.nama_barang,
                stock.sisa_stock
            FROM
                (
                    SELECT
                        barang.nama_barang,
                        barang.min_stock,
                        ( IFNULL(Brg_Masuk.barang_masuk,0) - IFNULL(Brg_Keluar.barang_keluar,0) - IFNULL(digital.Qty,0) - IFNULL(digital_KodeBrg.Qty,0) ) AS sisa_stock
                    FROM
                        barang
                    LEFT JOIN 
                        (SELECT
                            flow_barang.ID_Bahan,
                            SUM(flow_barang.barang_masuk) as barang_masuk
                        FROM
                            flow_barang
                        WHERE
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
                        GROUP BY 
                            digital_printing.kode_bahan
                        ) digital_KodeBrg
                    ON
                        digital_KodeBrg.kode_bahan = barang.kode_barang
                    WHERE
                        barang.jenis_barang = 'KRTS' and
                        barang.status_bahan != 'n' and
                        barang.id_barang != '69' and
                        barang.id_barang != '8'
                ) stock
            WHERE
                stock.min_stock > stock.sisa_stock
        ";

        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                echo "
                    <tr>
                        <td>$d[nama_barang]</td>
                        <td style='text-align:right; padding-right:0.9em'><b>". number_format($d['sisa_stock']) ."</b> Lbr</td>
                    </tr>
                ";
            endwhile;
        endif;
        ?>
    </table>
</div>