<?php
require_once "../../function_new.php";

$tipe = $_POST['tipe'];
?>
<div class='lineBlack'></div>
<?php if($_POST['tipe'] == 'ConfirmBox_Hapus') : ?>
    <div class='lineBlack'></div>
    <div class='content'>
        <div class='Title-Content'>
            <i class="fas fa-trash-alt"></i>
            <p>Hapus Data Absensi</p>
        </div>
        <div class='Text-Content'>
            Apabila anda Menghapus data maka data akan permanen terhapus dari database anda. Apa anda yakin ?. <br>
            <b>Data yang sudah hapus tidak dapat dikembalikan.</b>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tidak, Tutup Form</button>
            <button class="yes-btn">Iya, Hapus Data</button>
        </div>
    </div>
    <?= die(); ?>
    
<?php elseif($_POST['tipe'] == "Form_Update_Absensi_Individu") :
    $sql = 
        "SELECT
            karyawan.nama as namaKaryawan,
            absensi.tanggal,
            (CASE
                WHEN absensi.scan_masuk != '0000-00-00' THEN absensi.scan_masuk
                WHEN absensi.permisi_keluar != '0000-00-00' THEN absensi.permisi_keluar
                WHEN absensi.lembur_mulai != '0000-00-00' THEN absensi.lembur_mulai
                ELSE '0000-00-00'
            END) as jam_mulai,
            (CASE
                WHEN absensi.scan_pulang != '0000-00-00' THEN absensi.scan_pulang
                WHEN absensi.permisi_masuk != '0000-00-00' THEN absensi.permisi_masuk
                WHEN absensi.lembur_selesai != '0000-00-00' THEN absensi.lembur_selesai
                ELSE '0000-00-00'
            END) as jam_selesai,
            absensi.hadir,
            absensi.absen,
            absensi.cuti,
            absensi.lembur,
            (CASE WHEN absensi.permisi = '' THEN 'N' ELSE absensi.permisi END) as permisi
        FROM
            absensi
        LEFT JOIN
            (SELECT
                pm_user.uid,
                pm_user.nama
            FROM
                pm_user
            ) karyawan
        ON
            karyawan.uid = absensi.uid
        WHERE
            absensi.absensiID = $_POST[id]
        LIMIT
            1
    ";

    $result = $conn_OOP->query($sql);
    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();

        ($row['hadir'] == "Y") 
            ?   $checkHadir = 'checked'
            :   $checkHadir = 'disabled';

        ($row['lembur'] == "Y") 
            ?   $checkLembur = 'checked'
            :   $checkLembur = 'disabled';

        ($row['absen'] == "Y") 
            ?   $checkAbsen = 'Checked'
            :   $checkAbsen = 'disabled';
                
        ($row['cuti'] == "Y") 
            ?   $checkCuti = 'Checked'
            :   $checkCuti = 'disabled';

        ($row['permisi'] == "Y") 
            ?   $checkPermisi = 'Checked'
            :   $checkPermisi = 'disabled';
    endif;

    ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class='far fa-pen-square'></i>
            <p>Edit Data Absensi</p>
        </div>
        <div class='Text-Content'>
            <div class='input-left'>
                <table>
                    <tr>
                        <td><b>Nama Karyawan</b></td>
                        <td><input type="text" class='disabled' disabled readonly value='<?= $row['namaKaryawan'] ?>'></td>
                    </tr>
                    <tr>
                        <td><b>Jam Mulai</b></td>
                        <td><input type="time" id='jam_mulai' value='<?= $row['jam_mulai'] ?>'></td>
                    </tr>
                    <tr>
                        <td><b>Jam Selesai</b></td>
                        <td><input type="time" id='jam_selesai' value='<?= $row['jam_selesai'] ?>'></td>
                    </tr>
                </table>
            </div>
            <div class='input-right'>
                <table>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td colspan="2"><input type='date' id='tanggal' class='disabled' disabled readonly value='<?= $row['tanggal'] ?>'></td>
                    </tr>
                    <tr>
                        <td rowspan="3"><b>Jenis Absensi</b></td>
                        <td>
                            <div class="form-checkbox">
                                <input class="input-checkbox100" id="hadir" type="checkbox" value='hadir' <?="$checkHadir" ?>>
                                <label class="label-checkbox100" for="hadir">
                                    Hadir
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="form-checkbox">
                                <input class="input-checkbox100" id="permisi" type="checkbox" value='permisi' <?="$checkPermisi" ?>>
                                <label class="label-checkbox100" for="permisi">
                                    Permisi
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-checkbox">
                                <input class="input-checkbox100" id="absen" type="checkbox" value='absen' <?= "$checkAbsen" ?>>
                                <label class="label-checkbox100" for="absen">
                                    Absen
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="form-checkbox">
                                <input class="input-checkbox100" id="lembur" type="checkbox" value='lembur' <?="$checkLembur" ?>>
                                <label class="label-checkbox100" for="lembur">
                                    Lembur
                                </label>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <div class="form-checkbox">
                                <input class="input-checkbox100" id="cuti" type="checkbox" value='cuti' <?= "$checkCuti" ?>>
                                <label class="label-checkbox100" for="cuti">
                                    Cuti
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
            <button class="yes-btn">Submit Data</button>
        </div>
    </div>
    <?= die(); ?>
    
