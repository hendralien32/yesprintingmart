<?php
session_start();
require_once "../../function.php";

?>

<div class='absensi'>
    <div class='absensiTop'>
        <table>
            <tr>
                <td>Tanggal</td>
                <td><input type="date" name="tanggal" id="tglAbsensi" value="<?= $date; ?>"></td>
            </tr>
        </table>
    </div>
    <div class='absensiList'>
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
                                absensi.tanggal = '$date'
                            ) absensi
                        ON
                            absensi.uid  = pm_user.uid
                        WHERE
                            pm_user.absensi = 'Y' and
                            absensi.lembur != 'Y' || absensi.lembur != '' and 
                            absensi.permisi != 'Y' || absensi.permisi != ''
                        order by
                            pm_user.nama
                        asc
                    ";
                    $result = $conn_OOP->query($sql);
                    $jumlah_order = $result->num_rows;
                    echo "$sql";
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
                                <td><input data-uid='$d[uid]' type='checkbox' id='Absen' value='absen'></td>
                                <td><input data-uid='$d[uid]' type='checkbox' id='Cuti' value='cuti'></td>
                            </tr>
                            ";
                        endwhile;
                    endif;
                ?>
        </table>
    </div>
    <div class="absensiSubmit">
        <button id='submit'>Submit</button>
    </div>

    <div class='resultQuery'>
    </div>
</div>