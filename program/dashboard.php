<?php if ($_SESSION['level'] == "operator_dp" || $_SESSION['level'] == "admin") : ?>
    <h3>DIGITAL PRINTING STATS</h3>
    <div id='dashboard'>
        <div id='plugin'>
            <a href='?page=DP_List&tab=Digital_Printing'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-print"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Job Pending</p>
                    <?php
                    $sql =
                        "SELECT
                            penjualan.oid
                        FROM
                            penjualan
                        WHERE
                            (   penjualan.kode = 'digital'
                                or penjualan.kode='offset'
                                or penjualan.kode='etc' 
                            ) and
                            penjualan.inv_check = 'Y' and
                            penjualan.cancel != 'Y' and 
                            penjualan.status != 'selesai'
                    ";
                    $result = $conn_OOP->query($sql)->num_rows;
                    ?>
                    <span><?= number_format($result); ?> Jobs</span>
                </div>
            </div>
            <div id='read_more'>
            <i class="far fa-arrow-circle-right"></i>
            </div>
            </a>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-abacus"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print FC</p>
                    <p>Mesin C-1085</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as FC
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C-1085' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format($row['FC'] / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-abacus"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print BW</p>
                    <p>Mesin C-1085</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C-1085' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format($row['BW'] / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-times"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Error & Maintanance</p>
                    <p>Mesin C-1085</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_FC,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_BW,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_FC,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C-1085' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format(($row['error_FC'] + $row['error_BW'] + $row['Maintanance_FC'] + $row['Maintanance_BW']) / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                <i class="fal fa-sigma"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print</p>
                    <p>Mesin C-1085</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as FC,
                            SUM((CASE
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as BW,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_FC,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_BW,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_FC,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C-1085' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format(($row['FC'] + $row['BW'] + $row['Maintanance_FC'] + $row['Maintanance_BW'] + $row['error_FC'] + $row['error_BW']) / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-sticky-note"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Stock Kertas</p>
                    <p>kekurangan</p>
                    <?php
                    $sql =
                        "SELECT
                            stock.nama_barang,
                            stock.test
                        FROM
                            (
                                SELECT
                                    barang.nama_barang,
                                    barang.min_stock,
                                    ( IFNULL(Brg_Masuk.barang_masuk,0) - IFNULL(Brg_Keluar.barang_keluar,0) - IFNULL(digital.Qty,0) - IFNULL(digital_KodeBrg.Qty,0) ) AS test
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
                            stock.min_stock > stock.test
                    ";
                    $result = $conn_OOP->query($sql)->num_rows;
                    ?>
                    <span><?= number_format($result); ?> Jenis</span>
                </div>
            </div>
        </div>
        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-abacus"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print FC</p>
                    <p>Mesin C-1085</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as FC
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C7000' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format($row['FC'] / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-abacus"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print BW</p>
                    <p>Mesin C7000</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C7000' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format($row['BW'] / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-times"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Error & Maintanance</p>
                    <p>Mesin C7000</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_FC,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_BW,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_FC,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C7000' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format(($row['error_FC'] + $row['error_BW'] + $row['Maintanance_FC'] + $row['Maintanance_BW']) / 2); ?> A3</span>
                </div>
            </div>
        </div>

        <div id='plugin'>
            <div id='isi'>
                <div class='ikon_kiri'>
                    <i class="fal fa-sigma"></i>
                </div>
                <div class='keterangan_kanan'>
                    <p>Digital Printing</p>
                    <p>Total Print</p>
                    <p>Mesin C7000</p>
                    <?php
                    $sql =
                        "SELECT
                            SUM((CASE
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as FC,
                            SUM((CASE
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as BW,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='FC' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_FC,
                            SUM((CASE
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.qty_cetak)
                                WHEN digital_printing.maintanance='Y' and digital_printing.color='BW' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.qty_cetak * 2)
                            END)) as Maintanance_BW,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='FC' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_FC,
                            SUM((CASE
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '1' THEN ROUND(digital_printing.error)
                                WHEN digital_printing.error>0 and digital_printing.color='BW' and digital_printing.maintanance='N' AND digital_printing.sisi = '2' THEN ROUND(digital_printing.error * 2)
                            END)) as error_BW
                        FROM
                            digital_printing
                        WHERE
                            digital_printing.mesin = 'Konika_C7000' and 
                            LEFT(digital_printing.tgl_cetak,7) = '$months'
                    ";
                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        $row = $result->fetch_assoc();
                    else : endif; ?>
                    <span><?= number_format(($row['FC'] + $row['BW'] + $row['Maintanance_FC'] + $row['Maintanance_BW'] + $row['error_FC'] + $row['error_BW']) / 2); ?> A3</span>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    WELCOME to Program Yesprintingmart yang baru
<?php endif; ?>