<?php elseif($_POST['tipe'] == "Form_Absensi_Individu") : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class='far fa-pen-square'></i>
            <p>Add Data Absensi Individual</p>
        </div>
        <div class='Text-Content'>
            <div class='input-left'>
                <table>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td><input type="date" name="tanggal" class='tglAbsensiHarian' id="tglAbsensiHarian" value="<?= $date; ?>"></td>
                    </tr>
                </table>
            </div>
            <div class='input-right'></div>  
        </div>
        <div class='Table-Content'>
            <table>
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Permisi</th>
                    <th>Lembur</th>
                    <th>Cuti</th>
                    <th width="4%"></th>
                </tr>
                <tbody id='dynamic-field'>
                    <tr class='row_1'>
                        <td>
                            <input type='text' data-nomor='1' class='namaKaryawan_1' id='namaKaryawan' autocomplete='off'>
                            <div class='autocomplete ac_1'></div>
                            <input type='hidden' data-nomor='1' class='idKaryawan_1' id='idKaryawan' style='width:15%'>
                            <span class='checklist_1'></span>
                        </td>
                        <td class='center'><input data-nomor='1' type='time' id='jamMulai'></td>
                        <td class='center'><input data-nomor='1' type='time' id='jamSelesai'></td>
                        <td class='center'>
                            <div class='form-checkbox'>
                                <input data-nomor='1' data-input='permisi' class='input-checkbox100 permisi' id='permisi 1' type='checkbox' value='permisi'>
                                <label class='label-checkbox100' for='permisi 1'></label>
                            </div>
                        </td>
                        <td class='center'>
                            <div class='form-checkbox'>
                                <input data-nomor='1' data-input='lembur' class='input-checkbox100 lembur' id='lembur 1' type='checkbox' value='lembur'>
                                <label class='label-checkbox100' for='lembur 1'></label>
                            </div>
                        </td>
                        <td class='center'>
                            <div class='form-checkbox'>
                                <input data-nomor='1' data-input='cuti' class='input-checkbox100 cuti' id='cuti 1' type='checkbox' value='cuti'>
                                <label class='label-checkbox100' for='cuti 1'></label>
                            </div>
                        </td>
                        <td class='center add'><i class='far fa-plus btn'></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
            <button class="yes-btn">Submit Data</button>
        </div>
    </div>
    <?= die(); ?>
