<?php
session_start();
require_once "../../function.php";

$jenis_laporan = ($_POST['jenis_laporan'] != "") ? $_POST['jenis_laporan'] : "";
$dari_bulan = ($_POST['dari_bulan'] != "") ? $_POST['dari_bulan'] : $monts;
$ke_bulan = ($_POST['ke_bulan'] != "") ? $_POST['ke_bulan'] : $_POST['dari_bulan'];

if($jenis_laporan == "yescom" ) {
    $sub_table = "YES Communication";
    $add_where = "and penjualan.client = '1'";
} else {
    $sub_table = "YES Printingmart";
    $add_where = "and penjualan.client != '1'";
}
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<div id='laporan_header'>
    <div class='judul_laporan'>
        <h2>Laporan <?= $sub_table ?></h2>
    </div>
    <div class='plugin_icon'>
        <span onclick='export_xls()'><i class="fas fa-file-excel"></i> Export</span>
        <!-- <span><i class="fas fa-print"></i> Print</span> -->
    </div>
</div>
<div>
    <table>
        <thead>
            <tr>
                <th width="11%">Tanggal</th>
                <th width="7%">Digital</th>
                <th width="7%">Kotak</th>
                <th width="7%">Potong</th>
                <th width="7%">Laminate</th>
                <th width="7%">Large Format</th>
                <th width="7%">Indoor</th>
                <th width="7%">Xuli</th>
                <th width="7%">Alat Tambahan</th>
                <th width="7%">Offset</th>
                <th width="7%">Design</th>
                <th width="7%">Lain</th>
                <th width="5%">Diskon</th>
                <th width="7%">Total</th>
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
                            <td><strong>" . date("d F Y", strtotime($d['Tanggal'])) . "</strong></td>
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
                        <th>Total Penjualan</th>
                        <th>$Nilai_digital</th>
                        <th>$Nilai_kotak</th>
                        <th>$Nilai_potong</th>
                        <th>$Nilai_laminate</th>
                        <th>$Nilai_large</th>
                        <th>$Nilai_indoor</th>
                        <th>$Nilai_xuli</th>
                        <th>$Nilai_alat_Tambahan</th>
                        <th>$Nilai_offset</th>
                        <th>$Nilai_design</th>
                        <th>$Nilai_lain</th>
                        <th>$Nilai_diskon</th>
                        <th>$Nilai_TotalPenjualan</th>
                    </tr>
                ";
            else :
                echo "
                    <tr>
                        <td colspan='14'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                    </tr>
                ";
            endif;
        ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?>