<?php
session_start();
require_once "../../function.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}

$uniqueID = uniqid();

if($_POST['typeProgress'] == "Insert_Absensi") : // Absensi Insert Data
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

elseif($_POST['typeProgress'] == "Insert_Absensi_Individu") : // Absensi Personal Insert Data
    $jumlahArray = count(explode(",", "$_POST[uid]"));
    $uid = explode (",", "$_POST[uid]" );
    $jamMulai = explode (",", "$_POST[jamMulai]" );
    $jamSelesai = explode (",", "$_POST[jamSelesai]" );
    $permisiCB = explode (",", "$_POST[permisiCB]" );
    $lemburCB = explode (",", "$_POST[lemburCB]" );
    $cutiCB = explode (",", "$_POST[cutiCB]" );

    for($i = 0; $i < $jumlahArray; $i++) {
        if($lemburCB[$i] == "Y") {
            $lembur_mulai = $jamMulai[$i];
            $lembur_selesai = $jamSelesai[$i];
        } else {
            $lembur_mulai = "";
            $lembur_selesai = "";
        }

        if($permisiCB[$i] == "Y") {
            $permisi_keluar = $jamMulai[$i];
            $permisi_masuk = $jamSelesai[$i];
        } else {
            $permisi_keluar = "";
            $permisi_masuk = "";
        }

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
    }

    $New_Insert = implode(',', $insertAbsensi);

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

else :

endif;

if ($conn->multi_query($sql) === TRUE) {
    echo "true";
} else {
    if (mysqli_query($conn, $sql)) {
        echo "true";
    } else {
        $resultChecked = 
            isset($checked) 
                ? $checked 
                : "";

        echo "
                <b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>
                ERROR : Could not able to execute because \" " . mysqli_error($conn) . " \"<br>
                Query : $sql <br><br>
                Another Error : $resultChecked <br><br>
                </b>
            ";
    }
}

// Close connection
$conn->close();

?>