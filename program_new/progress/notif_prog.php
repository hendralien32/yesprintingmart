<?php
require_once "../../function.php";

$namePage = $_POST['showPage'];
?>

<?php if( $namePage == "logList" ) : // List Log Per ID

    $sql = 
        "SELECT 
            penjualan.history
        FROM 
            penjualan
        WHERE
            penjualan.oid = '$_POST[oid]'
    ";

    // Untuk Yesprintingmart
    $result = $conn_OOP -> query($sql);

    // Untuk YESCOM
    // $result = $conn_Server -> query($sql); 

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    endif;
    ?>

    <table id='tLogs'>
        <tr>
            <th width='20%'>Tanggal & Waktu</th>
            <th width='80%' colspan='2'>Details Logs</th>
        </tr>
        <?php 
        $history = str_replace(["<ul>","</ul>"], "", $row['history']);
        $history_1 = str_replace("<li>", "<tr style='border-right:none'><td colspan='3'>", $history);
        $FINAL_history = str_replace("</li>", "</td></tr>", $history_1);
    
        echo "$FINAL_history";
        ?>
    </table>
<?php elseif( $namePage == "kekuranganStockDP" ) : // List Kekurangan Stock ?>
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
<?php elseif( $namePage == "imagePreview" ) :
    $sql =
    "SELECT 
            penjualan.posisi_file,
            penjualan.file_design,
            penjualan.img_design
        FROM 
            penjualan
        WHERE
            penjualan.oid = '$_POST[oid]'
    ";

    $result = $conn_OOP -> query($sql);
    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
        $image = file_get_contents($row['posisi_file'] . $row['img_design']);
        $image_codes = base64_encode($image);
    endif;
    ?>

    <div id="imgPreview">
        <div class='imageFile'>
            <image src="data:image/jpg;charset=utf-8;base64,<?= $image_codes; ?>">
        </div>
        <div class='btnDownload'>
            <span class='styleDownload pointer'><a href='download.php?link=<?= $row['file_design'] ?>&LocationFile=<?= $row['posisi_file'] ?>'>Download Button <i class="fas fa-download"></i></a></span>
        </div>
    </div>
<?php elseif( $namePage == "xXX" ) : ?>
<?php  else : // Notif Page ?>
    ERROR
<?php endif; ?>