<?php 
    session_start();
    require_once '../../function.php';
    $n = 0;

    $upahLembur = 10000;
    $uangMakan = 20000;
    $username = $_POST['username'];
    $drTgl = $_POST['drTgl'];
    $keTgl = $_POST['keTgl'];

    $jenisList = 
    (isset($_POST['jenisList'])) 
        ? $_POST['jenisList'] 
        : 'N';

    $SearchKaryawan = 
    ( $username!="" )
        ? "and karyawan.nama LIKE '%$username%'" 
        : "";

    $SearchDate = 
    ($drTgl != "" and $keTgl != "")
        ? "and (absensi.tanggal >='$drTgl' and absensi.tanggal <='$keTgl')"
        : ((($drTgl != "" and $keTgl == "") || ($drTgl == "" and $keTgl != ""))
            ? "and (absensi.tanggal ='$drTgl')"
            : ""
    );
    

    if($jenisList == 'permisi') :
        $sqlName = "
            (CASE WHEN absensi.permisi = '' THEN 'N' ELSE absensi.permisi END) as permisi,
            absensi.permisi_keluar,
            absensi.permisi_masuk,
            if( 
                TIMEDIFF(absensi.permisi_masuk, absensi.permisi_keluar) < 0, 
                '-',
                TIMEDIFF(absensi.permisi_masuk, absensi.permisi_keluar)
            ) as totalPermisi,
        ";

        $sqlWhere = "
            (CASE WHEN absensi.permisi = '' THEN 'N' ELSE absensi.permisi END) = 'Y' and
        ";

        $tableTH = "
            <th>Permisi</th>
            <th>Permisi Keluar</th>
            <th>Permisi Masuk</th>
            <th>Durasi Permisi</th>
        ";

    elseif($jenisList == 'lembur') :
        $sqlName = "
            (CASE WHEN absensi.lembur = '' THEN 'N' ELSE absensi.lembur END) as lembur,
            absensi.lembur_mulai,
            absensi.lembur_selesai,
            if( 
                TIMEDIFF(absensi.lembur_selesai, absensi.lembur_mulai) < 0, 
                '-',
                TIMEDIFF(absensi.lembur_selesai, absensi.lembur_mulai)
            ) as totalLebur,
            if(
                TIME_TO_SEC(TIMEDIFF(absensi.lembur_selesai, absensi.lembur_mulai)) < 0, 
                0,
                TIME_TO_SEC(TIMEDIFF(absensi.lembur_selesai, absensi.lembur_mulai))
            ) as totalSec,
        ";

        $sqlWhere = "
            (CASE WHEN absensi.lembur = '' THEN 'N' ELSE absensi.lembur END) = 'Y' and
        ";

        $tableTH = "
            <th>Lembur</th>
            <th>Lembur Mulai</th>
            <th>Lembur Selesai</th>
            <th>Durasi Lembur</th>
            <th>Upah Lembur</th>
        ";

    else :
        $sqlName = "
            karyawan.jam_masuk,
            absensi.scan_masuk,
            absensi.scan_pulang,
            absensi.absen,
            absensi.cuti,
            if( 
                TIMEDIFF(absensi.scan_masuk, karyawan.jam_masuk) < 0, 
                '-',
                TIMEDIFF(absensi.scan_masuk, karyawan.jam_masuk)
            ) as totalTelat,
        ";

        $sqlWhere = "
            (CASE WHEN absensi.lembur = '' THEN 'N' ELSE absensi.lembur END) != 'Y' and
            (CASE WHEN absensi.permisi = '' THEN 'N' ELSE absensi.permisi END) != 'Y' and
        ";

        $tableTH = "
            <th>Jam Masuk</th>
            <th>Scan Masuk</th>
            <th>Scan Keluar</th>
            <th>Durasi Telat</th>
            <th>Absen</th>
            <th>Cuti</th>
        ";
    endif;

    $sql = 
        "SELECT
            $sqlName
            absensi.absensiID as id,
            absensi.tanggal,
            karyawan.nama as namaKaryawan,
            absensi.uniquePost
        FROM
            absensi
        LEFT JOIN 
            (SELECT
                pm_user.uid,
                pm_user.nama,
                pm_user.jam_masuk
            FROM
                pm_user
            ) karyawan
        ON karyawan.uid = absensi.uid
        WHERE
            $sqlWhere
            absensi.hapus != 'Y'
            $SearchKaryawan
            $SearchDate
        ORDER BY
            karyawan.nama, absensi.tanggal
    ";

    $result = $conn_OOP->query($sql);
    $jumlah_order = $result->num_rows;
    echo "<span id='jumlahKaryawan' class='display-none'>$jumlah_order Data</span>";
?>

<div class='list-karyawan'>
    <table>
        <tr>
            <th width="3%">#</th>
            <th width="12%">Karyawan</th>
            <th>Tanggal</th>
            <?= $tableTH ?>
            <th width="6%"></th>
        </tr>
        <?php
            if ($jumlah_order > 0) :
                while ($d = $result->fetch_assoc()) :
                    $n++;

                    if($jenisList == 'permisi') :
                        $tableTR = "
                            <td>$d[permisi]</td>
                            <td>$d[permisi_keluar]</td>
                            <td>$d[permisi_masuk]</td>
                            <td>$d[totalPermisi]</td>
                        ";

                    elseif($jenisList == 'lembur') :
                        $biayaMakan = ($d['lembur_selesai'] >= '20:00:00' ) ? $uangMakan : 0;
                        $biayaLembur = (($d['totalSec'] / 3600) * $upahLembur) + $biayaMakan;
                        $tableTR = "
                            <td>$d[lembur]</td>
                            <td>$d[lembur_mulai]</td>
                            <td>$d[lembur_selesai]</td>
                            <td>$d[totalLebur]</td>
                            <td>$biayaLembur</td>
                        ";

                    else :
                        $tableTR = "
                            <td>$d[jam_masuk]</td>
                            <td>$d[scan_masuk]</td>
                            <td>$d[scan_pulang]</td>
                            <td>$d[totalTelat]</td>
                            <td>$d[absen]</td>
                            <td>$d[cuti]</td>
                        ";

                    endif;

                    $tglAbsensi = date_format(date_create($d['tanggal']),"d M Y");

                    echo "
                    <tr>
                        <td>$n</td>
                        <td>". ucfirst($d['namaKaryawan']) ."</td>
                        <td>$tglAbsensi</td>
                        $tableTR
                        <td>
                            <span style='padding-right:8px;'><i class='fas fa-pen-square btn' onclick='smallLightBox(\"edit\",\"Absensi_Edit_Individu\",\"$d[id]\")'></i></span>
                            <span><i class='fas fa-trash-alt btn' onclick='confirmForm(\"delete_absensi\",\"Hapus_Absensi\",\"$d[id]\")'></i></span>
                        </td>
                    </tr>
                    ";
                endwhile;
            endif;
        ?>
    </table>
</div>