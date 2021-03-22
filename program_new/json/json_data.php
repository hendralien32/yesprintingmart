<?php
require_once "../../function.php";

$jenisData = $_POST['jenisData'];

if($jenisData == "karyawanAbsensi") :
    $sql = 
    "SELECT
        pm_user.uid,
        pm_user.nama
    FROM
        pm_user
    WHERE
        pm_user.absensi = 'Y' and
        pm_user.tanggal_resign = '0000-00-00'
    ORDER BY
        pm_user.nama
    ";

    $result = $conn_OOP->query($sql);
    // $result = $conn_OOP->query($sql);
    if ($result->num_rows > 0) :
        while ($row = $result->fetch_assoc()) :
            $arr_data['uid'] = $row['uid'];
            $arr_data['nama'] = ucwords($row['nama']);

            $array[] = array_merge($arr_data);
        endwhile;
    endif;
elseif ($jenisData == "listKekuranganKertas") : // JSON list Kekurangan Stock Kertas Digital Printing
    $sql =
        "SELECT
            stock.nama_barang as namaBarang,
            stock.sisa_stock as sisaStock
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
        while ($row = $result->fetch_assoc()) :
            $array[] = $row;
        endwhile;
    endif;
endif;

echo json_encode($array);
?>