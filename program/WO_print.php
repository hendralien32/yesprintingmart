<?php
session_start();
require '../function.php';

$ID_Order = $_GET['oid'];
$sql =
    "SELECT 
        penjualan.description,
        (CASE
            WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            ELSE penjualan.ukuran
        END) as ukuran,
        CONCAT(penjualan.sisi, ' Sisi') as sisi,
        penjualan.sisi as form_sisi,
        (CASE
            WHEN barang.id_barang > 0 THEN barang.nama_barang
            ELSE penjualan.bahan
        END) as bahan,
        customer.nama_client,
        penjualan.keterangan,
        penjualan.client_yes,
        penjualan.id_yes,
        penjualan.so_yes,
        penjualan.warna_cetak as Warna_Cetak,
        (CASE
            WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
            WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
            WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
            WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
            WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
            WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
            WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
            WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
            ELSE '- - -'
        END) as laminating,
        (CASE
            WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
            WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
            WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
            WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
            WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
            WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
            WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
            ELSE '- - -'
        END) as alat_tambahan,
        (CASE
            WHEN penjualan.alat_tambahan = 'KotakNC' THEN '31'
            ELSE '0'
        END) as ID_AlatTambahan,
        CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
        penjualan.potong,
        penjualan.potong_gantung,
        penjualan.pon,
        penjualan.perporasi,
        penjualan.CuttingSticker,
        penjualan.Hekter_Tengah,
        penjualan.Blok,
        penjualan.Spiral,
        penjualan.Proffing,
        penjualan.status,
        penjualan.ditunggu,
        penjualan.satuan,
        penjualan.qty as Qty_Order,
        setter.nama
    FROM 
        penjualan
    LEFT JOIN 
        (select customer.cid, customer.nama_client from customer) customer
    ON
        penjualan.client = customer.cid   
    LEFT JOIN 
        (select pm_user.uid, pm_user.nama from pm_user) setter
    ON
        penjualan.setter = setter.uid  
    LEFT JOIN 
        (select barang.id_barang, barang.nama_barang from barang) barang
    ON
        penjualan.ID_Bahan = barang.id_barang  
    WHERE
        penjualan.oid = '$ID_Order'
";

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    // output data of each row
    $d = $result->fetch_assoc();

    if ($d['status'] == 'selesai') {
        $display_form = "none";
    } else {
        $display_form = "";
    }

    if ($d['satuan'] == "Kotak" || $d['satuan'] == "KOTAK" || $d['satuan'] == "kotak") {
        $Qty_Val = $d['Qty_Order'] * 4;
    } else {
        $Qty_Val = $d['Qty_Order'];
    }

    if ($d['client_yes'] != "") {
        $client_yes = " - <strong style='color:#f1592a'>$d[client_yes]</strong>";
    } else {
        $client_yes = "";
    }
    if ($d['id_yes'] != "0") {
        $id_yes = " - <strong style='color:#f1592a'>$d[id_yes]</strong>";
    } else {
        $id_yes = "";
    }
    if ($d['so_yes'] != "0") {
        $id_yes .= "<strong style='color:#f1592a'> / $d[so_yes]</strong>";
    } else {
        $id_yes .= "";
    }
else :
    $client_yes = "";
    $id_yes = "";
endif;

?>

<title>No. ID Order <?= $_GET['oid']; ?></title>
<link rel="icon" type="image/png" href="../images/icons/favicon.png" />
<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Maven+Pro&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/printWO_style.css">

<!--===============================================================================================-->

<script src="js/print.js"></script>
<script src="js/jquery-3.4.1.min.js"></script>
<!-- jQuery UI library -->
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.min.js"></script>

<link rel="stylesheet" type="text/css" src="css/Font-Awesome-master/css/all.css">
<script defer src="css/Font-Awesome-master/js/all.js"></script>
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<!--===============================================================================================-->

<?php
if (isset($_SESSION["login"])) :
?>
    <div id="container">
        <div id='container_1'>
            <div class='left'>
                <img src='../images/Logo YES BON.png'>
            </div>
            <div class='middle'>
                <div class='status_lunas'>

                </div>
                <div class='Tgl_lunas'>

                </div>
            </div>
            <div class='right'>
                <h3>WORK ORDER</h3>
                <table>
                    <tr>
                        <th width="4%">#OID</th>
                        <th width="4%">DATE</th>
                    </tr>
                    <tr>
                        <td><?= $_GET['oid']; ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id='container_2'>
            <div id="left">
                <table>
                    <tr>
                        <td><strong>Operator</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Setter</strong></td>
                        <td><?= $d['nama'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>ID/ SO</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Client</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Desciption</strong></td>
                        <td>B</td>
                    </tr>

                </table>
            </div>
            <div id="right">
                <table>
                    <tr>
                        <td><strong>Laminating</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Alat Tambahan</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Finishing</strong></td>
                        <td>B</td>
                    </tr>
                    <tr>
                        <td><strong>Permintaan Order</strong></td>
                        <td>B</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php
else :
    header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
    exit();
endif;
?>