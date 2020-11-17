<?php
session_start();
require_once '../../function.php';

if ($_POST['search'] != "") {
    $add_where = "and ( penjualan.description LIKE '%$_POST[search]%' or penjualan.client_yes LIKE '%$_POST[search]%' or penjualan.id_yes LIKE '%$_POST[search]%' or penjualan.so_yes LIKE '%$_POST[search]%' or penjualan.oid LIKE '%$_POST[search]%' or penjualan.no_invoice LIKE '%$_POST[search]%')";
    $tanggal_total = "";
} else {
    if ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( penjualan.waktu, 10 )>='$_POST[Dari_Tanggal]' and LEFT( penjualan.waktu, 10 )<='$_POST[Ke_Tanggal]')";
        $tanggal_total = date("d M Y", strtotime($_POST['Dari_Tanggal'])) . " s/d " . date("d M Y", strtotime($_POST['Ke_Tanggal']));
    elseif ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] == "") :
        $add_where = "and (LEFT( penjualan.waktu, 10 )='$_POST[Dari_Tanggal]')";
        $tanggal_total = date("d M Y", strtotime($_POST['Dari_Tanggal']));
    elseif ($_POST['Dari_Tanggal'] == "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( penjualan.waktu, 10 )='$_POST[Ke_Tanggal]')";
        $tanggal_total = date("d M Y", strtotime($_POST['Ke_Tanggal']));
    else :
        $add_where = "";
        $tanggal_total = date("d M Y", strtotime($date));
    endif;
}

$cari_keyword = $_POST['search'];
$bold_cari_keyword = "<strong style='text-decoration:underline'>" . $_POST['search'] . "</strong>";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="2%">#</th>
            <th width="7%">Tanggal</th>
            <th width="5%">OID</th>
            <th width="5%">Invoice</th>
            <th width="3%">K</th>
            <th width="36%">Client - Description</th>
            <th width="5%">I</th>
            <th width="3%">S</th>
            <th width="11%">Bahan</th>
            <th width="7%">Qty</th>
            <th width="6%">@ Harga</th>
            <th width="6%">Total</th>
            <th width="4%"></th>
        </tr>
    </tbody>
    <?php
    $sql =
        "SELECT
            penjualan.oid,
            (CASE
                WHEN penjualan.no_invoice != '' THEN penjualan.no_invoice
                ELSE ''
            END) as Invoice_Number,
            penjualan.kode as kode_barang,
            penjualan.sisi,
            (CASE
                WHEN penjualan.sisi = '1' THEN 'satu'
                WHEN penjualan.sisi = '2' THEN 'dua'
                ELSE ''
            END) as css_sisi,
            (CASE
                WHEN penjualan.status = 'selesai' THEN 'Y'
                WHEN penjualan.status = '' THEN 'N'
                ELSE ''
            END) as Finished,
            (CASE
                WHEN barang.id_barang > 0 THEN barang.nama_barang
                ELSE penjualan.bahan
            END) as bahan,
            CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
            (CASE
                WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                ELSE ''
            END) as ukuran,
            (CASE
                WHEN penjualan.laminate !='' THEN 'Y'
                ELSE 'N'
            END) as laminating,
            penjualan.description,
            LEFT( penjualan.waktu, 10 ) as tanggal,
            LEFT(penjualan.kode, 1) as code,
            (CASE
                WHEN setter.nama != '' THEN setter.nama
                ELSE sales.nama
            END) as Nama_Setter,
            penjualan.cancel,
            penjualan.jenis_wo,
            penjualan.client_yes,
            penjualan.id_yes,
            penjualan.so_yes,
            (penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_indoor+penjualan.b_xuli) as harga_satuan,
            (penjualan.qty*(penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_indoor+penjualan.b_xuli)) as total
        from
            penjualan
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
        where
            penjualan.oid != '' and
            penjualan.client = '1' and 
            penjualan.cancel != 'Y'
            $add_where
        order by
            penjualan.oid
        desc
    ";

    $no = 0;

    // Perform query
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        // output data of each row
        while ($d = $result->fetch_assoc()) :
            $no++;
            $kode_class = str_replace(" ", "_", $d['kode_barang']);

            if ($d['jenis_wo'] == "Kuning") :
                $status = "#eed428";
            else :
                $status = "#00ab34";
            endif;

            $array_kode = array("laminating", "Finished");
            foreach ($array_kode as $kode) {
                if ($d[$kode] != "" && $d[$kode] != "N") : ${'check_' . $kode} = "active";
                else : ${'check_' . $kode} = "deactive";
                endif;
            }

            if ($_SESSION['level'] == "admin_yes" or $_SESSION['level'] == "admin") :
                $Akses_Edit = "Y";
                if ($d['cancel'] == "Y") :
                    $icon = "<i class='fas fa-undo-alt text-success'></i>";
                else :
                    $icon = "<i class='far fa-trash-alt text-danger'></i>";
                endif;
                $Delete_icon = "<span class='icon_status' ondblclick='hapus(\"" . $d['oid'] . "\", \"" . $d['oid'] . "\", \"" . $d['cancel'] . "\")'>$icon</span>";
                $css_cancel = "";
            else :
                $Akses_Edit = "N";
                $Delete_icon = "";
                $css_cancel = "cancel";
            endif;

            $edit = "LaodForm(\"penjualan_yescom\", \"" . $d['oid'] . "\", \"" . $Akses_Edit . "\")";

            echo "
            <tr>
                <td onclick='" . $edit . "' class='pointer'>$no</td>
                <td onclick='" . $edit . "' class='pointer'>" . date("d M Y", strtotime($d['tanggal'])) . "</td>
                <td onclick='" . $edit . "' class='pointer'><center>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['oid']) . "</center></td>
                <td onclick='" . $edit . "' class='pointer'><center>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['Invoice_Number']) . "</center></td>
                <td onclick='" . $edit . "' class='pointer'><Center><span class='KodeProject " . $kode_class . "'>" . strtoupper($d['code']) . "</span></Center></td>
                <td onclick='" . $edit . "' class='pointer'><b style='color:$status;'>▐</b> <strong>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['client_yes']) . " ( " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['id_yes']) . " / " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['so_yes']) . " )</strong> - " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['description']) . " $d[ukuran]</td>
                <td>
                    <center>
                        <span class='icon_status'><i class='fas fa-check-double " . $check_Finished . "'></i></span>
                        <span class='icon_status'><i class='fas fa-toilet-paper-alt " . $check_laminating . "'></i></span>
                    </center>
                </td>
                <td><center><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></center></td>
                <td>$d[bahan]</td>
                <td>$d[qty]</td>
                <td style='text-align:right; padding-right:18px;'>" . number_format($d['harga_satuan']) . "</td>
                <td style='text-align:right; padding-right:18px;'>" . number_format($d['total']) . "</td>
                <td>
                    $Delete_icon
                    <span class='icon_status' onclick='LaodForm(\"log\", \"" . $d['oid'] . "\")'><i class='fad fa-file-alt'></i></span>
                 </td>
            </tr>
            ";

            $total_invoice[]   = $d['total'];
            $Nilai_total_invoice = number_format(array_sum($total_invoice));

        endwhile;
    else :
        $Nilai_total_invoice = 0;
        echo "
            <tr>
                <td colspan='13'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
            </tr>
        ";
    endif;
    ?>
    <tr>
        <th colspan="11">Total Penjualan Yescom <?= $tanggal_total; ?></th>
        <th style='text-align:right'>
            <center><?= $Nilai_total_invoice; ?></center>
        </th>
    </tr>
</table>