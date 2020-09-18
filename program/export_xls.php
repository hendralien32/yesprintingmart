<?php
	session_start();
	require '../function.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Export Excel</title>
</head>
<body>
	<style type="text/css">
		@font-face {
			font-family: B612-Regular;
			src: url(../fonts/B612/B612-Regular.ttf);
		}

		@font-face {
			font-family: Karla-Regular;
			src: url(../fonts/Karla/Karla-Regular.ttf);
		}

		body {
			font-family: Karla-Regular, B612-Regular;
		}

		.a-right {
			text-align: right;
			padding-right: 0.4em;
		}

        .a-center {
			text-align: center;
		}

		table {
			width: 100%;
            margin: 20px auto;
		    border-collapse: collapse;
        }

		table th, table td {
            border: 1px solid #000;
            padding:10px 10px;
		}
	</style>

<?php if($_GET['type_export'] == "laporan_penjualan" ) : 
    $jenis_laporan = ($_GET['jenis_laporan'] != "") ? $_GET['jenis_laporan'] : "";
	$dari_bulan = ($_GET['dari_bulan'] != "") ? $_GET['dari_bulan'] : $months;
    $ke_bulan = ($_GET['ke_bulan'] != "") ? $_GET['ke_bulan'] : $_GET['dari_bulan'];

    if($jenis_laporan == "yescom" ) {
		$sub_table = "YES Communication";
		$add_where = "and penjualan.client = '1'";
	} else {
		$sub_table = "YES Printingmart";
		$add_where = "and penjualan.client != '1'";
    }
    
    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=Lap Penjualan $sub_table Tgl $dari_bulan-01 sd $ke_bulan-31 ". rand() .".xls");
    ?>

    <div id='laporan_header'>
        <div class='judul_laporan'>
            <h2>Laporan <?= $sub_table ?></h2>
        </div>
    </div>

    <div>
        <table border="1">
            <thead>
                <tr>
                    <th width="11%" style='background-color: #4683de; color: #ffffff;'>Tanggal</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Digital</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Kotak</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Potong</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Laminate</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Large Format</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Indoor</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Xuli</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Alat Tambahan</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Offset</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Design</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Lain</th>
                    <th width="5%" style='background-color: #4683de; color: #ffffff;'>Diskon</th>
                    <th width="7%" style='background-color: #4683de; color: #ffffff;'>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql =
                    "SELECT
                        LEFT(penjualan.waktu,10) as Tanggal,
                        SUM(penjualan.qty * penjualan.b_digital) as digital,
                        SUM(penjualan.qty * penjualan.b_kotak) as kotak,
                        SUM(penjualan.qty * penjualan.b_offset) as offset,
                        SUM(penjualan.qty * penjualan.b_large) as large,
                        SUM(penjualan.qty * penjualan.b_laminate) as laminate,
                        SUM(penjualan.qty * penjualan.b_potong) as potong,
                        SUM(penjualan.qty * penjualan.b_design) as design,
                        SUM(penjualan.qty * penjualan.discount) as diskon,
                        (SUM(penjualan.qty * penjualan.b_delivery) + sum(penjualan.qty * penjualan.b_lain)) as lain,
                        SUM(penjualan.qty * penjualan.b_xuli) as xuli,
                        SUM(penjualan.qty * penjualan.b_indoor) as indoor,
                        SUM(penjualan.qty * penjualan.b_xbanner) as alat_Tambahan
                    FROM
                        penjualan
                    WHERE
                        penjualan.cancel != 'Y' and
                        left(penjualan.waktu, 10)>='$dari_bulan-01' and 
                        left(penjualan.waktu, 10)<='$ke_bulan-31'
                        $add_where
                    GROUP BY
                        LEFT(penjualan.waktu,10)
                ";
                $result = $conn_OOP->query($sql);
                if ($result->num_rows > 0) :
                    while ($d = $result->fetch_assoc()) :
                        $total = ( $d['digital'] + $d['kotak'] + $d['potong'] + $d['laminate'] + $d['large'] + $d['indoor'] + $d['xuli'] + $d['alat_Tambahan'] + $d['offset'] + $d['design'] + $d['lain'] ) - $d['diskon'];
                        echo "
                            <tr>
                                <td><center><strong>" . date("d F Y", strtotime($d['Tanggal'])) . "</strong></center></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['digital']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['kotak']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['potong']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['laminate']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['large']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['indoor']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['xuli']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['alat_Tambahan']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['offset']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['design']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($d['lain']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px; color:red'><strong>". number_format($d['diskon']) ."</strong></td>
                                <td class='a-right' style='padding-right:10px;'><strong>". number_format($total) ."</strong></td>
                            </tr>
                        ";

                        $total_digital[]   = $d['digital'];
                        $total_kotak[]   = $d['kotak'];
                        $total_potong[]   = $d['potong'];
                        $total_laminate[]   = $d['laminate'];
                        $total_large[]   = $d['large'];
                        $total_indoor[]   = $d['indoor'];
                        $total_xuli[]   = $d['xuli'];
                        $total_alat_Tambahan[]   = $d['alat_Tambahan'];
                        $total_offset[]   = $d['offset'];
                        $total_design[]   = $d['design'];
                        $total_lain[]   = $d['lain'];
                        $total_diskon[]   = $d['diskon'];
                        $total_penjualan[]   = $total;
                        $Nilai_digital = number_format(array_sum($total_digital));
                        $Nilai_kotak = number_format(array_sum($total_kotak));
                        $Nilai_potong = number_format(array_sum($total_potong));
                        $Nilai_laminate = number_format(array_sum($total_laminate));
                        $Nilai_large = number_format(array_sum($total_large));
                        $Nilai_indoor = number_format(array_sum($total_indoor));
                        $Nilai_xuli = number_format(array_sum($total_xuli));
                        $Nilai_alat_Tambahan = number_format(array_sum($total_alat_Tambahan));
                        $Nilai_offset = number_format(array_sum($total_offset));
                        $Nilai_design = number_format(array_sum($total_design));
                        $Nilai_lain = number_format(array_sum($total_lain));
                        $Nilai_diskon = number_format(array_sum($total_diskon));
                        $Nilai_TotalPenjualan = number_format(array_sum($total_penjualan));
                    endwhile;

                    echo "
                        <tr>
                            <th style='background-color: #4683de; color: #ffffff;'>Total Penjualan</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_digital</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_kotak</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_potong</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_laminate</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_large</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_indoor</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_xuli</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_alat_Tambahan</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_offset</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_design</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_lain</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_diskon</th>
                            <th style='background-color: #4683de; color: #ffffff; padding-right:10px;' class='a-right'>$Nilai_TotalPenjualan</th>
                        </tr>
                    ";
                else :

                endif;
            ?>
            </tbody>
        </table>
    </div>
<?php elseif ($_GET['type_export'] == "laporan_Setoran_Bank" ) :
    $jenis_laporan = ($_GET['jenis_laporan'] != "") ? $_GET['jenis_laporan'] : "";
    $dari_tanggal = ($_GET['dari_tanggal'] != "") ? $_GET['dari_tanggal'] : $date;

    if($jenis_laporan == "Cash") :
        $title = "Cash";
        $add_where = "and (pelunasan.type_pem ='cash' or pelunasan.type_pem ='Cash' or pelunasan.type_pem ='DP' or pelunasan.type_pem ='dp' or pelunasan.type_pem ='Dp' or pelunasan.type_pem ='')";
    elseif($jenis_laporan == "CC") :
        $title = "Debit / Kredit / TRF";
        $add_where = "and (pelunasan.type_pem ='Kartu Kredit' or pelunasan.type_pem ='kartu kredit' or pelunasan.type_pem ='DP Kartu Kredit')";
    else :
        $title = "";
        $add_where = "";
    endif;

    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=Lap Pelunasan $title Tgl $dari_tanggal ". rand() .".xls");
    ?>

    <div id='laporan_header'>
        <div class='judul_laporan'>
            <h2>Laporan Pelunasan <?= $title ?></h2>
        </div>
        <div class='plugin_icon'>
            <!-- <span onclick='export_xls()'><i class="fas fa-file-excel"></i> Export</span> -->
            <!-- <span><i class="fas fa-print"></i> Print</span> -->
        </div>
    </div>

    <div>
        <table border="1">
            <thead>
                <tr>
                    <th width="10%" style='background-color: #4683de; color: #ffffff;'>Tanggal</th>
                    <th width="22%" style='background-color: #4683de; color: #ffffff;'>Tipe Pembayaran</th>
                    <th width="9%" style='background-color: #4683de; color: #ffffff;'>No Invoice</th>
                    <th width="15%" style='background-color: #4683de; color: #ffffff;'>Client</th>
                    <th width="11%" style='background-color: #4683de; color: #ffffff;'>Total Bayar</th>
                    <th width="11%" style='background-color: #4683de; color: #ffffff;'>Adjust</th>
                    <th width="11%" style='background-color: #4683de; color: #ffffff;'>Total Terima</th>
                    <th width="11%" style='background-color: #4683de; color: #ffffff;'>total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql =
                    "SELECT
                        penjualan.tanggal_INV as Tanggal,
                        GROUP_CONCAT(penjualan.no_invoice) as no_invoice,
                        GROUP_CONCAT(penjualan.nama_client) as nama_client,
                        pelunasan.type_pem,
                        pelunasan.jenis_kartu, 
                        pelunasan.nomor_kartu,
                        pelunasan.rekening_tujuan,
                        GROUP_CONCAT(pelunasan.tot_pay) as tot_pay,
                        GROUP_CONCAT(pelunasan.adj_pay) as adj_pay
                    FROM
                        pelunasan
                        LEFT JOIN
                        (
                            SELECT
                                customer.nama_client,
                                penjualan.no_invoice,
                                LEFT(penjualan.invoice_date,10) as tanggal_INV,
                                sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan
                            FROM
                                penjualan
                            LEFT JOIN 
                                (select customer.cid, customer.nama_client from customer) customer
                            ON
                                penjualan.client = customer.cid 
                            GROUP BY
                                penjualan.no_invoice
                        ) penjualan
                    ON
                        pelunasan.no_invoice = penjualan.no_invoice
                    WHERE
                        LEFT(pelunasan.pay_date,10) = '$dari_tanggal' 
                        $add_where
                    GROUP BY
                        penjualan.tanggal_INV
                    ORDER BY
                        penjualan.tanggal_INV
                ";
                $result = $conn_OOP->query($sql);
                if ($result->num_rows > 0) :
                    while ($d = $result->fetch_assoc()) :
                        $no_invoice   = explode(",", "$d[no_invoice]");
                        $nama_client   = explode(",", "$d[nama_client]");
                        $total_bayar   = explode(",", "$d[tot_pay]");
                        $total_adj_pay   = explode(",", "$d[adj_pay]");
                        $x = array_sum($total_bayar);
                        $y = array_sum($total_adj_pay);
                        $count = count($no_invoice);

                        if($d['type_pem']==='cash' or $d['type_pem']==='Cash') {
                            $type_pem="$d[type_pem]"; 
                        } elseif($d['type_pem']=="DP") {
                            $type_pem="Down Payment";
                        } elseif($d['type_pem']=="Kartu Kredit" or $d['type_pem']=="DP Kartu Kredit") {
                            $type_pem = "Debit / Kredit / TRF";
                            if($d['jenis_kartu'] != "") :
                                $type_pem .=  " - $d[jenis_kartu]";
                            else : 
                                $type_pem .=  "";
                            endif;

                            if($d['nomor_kartu'] != "") :
                                $type_pem .=  " ( $d[nomor_kartu] )";
                            else : 
                                $type_pem .=  "";
                            endif;

                            if($d['rekening_tujuan'] != "") :
                                $type_pem .=  " --> $d[rekening_tujuan] ( A/N Muliadi )";
                            else : 
                                $type_pem .=  "";
                            endif;
                        } else { 
                            $type_pem="- - -"; 
                        }

                        echo "
                            <tr>
                                <td rowspan='$count'><strong>" . date("d F Y", strtotime($d['Tanggal'])) . "</strong></td> 
                                <td rowspan='$count'><strong>$type_pem</strong></td>        
                                <td class='a-center'><strong>$no_invoice[0]</strong></td>         
                                <td><strong>$nama_client[0]</strong></td>         
                                <td class='a-right'><strong>" . number_format($total_bayar[0]) . "</strong></td>         
                                <td class='a-right'><strong>" . number_format($total_adj_pay[0]) . "</strong></td>         
                                <td class='a-right'><strong>" . number_format($total_bayar[0] - $total_adj_pay[0]) . "</strong></td>   
                                <td rowspan='$count' class='a-right'><strong>" . number_format($x - $y) . "</strong></td>          
                            </tr>          
                        ";

                        for ($i = 1; $i < $count; $i++) :
                            echo
                                "<tr>
                                    <td class='a-center'><strong>$no_invoice[$i]</strong></td>  
                                    <td><strong>$nama_client[$i]</strong></td>  
                                    <td class='a-right'><strong>" . number_format($total_bayar[$i]) . "</strong></td>     
                                    <td class='a-right'><strong>" . number_format($total_adj_pay[$i]) . "</strong></td>  
                                    <td class='a-right'><strong>" . number_format($total_bayar[$i] - $total_adj_pay[$i]) . "</strong></td>    
                                </tr>
                                ";
                        endfor;
                        
                        $Xtotal_bayar[]   = array_sum($total_bayar);
                        $Xtotal_adj_pay[]   = array_sum($total_adj_pay);

                        $a = array_sum($Xtotal_bayar);
                        $b = array_sum($Xtotal_adj_pay);
                    endwhile;
                else :
                    $a = 0;
                    $b = 0;

                    echo "
                        <tr>
                            <td colspan='8'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
                endif;
                ?>
                <tr>
                    <th colspan="4" style='background-color: #4683de; color: #ffffff;'>Total Pelunasan</th>
                    <th style='text-align:right; background-color: #4683de; color: #ffffff;'><?= number_format($a); ?></th>
                    <th style='text-align:right; background-color: #4683de; color: #ffffff;'><?= number_format($b); ?></th>
                    <th style='text-align:right; background-color: #4683de; color: #ffffff;'><?= number_format($a - $b); ?></th>
                    <th style='text-align:right; background-color: #4683de; color: #ffffff;'><?= number_format($a - $b); ?></th>
                </tr>
            </tbody>
        </table>
    </div>
<?php elseif ($_GET['type_export'] == "xxxx" ) : ?>

<?php else : ?>

<?php endif; ?>
</body>
</html>
