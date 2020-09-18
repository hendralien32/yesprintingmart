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
	header("Content-Disposition: attachment; filename=Laporan $sub_table Tanggal $dari_bulan-01 sd $ke_bulan-31 ". rand() .".xls");
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
<?php else : ?>

<?php endif; ?>
</body>
</html>