<?php
session_start();
require_once '../../function.php';
$n = 0;

$sql =
"SELECT
    pm_user.nama as namaKaryawan,
    absensi.absen,
    absensi.hadir,
    absensi.cuti,
    absensi.absen,
    absensi.telat,
    left(absensi.totalTelat,5) as totalTelat
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
                WHEN absensi.scan_masuk != '00:00:00' and absensi.lembur = 'N' and absensi.cuti = 'N' then 1
                ELSE 0
                END
            ) as hadir,
            SEC_TO_TIME(sum(CASE
                WHEN absensi.scan_masuk != '00:00:00' 
             		then if(
                        		TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, '09:00:00')) < 0, 
            					0,
             					TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, '09:00:00'))
                            )
                ELSE 0
                END
            )) as totalTelat,
            sum(CASE
                WHEN absensi.scan_masuk != '00:00:00' 
             		then if(
                        		TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, '09:00:00')) > 0, 
            					1,
             					0
                            )
                ELSE 0
                END
            ) as telat
        FROM
            absensi
        WHERE
            LEFT(absensi.tanggal,7) = '2021-03'
        GROUP BY
            LEFT(absensi.tanggal,7)
    ) as absensi
ON
    absensi.uid = pm_user.uid
WHERE
    pm_user.absensi = 'Y'
";

// Perform query
$result = $conn_OOP->query($sql);
$jumlah_order = $result->num_rows;

$days = cal_days_in_month(CAL_GREGORIAN,03,2021);
?>
    
    
    <div class='list-karyawan'>
        <table>
            <tr>
                <th>#</th>
                <th>Karyawan</th>
                <th>Hadir</th>
                <th>Libur</th>
                <th>absen</th>
                <th>Cuti</th>
                <th>Telat</th>
                <th>Total Hari</th>
                <th>Total Durasi Telat</th>
            </tr>
            <?php
                if ($jumlah_order > 0) :
                    while ($d = $result->fetch_assoc()) :
                        $n++;
                        $libur = $days - $d['hadir'] - $d['absen'];

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
                        </tr>
                        ";
                    endwhile;
                endif;
            ?>
        </table>
    </div>