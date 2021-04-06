<?php
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

$arrayNo = ['N','N','N','N','N','N','N'];

$absensi = isset($_SESSION['aksesAbsensi']) ? explode("," , $_SESSION['aksesAbsensi']) : $arrayNo;
$database = isset($_SESSION['aksesDb']) ? explode("," , $_SESSION['aksesDb']) : $arrayNo;
$SalesOrder = isset($_SESSION['aksesSalesOrder']) ? explode("," , $_SESSION['aksesSalesOrder']) : $arrayNo;
$salesOrderYescom = isset($_SESSION['aksesSalesOrderYescom']) ? explode("," , $_SESSION['aksesSalesOrderYescom']) : $arrayNo;
$largeFormat = isset($_SESSION['aksesLF']) ? explode("," , $_SESSION['aksesLF']) : $arrayNo;
$digitalPrinting = isset($_SESSION['aksesDP']) ? explode("," , $_SESSION['aksesDP']) : $arrayNo;
$laporan = isset($_SESSION['aksesLaporan']) ? explode("," , $_SESSION['aksesLaporan']) : $arrayNo;

$aksesAdd       =   isset($_SESSION['aksesAdd'])
                        ? explode("," , $_SESSION['aksesAbsensi'])
                        : $arrayNo;
$aksesEdit      =   isset($_SESSION['aksesEdit'])
                        ? explode("," , $_SESSION['aksesAbsensi'])
                        : $arrayNo;
$aksesDelete    =   isset($_SESSION['aksesDelete'])
                        ? explode("," , $_SESSION['aksesAbsensi'])
                        : $arrayNo;
$aksesLog       =   isset($_SESSION['aksesLog']) 
                        ? explode("," , $_SESSION['aksesLog'])
                        : $arrayNo;
$aksesDownlaod  =   isset($_SESSION['aksesDownload']) 
                        ? explode("," , $_SESSION['aksesDownload']) 
                        : $arrayNo;

$aksesAddAbsensi        = $aksesAdd[0];
$aksesEditAbsensi       = $aksesEdit[0];
$aksesDeleteAbsensi     = $aksesDelete[0];
$aksesLogAbsensi        = $aksesLog[0];
$aksesDownlaodAbsensi   = $aksesDownlaod[0];

$aksesAddDB        = $aksesAdd[1];
$aksesEditDB       = $aksesEdit[1];
$aksesDeleteDB     = $aksesDelete[1];
$aksesLogDB        = $aksesLog[1];
$aksesDownlaodDB   = $aksesDownlaod[1];

$listAbsensi = array("","Absensi List", "Absensi Harian","Absensi Rekapan");
$listDb = array("","Database User","Database Client","Database Supplier","Database Barang","Database Pricelist");
$listSalesOrder = array("","Sales Invoice Penjualan","Pelunasan Invoice","List Pelunasan Invoice");
$listSalesOrderYescom = array("","Sales Order","Sales Invoice","WO List");
$listlargeFormat = array("","Order List","Pemotongan Stock","Stock Bahan", "List Pemesanan Bahan");
$listdigitalPrinting = array("","Order List","Pemotongan Stock","Laporan Harian Konika","List Pemasukan Kertas","Stock Kertas");
$listlaporan = array("","Penjualan","Setoran Bank","Harian Konika");


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
