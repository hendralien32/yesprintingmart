<?php
session_start();
require '../function.php';

$ID_Order = $_GET['oid'];
$sql =
    "SELECT 
        LEFT(penjualan.waktu,10) as waktu,
        penjualan.no_invoice,
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
        (CASE
            WHEN penjualan.warna_cetak = 'FC' THEN 'FULLCOLOR'
            WHEN penjualan.warna_cetak = 'BW' THEN 'GREYSCALE'
            ELSE '- - -'
        END) as Warna_Cetak,
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
        (CASE
            WHEN setter.nama != '' THEN setter.nama
            ELSE '- - -'
        END) as nama_setter
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

    if ($d['client_yes'] != "") {
        $client_yes = " - <strong style='color:#353942'>$d[client_yes]</strong>";
    } else {
        $client_yes = "";
    }
    if ($d['id_yes'] != "0") {
        $id_yes = " - <strong style='color:#353942'>$d[id_yes]</strong>";
    } else {
        $id_yes = "";
    }
    if ($d['so_yes'] != "0") {
        $id_yes .= "<strong style='color:#353942'> / $d[so_yes]</strong>";
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
                <img src='../images/Logo YES BON - BW.png'>
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
                        <td><?= date("d F Y", strtotime($d['waktu'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id='container_2'>
            <div id="left">
                <table>
                    <tr>
                        <td style='width:130px'><strong>ID/ SO</strong></td>
                        <td><?= $_GET['oid'] . $id_yes ?></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Client</strong></td>
                        <td><span class='nama_client'><?= $d['nama_client'] . $client_yes ?></span></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Desciption</strong></td>
                        <td><?= $d['description'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Kertas</strong></td>
                        <td><?= $d['bahan'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Sisi</strong></td>
                        <td><?= $d['sisi'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Qty</strong></td>
                        <td><?= $d['qty'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Warna</strong></td>
                        <td><strong style='color:#353942'><?= $d['Warna_Cetak'] ?></strong></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Alat Tambahan</strong></td>
                        <td><strong style='color:#353942'><?= $d['alat_tambahan'] ?></strong></td>
                    </tr>
                    <tr>
                        <td style='width:130px'><strong>Laminating</strong></td>
                        <td><strong style='color:#353942'><?= $d['laminating'] ?></strong></td>
                    </tr>
                </table>
            </div>
            <div id="right">
                <table>
                    <tr>
                        <td style='width:135px'><strong>No Invoice</strong></td>
                        <td><?= $d['no_invoice'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:135px'><strong>Nama Setter</strong></td>
                        <td><?= $d['nama_setter'] ?></td>
                    </tr>
                    <tr>
                        <td style='width:135px'><strong>Finishing</strong></td>
                        <?php
                        $array_kode = array(
                            "potong",
                            "potong_gantung",
                            "pon",
                            "perporasi",
                            "CuttingSticker",
                            "Hekter_Tengah",
                            "Blok",
                            "Spiral",
                            "Proffing",
                            "ditunggu"
                        );
                        foreach ($array_kode as $kode) :
                            if ($d[$kode] == "Y") : ${'check_' . $kode} = "<i class='fad fa-check-square'></i>";
                            else : ${'check_' . $kode} = "<i class='fas fa-square'></i>";
                            endif;
                        endforeach;
                        ?>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_potong; ?>
                                <label class='checkbox-fa' for='Ptg_Pts'> Ptg Putus </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_potong_gantung; ?>
                                <label class='checkbox-fa' for='Ptg_Gantung'> Ptg Gantung </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_pon; ?>
                                <label class='checkbox-fa' for='Pon_Garis'> Pon Garis </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_perporasi; ?>
                                <label class='checkbox-fa' for='Perporasi'> Perporasi </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="contact100-form-checkbox">
                                <?= $check_CuttingSticker; ?>
                                <label class='checkbox-fa' for='CuttingSticker'> Cutting Sticker </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Hekter_Tengah; ?>
                                <label class='checkbox-fa' for='Hekter_Tengah'> Hekter Tengah </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Blok; ?>
                                <label class='checkbox-fa' for='Blok'> Blok </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Spiral; ?>
                                <label class='checkbox-fa' for='Spiral'> Ring Spiral </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:135px'><strong>Permintaan</strong></td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_Proffing; ?>
                                <label class='checkbox-fa' for='proffing'> Proffing</label>
                            </div>
                        </td>
                        <td>
                            <div class='contact100-form-checkbox'>
                                <?= $check_ditunggu; ?>
                                <label class='checkbox-fa' for='Ditunggu'> Ditunggu </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if ($d['keterangan'] != "") : ?>
            <div id='container_3'>
                <p>
                    <strong>Note :</strong><br>
                    <span><?= $d['keterangan'] ?></span>
                </p>
            </div>
        <?php else : endif; ?>
        <!-- <div id='container_4'>
            <p>
                <strong>Paraf Operator</strong>
            </p>
            </di>
        </div> -->
    <?php
else :
    header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
    exit();
endif;
    ?>