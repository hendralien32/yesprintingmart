<?php
require_once "../../function_new.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}

$uniqueID = uniqid();

$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);
if(is_array($decoded)) {
    $typeProgress = $decoded['typeProgress'];
    $error = $decoded['error'];
} else {
    $typeProgress = !empty($_POST['typeProgress']) ? $_POST['typeProgress'] : '';
    $error = !empty($_POST['error']) ? $_POST['error'] : 'false';
}


if($typeProgress == "Insert_Absensi") : // Absensi Insert Data
    ($add_Absensi_Harian == 'N') ? die("error") : true;

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
    ($add_Absensi_Harian == 'N') ? die("error") : true;

    $jumlahArray = count(explode(",", "$_POST[uid]"));
    $uid = explode (",", "$_POST[uid]" );
    $jamMulai = explode (",", "$_POST[jamMulai]" );
    $jamSelesai = explode (",", "$_POST[jamSelesai]" );
    $permisiCB = explode (",", "$_POST[permisiCB]" );
    $lemburCB = explode (",", "$_POST[lemburCB]" );
    $cutiCB = explode (",", "$_POST[cutiCB]" );

    for($i = 0; $i < $jumlahArray; $i++) {
        // if you really want to make multiple assignments in one statement, you can use the list construct:
        ($lemburCB[$i] == "Y") 
        ? (list($lembur_mulai, $lembur_selesai) = array($jamMulai[$i], $jamSelesai[$i]))
        : (list($lembur_mulai, $lembur_selesai) = array("", ""));

        ($permisiCB[$i] == "Y") 
        ? (list($permisi_keluar, $permisi_masuk) = array($jamMulai[$i], $jamSelesai[$i]))
        : (list($permisi_keluar, $permisi_masuk) = array("", ""));

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
                $checked[] = "$row[nama]";
                $insertAbsensi[] = "";
            else :
                $checked[] = null;
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
            $checked[] = null;
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
    $a = array_filter($checked);
    $test = implode(", ", $a);
    $test .= ' Cuti sudah terdaftar';
    $New_Insert = implode(',', $insertAbsensi);

    if($_POST['error']=="false") {
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
    ($delete_Absensi_Harian == 'N') ? die("error") : true;
    $sql =
        "UPDATE
            absensi
        SET
            hapus = 'Y'
        WHERE
            absensiID = $_POST[idAbsensi];
    ";
elseif($typeProgress == "Form_Update_Absensi_Individu") : // Absensi Update Data Individu
    ($edit_Absensi_Harian == 'N') ? die("error") : true;

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
    
elseif($typeProgress == "insert_user") :

    $listPage = explode (",", "$decoded[listPage]" );
    $jumlahPage = count($listPage);
    // $roleAccess = ['page' , 'add' , 'edit' , 'delete' , 'log' , 'download' , 'imagePreview'];

    for($i = 0; $i < $jumlahPage ; $i++) {
        $page = explode ("|", "$listPage[$i]");
        $access = explode (",", $decoded[$page[0]] );
        $roleAccess = "";
        for($j = 0; $j < count($access); $j++) {
            $roleAccess .= "'$access[$j]',"; 
        }

        //LAST_INSERT_ID() untuk mengambil ID yang sudah terbuka pada pm_user

        $insertRCA[] = "
            (
                $roleAccess
                '$page[1]',
                LAST_INSERT_ID()
            )
        ";
    }

    $New_Insert = implode(',', $insertRCA);

    $password = htmlentities($decoded['password'], ENT_QUOTES);
    $pass     = md5("pmart" . "$password");

    $sql = 
        "INSERT INTO pm_user 
            (
                nama,
                username,
                password,
                password_visible,
                tanggal_masuk,
                tanggal_resign,
                absensi,
                jam_masuk,
                jam_pulang,
                status
            )  VALUES (
                '$decoded[nama]',
                '$decoded[username]',
                '$pass',
                '$decoded[password]',
                '$decoded[tglMasuk]',
                '$decoded[tglKeluar]',
                'Y',
                '09:00:00',
                '18:00:00',
                'a'
            );
    ";

    $sql .= 
        "INSERT INTO database_accessrole
            (
                access_page,
                access_add,
                access_edit,
                access_delete,
                access_log,
                access_download,
                access_imagePreview,
                page_id,
                user_id
            ) VALUE $New_Insert
    ";
elseif($typeProgress == "xxx") :
else :

endif;

$resultChecked = 
(empty($a)) 
    ? true
    : $test;

$resultError = 
$error != "" && $error != "false"
    ? "& $error"
    : "";

if($resultChecked === true && $resultError === '') {
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
        ERROR : $resultChecked $resultError 
        </>
    ";
}

// Close connection
$conn->close();

?>