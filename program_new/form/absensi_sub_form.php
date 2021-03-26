<?php
session_start();
require_once "../../function.php";

$tanggal = $_POST['tanggal'];
$bulan = substr($tanggal,0,7);
?>
    
    <table>
        <tr>
            <th>#</th>
            <th>Nama Karyawan</th>
            <th>Scan Masuk</th>
            <th>Scan Pulang</th>
            <th>Absen</th>
            <th>Cuti</th>
        </tr>
        <?php
            $sql = 
                "SELECT
                    pm_user.uid, 
                    pm_user.nama,
                    absensi.tanggal,
                    absensi.hadir,
                    absensi.cuti,
                    absensi.absen
                FROM
                    pm_user
                LEFT JOIN 
                    (SELECT
                        absensi.uid,
                        absensi.tanggal,
                        absensi.hadir,
                        absensi.cuti,
                        absensi.absen,
                        absensi.lembur,
                        absensi.permisi
                    FROM
                        absensi
                    WHERE  
                        ( absensi.hadir != 'Y' || absensi.cuti != 'Y' || absensi.absen != 'Y') and
                        absensi.tanggal = '$tanggal' and
                        absensi.hapus != 'Y'
                    ) absensi
                ON
                    absensi.uid  = pm_user.uid
                WHERE
                    pm_user.absensi = 'Y' and
                    absensi.hadir is null and
                    (CASE 
                        WHEN LEFT(pm_user.tanggal_resign,7) = '0000-00' THEN '9999-12'
                        ELSE LEFT(pm_user.tanggal_resign,7)
                    END ) >= '$bulan'
                order by
                    pm_user.nama
                asc
            ";
            $result = $conn_OOP->query($sql);
            $jumlah_order = $result->num_rows;
            $no = 0;
            if ($jumlah_order > 0) :
                // output data of each row
                while ($d = $result->fetch_assoc()) :
                    $no++;
                    echo "
                    <tr>
                        <td>$no</td>
                        <td>
                            <input data-uid='$d[uid]' type='hidden' id='karyawanUid' value='$d[uid]'>
                            <span data-uid='$d[uid]'>$d[nama]</span>
                        </td>
                        <td><input data-uid='$d[uid]' type='time' id='scanMasuk'></td>
                        <td><input data-uid='$d[uid]' type='time' id='scanKeluar'></td>
                        <td>
                            <div class='form-checkbox'>
                                <input data-uid='$d[uid]' class='input-checkbox100' id='Absen' type='checkbox' value='absen>
                                <label class='label-checkbox100' for='Absen'>
                                    Absen
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class='form-checkbox'>
                                <input data-uid='$d[uid]' class='input-checkbox100' id='Cuti' type='checkbox' value='cuti>
                                <label class='label-checkbox100' for='Cuti'>
                                    Cuti
                                </label>
                            </div>
                        </td>
                    </tr>
                    ";
                endwhile;
            endif;
        ?>
    </table>