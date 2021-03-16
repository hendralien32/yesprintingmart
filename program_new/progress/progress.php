<?php
session_start();
require_once "../../function.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}


if($_POST['typeProgress'] == "Insert_Absensi") : 
    $jumlahArray = count(explode(",", "$_POST[uid]"));
    $uid = explode (",", "$_POST[uid]" );
    $scanMasuk = explode (",", "$_POST[scanMasuk]" );
    $scanKeluar = explode (",", "$_POST[scanKeluar]" );
    $absensiCB = explode (",", "$_POST[absensiCB]" );
    $cutiCB = explode (",", "$_POST[cutiCB]" );
    $uniqueID = uniqid();

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
                uniquePost,
                hapus
            )  VALUES $New_Insert
    ";

    echo $sql;
else :
    echo "Something Wrong";
endif;

?>