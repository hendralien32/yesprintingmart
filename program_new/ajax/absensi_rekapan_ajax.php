<?php
session_start();
require_once '../../function.php';
$n = 0;

$bulanDari = $_POST['blnDari'];
$username = $_POST['username'];

$SearchMonth = 
    ($bulanDari != "" )
        ? "and LEFT(absensi.tanggal,7)='$bulanDari'"
        : "";

$SearchKaryawan = 
    ( $username!="" )
        ? "and pm_user.nama LIKE '%$username%'" 
        : "";

$sql =
"SELECT
    pm_user.nama as namaKaryawan,
    absensi.absen,
    absensi.hadir,
    absensi.cuti,
    absensi.absen,
    absensi.telat,
    left(absensi.totalTelat,5) as totalTelat,
    left(absensi.totalPermisi,5) as totalPermisi
FROM
    pm_user
LEFT JOIN
    (
        SELECT
            absensi.uid,
            sum(CASE 
                absensi.absen 
                WHEN 'Y' THEN 1 
                ELSE 0 
                END
            ) as absen,
            sum(CASE 
                absensi.cuti 
                WHEN 'Y' THEN 1 
                ELSE 0 
                END
            ) as cuti,
            sum(CASE
                WHEN absensi.scan_masuk != '00:00:00' and absensi.hadir = 'Y' then 1
                ELSE 0
                END
            ) as hadir,
            SEC_TO_TIME(sum(CASE
                WHEN absensi.scan_masuk != '00:00:00' 
             		then if(
                        		TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, user.jam_masuk)) < 0, 
            					0,
             					TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, user.jam_masuk))
                            )
                ELSE 0
                END
            )) as totalTelat,
            SEC_TO_TIME(sum(CASE
                WHEN absensi.permisi_keluar != '00:00:00' 
             		then if(
                        		TIME_TO_SEC(TIMEDIFF(absensi.permisi_masuk, absensi.permisi_keluar)) < 0, 
            					0,
             					TIME_TO_SEC(TIMEDIFF(absensi.permisi_masuk, absensi.permisi_keluar))
                            )
                ELSE 0
                END
            )) as totalPermisi,
            sum(CASE
                WHEN absensi.scan_masuk != '00:00:00' 
             		then if(
                        		TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, user.jam_masuk)) > 0, 
            					1,
             					0
                            )
                ELSE 0
                END
            ) as telat
        FROM
            absensi
        LEFT JOIN
            (
                SELECT
                    pm_user.uid,
                    pm_user.jam_masuk
                FROM
                    pm_user
            ) as user
        ON
            user.uid = absensi.uid
        WHERE
            absensi.hapus != 'Y'
            $SearchMonth
        GROUP BY
            absensi.uid
    ) as absensi
ON
    absensi.uid = pm_user.uid
WHERE
    pm_user.absensi = 'Y' and
    (CASE 
        WHEN LEFT(pm_user.tanggal_resign,7) = '0000-00' THEN '9999-12'
        ELSE LEFT(pm_user.tanggal_resign,7)
    END ) >= '$bulanDari'
    $SearchKaryawan
ORDER BY
    pm_user.nama
";

// Perform query
$result = $conn_OOP->query($sql);
$jumlah_order = $result->num_rows;
$days = cal_days_in_month(CAL_GREGORIAN, substr($bulanDari,5,2), substr($bulanDari,0,4));
echo "<span id='jumlahKaryawan' class='display-none'>Bulan ". format_bulanTahun($bulanDari) .", Total $jumlah_order Karyawan</span>";
?>
    
<div class='list-karyawan'>
    <table>
        <tr>
            <th>#</th>
            <th>Karyawan</th>
            <th>Hadir</th>
            <th>Libur</th>
            <th>Absen</th>
            <th>Cuti</th>
            <th>Telat</th>
            <th>Total Hari</th>
            <th>Durasi Telat</th>
            <th>Durasi Permisi</th>
        </tr>
        <?php
            if ($jumlah_order > 0) :
                while ($d = $result->fetch_assoc()) :
                    $n++;
                    $libur = $days - $d['hadir'] - $d['absen'] - $d['cuti'];
                    echo "
                    <tr>
                        <td>$n</td>
                        <td>". ucfirst($d['namaKaryawan']) ."</td>
                        <td>$d[hadir] hari</td>
                        <td>$libur hari</td>
                        <td>$d[absen] hari</td>
                        <td>$d[cuti] hari</td>
                        <td>$d[telat] hari</td>
                        <td>$days hari</td>
                        <td>$d[totalTelat]</td>
                        <td>$d[totalPermisi]</td>
                    </tr>
                    ";
                endwhile;
            endif;
        ?>
    </table>
</div>