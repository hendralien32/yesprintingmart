<?php
session_start();
require_once "../../function.php";

$daftar_hari = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

if ($_POST['data'] != "" and $_POST['date'] == "") :
    $add_where = "( penjualan.client LIKE '%$_POST[data]%' or digital_printing.oid LIKE '%$_POST[data]%' or penjualan.client_yes LIKE '%$_POST[data]%' or penjualan.id_yes LIKE '%$_POST[data]%')";
elseif ($_POST['date'] != "" and $_POST['data'] == "") :
    $add_where = "LEFT(digital_printing.tgl_cetak,10) = '$_POST[date]'";
else :
    $add_where = "LEFT(digital_printing.tgl_cetak,10) = '$date'";
endif;

   
if ($_POST['date'] != '') :
    $tanggal_total = date("d F Y", strtotime($_POST['date']));
    $namahari = date('l', strtotime($_POST['date']));
else :
    $tanggal_total = date("d F Y", strtotime($date));
    $namahari = date('l', strtotime($date));
endif;

$cari_keyword = $_POST['data'];
$bold_cari_keyword = "<strong style='text-decoration:underline'>" . $_POST['data'] . "</strong>";
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>

<table>
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="6%">ID</th>
            <th width="14%">Client</th>
            <th width="32%">Description</th>
            <th width="3%">Warna</th>
            <th width="13%">Bahan</th>
            <th width="3%">Sisi</th>
            <th width="8%">Qty (Click)</th>
            <th width="8%">Error (Click)</th>
            <th width="6%">Jammed</th>
            <th width="5%">ETC</th>
        </tr>
        <?php
            $sql =
                "SELECT
                    digital_printing.did,
                    digital_printing.oid,
                    digital_printing.maintanance,
                    digital_printing.mesin,
                    penjualan.id_yes,
                    penjualan.so_yes,
                    penjualan.id_client,
                    penjualan.client_yes,
                    penjualan.description,
                    penjualan.client,
                    (CASE
                        WHEN digital_printing.sisi = '2' THEN digital_printing.qty_cetak * 2
                        ELSE digital_printing.qty_cetak
                    END) as click_cetak,
                    (CASE
                        WHEN digital_printing.sisi = '2' THEN digital_printing.error * 2
                        ELSE digital_printing.error
                    END) as click_error,
                    (CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                        ELSE ROUND(digital_printing.qty_cetak / 2)
                    END) as qty_cetak,
                    (CASE
                        WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                        WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                        ELSE ROUND(digital_printing.error / 2)
                    END) as error,
                    digital_printing.qty_etc,
                    digital_printing.color,
                    digital_printing.jam,
                    (CASE
                        WHEN digital_printing.sisi = '1' THEN 'satu'
                        WHEN digital_printing.sisi = '2' THEN 'dua'
                        ELSE ''
                    END) as css_sisi,
                    digital_printing.sisi,
                    (CASE
                        WHEN barang.nama_barang != '' THEN barang.nama_barang
                        WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
                        ELSE '- - -'
                    END) as nama_barang
                FROM
                    digital_printing
                LEFT JOIN
                    (SELECT
                        barang.id_barang,
                        barang.nama_barang
                    FROM
                        barang
                    WHERE
                        barang.jenis_barang = 'KRTS'
                    ) barang
                ON 
                    barang.id_barang = digital_printing.id_bahan
                LEFT JOIN
                    (SELECT
                        barang.kode_barang,
                        barang.nama_barang
                    FROM
                        barang
                    WHERE
                        barang.jenis_barang = 'KRTS'
                    ) barang_Kode
                ON 
                    barang_Kode.kode_barang = digital_printing.kode_bahan
                LEFT JOIN 
                    (SELECT
                        penjualan.oid,
                        penjualan.client as id_client,
                        customer.nama_client as client,
                        penjualan.id_yes,
                        penjualan.so_yes,
                        penjualan.client_yes,
                        penjualan.description
                    FROM
                        penjualan
                    LEFT JOIN 
                        (SELECT 
                            customer.cid, 
                            customer.nama_client 
                        FROM 
                            customer
                    ) customer
                    ON customer.cid = penjualan.client
                ) penjualan
                ON penjualan.oid = digital_printing.oid
                WHERE
                    $add_where
                ORDER BY
                    digital_printing.tgl_cetak
                DESC
            ";

            $n = 0;
            $result = $conn_OOP->query($sql);

            $jumlahQry = $result->num_rows;

            if ($result->num_rows > 0) :
                while ($d = $result->fetch_assoc()) :
                    $n++;

                    if($d['maintanance']=="Y") {
                        $icon_maintanance = "<strong style='color:#f1592a'><i class='fas fa-wrench'></i> Maintanance Mesin $d[mesin]</strong>";
                        $edit = "LaodSubForm(\"maintenance_DP\", \"" . $d['did'] . "\")";
                    } else {
                        $icon_maintanance = ""; 
                        $edit = "LaodForm(\"DigitalPrinting_Update\", \"" . $d['did'] . "\")";
                    }

                    if ($d['id_client'] == "1") :
                        $detail_yes = "<strong>";
                        if ($d['id_yes'] != "0") :
                            $detail_yes .= str_ireplace($cari_keyword, $bold_cari_keyword, $d['id_yes']);
                        else :
                            $detail_yes .= "";
                        endif;
                        if ($d['so_yes'] != "0") :
                            $detail_yes .= " / " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['so_yes']) . "";
                        else :
                            $detail_yes .= "";
                        endif;
                        $detail_yes .= " - <span style='color:#f1592a'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['client_yes']) . "</span> </strong>";
                    else :
                        $detail_yes = "";
                    endif;

                    echo "
                        <tr>
                            <td>$n</td>
                            <td class='a-center'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['oid']) . "</td>
                            <td onclick='" . $edit . "' class='pointer'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['client']) . "</td>
                            <td onclick='" . $edit . "' class='pointer'>$detail_yes " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['description']) . " $icon_maintanance</td>
                            <td class='a-center'><span class='$d[color] KodeProject'>$d[color]</span></td>
                            <td>$d[nama_barang]</td>
                            <td class='a-center'><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></td>
                            <td class='a-right'><strong>" . number_format($d['qty_cetak'])  . " <span style='color:#f1592a'>(" . number_format($d['click_cetak']) . ") </span>". " </strong> Lbr</td>
                            <td class='a-right'><strong>" . number_format($d['error']) . " <span style='color:#f1592a'>(" . number_format($d['click_error']) . ") </span>" . "</strong> Lbr</td>
                            <td class='a-right'><strong>" . number_format($d['jam']) . "</strong> Lbr</td>
                            <td class='a-right'><strong>" . number_format($d['qty_etc']) . "</strong> Pcs</td>
                        </tr>
                    ";
                    
                    $total_etc[]   = $d['qty_etc'];
                    $total_jam[]   = $d['jam'];
                    $total_error[]   = $d['error'];
                    $total_qty_cetak[]   = $d['qty_cetak'];
                    $total_click_cetak[]   = $d['click_cetak'];
                    $total_click_error[]   = $d['click_error'];
                    $Nilai_total_etc = array_sum($total_etc);
                    $Nilai_total_jam = array_sum($total_jam);
                    $Nilai_total_click_cetak = array_sum($total_click_cetak);
                    $Nilai_total_click_error = array_sum($total_click_error);
                    $Nilai_total_error = array_sum($total_error);
                    $Nilai_total_qty_cetak = array_sum($total_qty_cetak);
                endwhile;

                
                if($Nilai_total_click_cetak == 0 || $Nilai_total_click_error == 0) :
                    $persen_error=0;
                    $persen_cetak=0;
                else :
                    $persen_error=round(($Nilai_total_click_error/$Nilai_total_click_cetak)*100,2);
                    $persen_cetak=100-$persen_error;
                endif;
                
                
                echo "
                    <tr>
                        <th colspan='7'>Total Cetakan <span style='color:yellow'>$daftar_hari[$namahari], $tanggal_total</span></th>
                        <th class='a-left' style='text-align:right; padding-right: 0.4em;'>
                            " . number_format($Nilai_total_qty_cetak) . " (". number_format($Nilai_total_click_cetak) .") Lbr<br>
                            <span style='color:yellow'>( $persen_cetak % )</span>
                        </th>
                        <th class='a-left' style='text-align:right; padding-right: 0.4em;'>
                            " . number_format($Nilai_total_error) . " (". number_format($Nilai_total_click_error) .") Lbr<br>
                            <span style='color:yellow'>( $persen_error % )</span>
                        </th>
                        <th class='a-left' style='text-align:right; vertical-align:top; padding-right: 0.4em;'>" . number_format($Nilai_total_jam) . " Lbr</th>
                        <th class='a-left' style='text-align:right; vertical-align:top; padding-right: 0.4em;'>" . number_format($Nilai_total_etc) . " Pcs</th>
                    </tr>
                ";
            else :
                echo "
                    <tr>
                        <td colspan='11'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                    </tr>
                ";
            endif;
        ?>
    </thead>
    <tbody>
    </tbody>
</table>
