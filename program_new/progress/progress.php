<?php
session_start();
require_once "../../function.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}

$uniqueID = uniqid();

$typeProgress = !empty($_POST['typeProgress']) ? $_POST['typeProgress'] : '';

if($typeProgress == "Insert_Absensi") : // Absensi Insert Data
    $jumlahArray = count(explode(",", "$_POST[uid]"));
    $uid = explode (",", "$_POST[uid]" );
    $scanMasuk = explode (",", "$_POST[scanMasuk]" );
    $scanKeluar = explode (",", "$_POST[scanKeluar]" );
    $absensiCB = explode (",", "$_POST[absensiCB]" );
    $cutiCB = explode (",", "$_POST[cutiCB]" );

    for($i = 0; $i < $jumlahArray; $i++) {
        $hadir = ( $scanMasuk[$i] != "" ) ? "Y" : "N";
        $insertAbsensi[] = "
            (
                '$uid[$i]',
                '$_POST[tglAbensi]',
                '$scanMasuk[$i]',
                '$scanKeluar[$i]',
                '$hadir',
                '$absensiCB[$i]',
                '$cutiCB[$i]',
                'N',
                'N',
                '$uniqueID',
                'N'
            )
        ";
    }

    $New_Insert = implode(',', $insertAbsensi);

    $sql =
        "INSERT INTO absensi 
            (
                uid,
                tanggal,
                scan_masuk,
                scan_pulang,
                hadir,
                absen,
                cuti,
                lembur,
                permisi,
                uniquePost,
                hapus
            )  VALUES $New_Insert
    ";

elseif($typeProgress == "Form_Absensi_Individu") : // Absensi Personal Insert Data Individu
    $jumlahArray = count(explode(",", "$_POST[uid]"));
    $uid = explode (",", "$_POST[uid]" );
    $jamMulai = explode (",", "$_POST[jamMulai]" );
    $jamSelesai = explode (",", "$_POST[jamSelesai]" );
    $permisiCB = explode (",", "$_POST[permisiCB]" );
    $lemburCB = explode (",", "$_POST[lemburCB]" );
    $cutiCB = explode (",", "$_POST[cutiCB]" );

    for($i = 0; $i < $jumlahArray; $i++) {
        ($lemburCB[$i] == "Y") 
        ?   $lembur_mulai = $jamMulai[$i] xor
            $lembur_selesai = $jamSelesai[$i]
        :   $lembur_mulai = "" xor
            $lembur_selesai = "";
    
        ($permisiCB[$i] == "Y") 
        ?   $permisi_keluar = $jamMulai[$i] xor
            $permisi_masuk = $jamSelesai[$i]
        :   $permisi_keluar = "" xor
            $permisi_masuk = "";

        if($cutiCB[$i] == 'Y') :
            $cutiSql =
                "SELECT
                    karyawan.nama
                FROM
                    absensi
                LEFT JOIN
                    (SELECT
                        pm_user.nama,
                        pm_user.uid
                    FROM
                        pm_user
                    ) karyawan
                ON
                    karyawan.uid = absensi.uid
                WHERE
                    absensi.tanggal = '$_POST[tglAbensi]' and
                    absensi.cuti = '$cutiCB[$i]' and
                    absensi.uid = '$uid[$i]' and
                    absensi.hapus != 'Y'
                LIMIT
                    1
            ";    
            $result = $conn_OOP->query($cutiSql);
            if ($result->num_rows > 0) :
                $row = $result->fetch_assoc();
                $checked[] = "$row[nama] Cuti sudah terdaftar, ";
                $insertAbsensi[] = "";
            else :
                $checked[] = "";
                $insertAbsensi[] = "
                    (
                        '$uid[$i]',
                        '$_POST[tglAbensi]',
                        '$permisi_keluar',
                        '$permisi_masuk',
                        '$lembur_mulai',
                        '$lembur_selesai',
                        'N',
                        'N',
                        '$cutiCB[$i]',
                        '$lemburCB[$i]',
                        '$permisiCB[$i]',
                        '$uniqueID',
                        'N'
                    )
                ";
            endif;
        else :
            $checked[] = "";
            $insertAbsensi[] = "
                (
                    '$uid[$i]',
                    '$_POST[tglAbensi]',
                    '$permisi_keluar',
                    '$permisi_masuk',
                    '$lembur_mulai',
                    '$lembur_selesai',
                    'N',
                    'N',
                    '$cutiCB[$i]',
                    '$lemburCB[$i]',
                    '$permisiCB[$i]',
                    '$uniqueID',
                    'N'
                )
            ";
        endif;
    }
    $test = implode(" | ", $checked);
    $New_Insert = implode(',', $insertAbsensi);

    if($_POST['error']=="") {
        $sql =
            "INSERT INTO absensi 
                (
                    uid,
                    tanggal,
                    permisi_keluar,
                    permisi_masuk,
                    lembur_mulai,
                    lembur_selesai,
                    hadir,
                    absen,
                    cuti,
                    lembur,
                    permisi,
                    uniquePost,
                    hapus
                )  VALUES $New_Insert
        ";
    } else {
        $sql = "";
    }

elseif($typeProgress == "ConfirmBox_Hapus") : // Absensi Delete Data
    $sql =
        "UPDATE
            absensi
        SET
            hapus = 'Y'
        WHERE
            absensiID = $_POST[idAbsensi];
    ";
elseif($typeProgress == "Form_Update_Absensi_Individu") : // Absensi Update Data Individu
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $absen = $_POST['absen'];
    $cuti = $_POST['cuti'];
    $permisi = $_POST['permisi'];
    $lembur = $_POST['lembur'];

    if($permisi == 'Y') {
        $setUpdate ="
            permisi_keluar = '$jam_mulai',
            permisi_masuk = '$jam_selesai',
            hadir = 'N',
        ";
    } elseif($lembur == 'Y') {
        $setUpdate ="
            lembur_mulai = '$jam_mulai',
            lembur_selesai = '$jam_selesai',
            hadir = 'N',
        ";
    }  elseif($cuti == 'Y' || $absen == 'Y') {
        $setUpdate ="
            scan_masuk = '0000-00-00',
            scan_pulang = '0000-00-00',
            hadir = 'N',
        ";
    } else {
        $setUpdate ="
            scan_masuk = '$jam_mulai',
            scan_pulang = '$jam_selesai',
            hadir = 'Y',
        ";
    }

    $sql =
        "UPDATE
            absensi
        SET
            $setUpdate
            cuti = '$cuti',
            lembur = '$lembur',
            permisi = '$permisi',
            absen = '$absen'
        WHERE
            absensiID = $_POST[idAbsensi];
    ";
    
elseif($typeProgress == "xxx") :
else :

endif;

$resultChecked = 
(isset($test)) 
    ? $test 
    : "";

$resultError = 
(isset($_POST['error'])) 
    ? $_POST['error'] 
    : "";

if($resultChecked == "" && $resultError == "") {
    if ($conn->multi_query($sql) === TRUE) {
        echo "true";
    } else {
        if (mysqli_query($conn, $sql)) {
            echo "true";
        } else {
            echo "false";
        }
    }
} else {
    echo "
        <b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>
        ERROR : $resultChecked $resultError <br>
        </b>
    ";
}

// Close connection
$conn->close();

?>