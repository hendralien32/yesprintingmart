<?php if ($_SESSION['level'] == "operator_dp" || $_SESSION['level'] == "admin") : ?>
    <div id='dashboard'>
        <div id='plugin'>
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
                    <i class="fal fa-abacus"></i>
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
                    <span>5 Jenis</span>
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
                    <i class="fal fa-abacus"></i>
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