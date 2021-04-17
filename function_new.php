<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
$conn = mysqli_connect("localhost", "root", "", "new_ypm") or die();
$conn_OOP = new mysqli("localhost", "root", "", "new_ypm");
if ($conn_OOP->connect_error) {
    die("Connection failed: " . $conn_OOP->connect_error);
}

// $conn_Server = new mysqli("192.168.1.1", "root", "", "new_ypm");
// if ($conn_Server->connect_error) {
//     die("Connection failed: " . $conn_Server->connect_error);
// }

$date   = date('Y-m-d');
$months = date('Y-m');

if(isset($_SESSION["login"])) :
    $page_type = $_SESSION["page_type"];
    $page_name = $_SESSION["page_name"];
    $access_page = $_SESSION["access_page"];
    $access_add = $_SESSION["access_add"];
    $access_edit = $_SESSION["access_edit"];
    $access_delete = $_SESSION["access_delete"];
    $access_download = $_SESSION["access_download"];
    $access_imagePreview = $_SESSION["access_imagePreview"];

    $page = explode("," , $page_type);
    $pageName = explode("|" , $page_name);

    // Access Role User Account
    $pageAccess = explode("|" , $access_page);
    $addAccess = explode("|" , $access_add);
    $editAccess = explode("|" , $access_edit);
    $deleteAccess = explode("|" , $access_delete);
    $downloadAccess = explode("|" , $access_download);
    $imagePreviewAccess = explode("|" , $access_imagePreview);

    for ($x = 0; $x < count($page); $x++) {
        $listPageName = explode("," , $pageName[$x]);
        $listPageAccess = explode("," , $pageAccess[$x]);

        $listAddAccess = explode("," , $addAccess[$x]);
        $listEditAccess = explode("," , $editAccess[$x]);
        $listDeleteAccess = explode("," , $deleteAccess[$x]);
        $listDownloadAccess = explode("," , $downloadAccess[$x]);
        $listImagePreviewAccess = explode("," , $imagePreviewAccess[$x]);

        for ($i = 0; $i < count($listPageName); $i++) {
            if($listPageAccess[$i] == "Y") {
                $namePageName = str_replace(" ", "_", $listPageName[$i]);
                ${'add_'.$namePageName} = $listAddAccess[$i];
                ${'edit_'.$namePageName} = $listEditAccess[$i];
                ${'delete_'.$namePageName} = $listDeleteAccess[$i];
                ${'download_'.$namePageName} = $listDownloadAccess[$i];
                ${'imagePreview_'.$namePageName} = $listImagePreviewAccess[$i];
            }
        }
    }
endif;

function format_hari_tanggal($waktu) {
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date('H:i:s', strtotime($waktu));

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}

function format_tanggal($waktu) {
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date('H:i:s', strtotime($waktu));

    //untuk menampilkan hari, tanggal bulan tahun
    return "$tanggal $bulan $tahun";
}

function format_bulanTahun($waktu) {
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));

    return "$bulan $tahun";
}