<?php elseif($_POST['tipe'] == "Insert_Absensi") : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class='far fa-pen-square'></i>
            <p>Add Data Absensi Harian</p>
        </div>
        <div class='Text-Content'>
            <div class='input-left'>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td><input type="date" name="tanggal" class='tglAbsensi' id="tglAbsensi" value="<?= $date; ?>"</td>
                    </tr>
                </table>
            </div>
            <div class='input-right'>   
            </div>  
        </div>
        <div class='Table-Content'>
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
                                    absensi.tanggal = '$date' and
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
                                END ) >= '$months'
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
                                    <td class='center'>$no</td>
                                    <td>
                                        <input data-uid='$d[uid]' type='hidden' id='karyawanUid' value='$d[uid]'>
                                        <span data-uid='$d[uid]'>$d[nama]</span>
                                    </td>
                                    <td class='center'><input data-uid='$d[uid]' type='time' class='scanMasuk' id='scanMasuk_$d[uid]'></td>
                                    <td class='center'><input data-uid='$d[uid]' type='time' class='scanKeluar' id='scanKeluar_$d[uid]'></td>
                                    <td class='center'>
                                        <div class='form-checkbox'>
                                            <input data-uid='$d[uid]' class='input-checkbox100 Absen' id='Absen_$d[uid]' type='checkbox' value='absen'>
                                            <label class='label-checkbox100' for='Absen_$d[uid]'></label>
                                        </div>
                                    </td>
                                    <td class='center'>
                                        <div class='form-checkbox'>
                                            <input data-uid='$d[uid]' class='input-checkbox100 Cuti' id='Cuti_$d[uid]' type='checkbox' value='cuti'>
                                            <label class='label-checkbox100' for='Cuti_$d[uid]'></label>
                                        </div>
                                    </td>
                                </tr>
                                ";
                            endwhile;
                        endif;
                    ?>
            </table>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
            <button class="yes-btn">Submit Data</button>
        </div>
    </div>
    <?= die(); ?>
<?php elseif($_POST['tipe'] == "listAbsensi") :
    $date = date_format(date_create($_POST['id']),"Y-m-d");

    $sql = 
        "SELECT
            GROUP_CONCAT((CASE 
                absensi.cuti 
                WHEN 'Y' THEN user.nama
                END
            )) as karyawanCuti,
            GROUP_CONCAT((CASE 
                absensi.absen 
                WHEN 'Y' THEN user.nama
                END
            )) as karyawanAbsen,
            GROUP_CONCAT((CASE 
                WHEN TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, user.jam_masuk)) > 0 THEN user.nama
                END
            )) as karyawanTelat
        FROM
            absensi
        LEFT JOIN
            (
                SELECT
                    pm_user.uid,
                    pm_user.nama,
                    pm_user.jam_masuk
                FROM
                    pm_user
            ) as user
        ON
            user.uid = absensi.uid
        WHERE
            absensi.tanggal = '$date' and
            ( 
                absensi.cuti = 'Y' || 
                absensi.absen = 'Y' || 
                absensi.hadir = 'Y' || 
                TIME_TO_SEC(TIMEDIFF(absensi.scan_masuk, user.jam_masuk)) > 0 
            ) and
            absensi.hapus != 'Y'
    ";

    $result = $conn_OOP->query($sql);
    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();

        $karyawanCuti = array_filter(explode(",", $row['karyawanCuti']));
        $karyawanAbsen = array_filter(explode(",", $row['karyawanAbsen']));
        $karyawanTelat = array_filter(explode(",", $row['karyawanTelat']));
    endif;

    ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class="fas fa-calendar-day"></i>
            <p><?= format_hari_tanggal($date) ?></p>
        </div>
        <div class='Text-Content'>
            <table>
                <tr>
                    <th>Telat</th>
                    <th>Absen</th>
                    <th>Cuti</th>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <?php 
                                foreach($karyawanTelat as $namaKaryawan) {
                                    echo "<li>". ucwords($namaKaryawan) ."</li>";
                                }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <?php 
                                foreach($karyawanAbsen as $namaKaryawan) {
                                    echo "<li>". ucwords($namaKaryawan) ."</li>";
                                }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <?php 
                                foreach($karyawanCuti as $namaKaryawan) {
                                    echo "<li>". ucwords($namaKaryawan) ."</li>";
                                }
                            ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="yes-btn display-none">Submit Data</button>
            <button class="no-btn">Tutup Form</button>
        </div>
    </div>
    <?= die(); ?>
<?php elseif($_POST['tipe'] == "xxx") : ?>
<?php else : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class="far fa-exclamation-circle"></i>
            <p>Data Not Found</p>
        </div>
        <div class='Text-Content'>
            Data tidak ditemukan coba kontak Web developer
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
        </div>
    </div>
<?php endif; ?>