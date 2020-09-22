<?php
session_start();
require '../function.php';

$type = $_GET['type'];
$no_invoice = "";

if ($_GET['no_invoice'] != "") {
    $no_invoice .= "No. Invoice : #$_GET[no_invoice]";
} else {
    $no_invoice .= "";
}

$sql =
    "SELECT
        penjualan.no_invoice,
        penjualan.pembayaran,
        LEFT( penjualan.invoice_date, 10 ) as tanggal,
        customer.nama_client,
        GROUP_CONCAT(penjualan.oid) as oid,
        GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
        GROUP_CONCAT((CASE
            WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            ELSE ''
        END)) as ukuran,
        GROUP_CONCAT((CASE
            WHEN barang.id_barang > 0 THEN barang.nama_barang
            ELSE penjualan.bahan
        END)) as bahan,
        GROUP_CONCAT(CONCAT('<b>',penjualan.qty, '</b> ' ,penjualan.satuan)) as qty,
        GROUP_CONCAT((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)) as harga_satuan,
        GROUP_CONCAT(penjualan.discount) as discount,
        GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)*penjualan.qty)) as total,
        sum((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)*penjualan.qty) as Total_keseluruhan,
        sum(penjualan.discount*penjualan.qty) as total_discount,
        pelunasan.total_bayar,
        pelunasan.Tgl_Pelunasan,
        (CASE
            WHEN setter.uid > 0 then setter.nama
            ELSE '-'
        END) as Nama_Setter,
        (CASE
            WHEN sales.uid > 0 then sales.nama
            ELSE '-'
        END) as Nama_Sales
    from
        penjualan
    LEFT JOIN 
        (select customer.cid, customer.nama_client from customer) customer
    ON
        penjualan.client = customer.cid  
    LEFT JOIN 
        (select barang.id_barang, barang.nama_barang from barang) barang
    ON
        penjualan.ID_Bahan = barang.id_barang  
    LEFT JOIN 
        (select pm_user.uid, pm_user.nama from pm_user) setter
    ON
        penjualan.setter = setter.uid
        LEFT JOIN 
    (select pm_user.uid, pm_user.nama from pm_user) sales
    ON
        penjualan.sales = sales.uid
    LEFT JOIN 
        (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar, LEFT( pelunasan.pay_date, 10 ) as Tgl_Pelunasan from pelunasan group by pelunasan.no_invoice) pelunasan
    ON
        penjualan.no_invoice = pelunasan.no_invoice  
    where
        penjualan.no_invoice = '$_GET[no_invoice]'
    GROUP BY
        penjualan.no_invoice
    ";

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    while ($d = $result->fetch_assoc()) :
        $oid = explode(",", "$d[oid]");
        $description = explode("*_*", "$d[description]");
        $ukuran = explode(",", "$d[ukuran]");
        $bahan = explode(",", "$d[bahan]");
        $qty = explode(",", "$d[qty]");
        $discount = explode(",", "$d[discount]");
        $harga_satuan = explode(",", "$d[harga_satuan]");
        $total = explode(",", "$d[total]");
        $data_No_Invoice = "$d[no_invoice]";
        $data_InvoiceDate = "$d[tanggal]";
        $data_total = "$d[Total_keseluruhan]";
        $data_Nama_Setter = "$d[Nama_Setter]";
        $data_Nama_Sales = "$d[Nama_Sales]";
        $data_nama_client = "$d[nama_client]";

        if ($d['pembayaran'] == "lunas") :
            $check_Lunas = "LUNAS";
            $Tgl_Lunas = "" . date("d M Y", strtotime($d['Tgl_Pelunasan'])) . "";
        elseif ($d['Total_keseluruhan'] == $d['total_bayar']) :
            $check_Lunas = "LUNAS";
            $Tgl_Lunas = "" . date("d M Y", strtotime($d['Tgl_Pelunasan'])) . "";
        else :
            $check_Lunas = "BELUM LUNAS";
            $Tgl_Lunas = "";
        endif;

        $diskon = "$d[total_discount]";
        $total_invoice = $data_total - $diskon;
        $count_oid = count($oid);
    endwhile;
endif;

?>

<title><?= $no_invoice; ?></title>
<link rel="icon" type="image/png" href="../images/icons/favicon.png" />
<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Maven+Pro&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/print_style.css">

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
    if ($type == "sales_invoice") : ?>
        <div id='container'>
            <div id='container_1'>
                <div class='left'>
                    <img src='../images/Logo YES BON.png'>
                </div>
                <div class='middle'>
                    <div class='status_lunas'>
                        <?= $check_Lunas; ?>
                    </div>
                    <div class='Tgl_lunas'>
                        <?= $Tgl_Lunas; ?>
                    </div>
                </div>
                <div class='right'>
                    <h3>INVOICE</h3>
                    <table>
                        <tr>
                            <th width="4%">INVOICE#</th>
                            <th width="4%">DATE</th>
                        </tr>
                        <tr>
                            <td><?= $data_No_Invoice; ?></td>
                            <td><?= $data_InvoiceDate; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id='container_2'>
                <div id="left">
                    <b>Client :</b> <?= $data_nama_client; ?>
                </div>
                <div id="right">
                    <b>Setter :</b> <?= $data_Nama_Setter; ?> | <b>Sales :</b> <?= $data_Nama_Sales; ?>
                </div>
            </div>
            <div id='container_3'>
                <table class='table_print'>
                    <tr>
                        <th width="1%">No.</th>
                        <th width="61%">Deskripsi</th>
                        <th width="12%">Qty</th>
                        <th width="12%">@ Harga</th>
                        <th width="14%">Harga</th>
                    </tr>
                    <?php
                    for ($i = 0; $i < $count_oid; $i++) :
                        $no = $i + 1;
                        echo "
                                <tr>
                                    <td><center>$no</center></td>
                                    <td>$description[$i] $ukuran[$i]</td>
                                    <td>$qty[$i]</td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                                ";
                    endfor;
                    ?>
                    <tr>
                        <th colspan='4' style='text-align:right'>Substotal</th>
                        <td style='text-align:right'><b><?= number_format($data_total); ?></b></td>
                    </tr>
                    <tr>
                        <th colspan='4' style='text-align:right'>Diskon</th>
                        <td style='text-align:right'><b><?= number_format($diskon); ?></b></td>
                    </tr>
                    <tr>
                        <th colspan='4' style='text-align:right'>Invoice Total</th>
                        <td style='text-align:right'><b><?= number_format($total_invoice); ?></b></td>
                    </tr>
                </table>
            </div>
        </div>

<?php
    endif;
else :
    header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
    exit();
endif;
?>