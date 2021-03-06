<?php
session_start();
require_once '../../function.php';

$test = "";
$Setter_Sort = isset($_POST['Setter_Sort']) ? $_POST['Setter_Sort'] : ' ';

if (isset($Setter_Sort)) :
    $test .= $Setter_Sort;
else :
    $test .= "$_SESSION[filter_ID_Penjualan]";
endif;

$_SESSION['filter_ID_Penjualan'] = "$test";
$no = 1;

if ($_POST['data'] != '' && $_POST['client'] == '') :
    $Add_Search = "and ( penjualan.description LIKE '%$_POST[data]%' or penjualan.oid LIKE '%$_POST[data]%' or penjualan.no_invoice LIKE '%$_POST[data]%' or bahan LIKE '%$_POST[data]%')";
elseif ($_POST['data'] == '' && $_POST['client'] != '') :
    $Add_Search = "and customer.nama_client LIKE '%$_POST[client]%'";
elseif ($_POST['data'] != '' && $_POST['client'] != '') :
    $Add_Search = "and customer.nama_client LIKE '%$_POST[client]%' and penjualan.description LIKE '%$_POST[data]%'";
else :
    $Add_Search = "and penjualan.cancel!='Y'";
endif;

if ($_POST['date'] != '') :
    $Add_date = "and LEFT( penjualan.waktu, 10 ) = '$_POST[date]'";
else :
    $Add_date = "";
endif;

if ($_SESSION['filter_ID_Penjualan'] != '') :
    $Add_Setter = "and penjualan.setter='$_SESSION[filter_ID_Penjualan]'";
else :
    $Add_Setter = "";
endif;

$cari_keyword = $_POST['data'];
$bold_cari_keyword = "<strong style='text-decoration:underline'>" . $_POST['data'] . "</strong>";

$cari_keyword_client = $_POST['client'];
$bold_cari_keyword_client = "<span style='text-decoration:underline'>" . $_POST['client'] . "</span>";

if ($_SESSION["level"] == "admin") :
    $width_icon = "7%";
    $width_bahan = "10%";
else :
    $width_icon = "3%";
    $width_bahan = "13%";
endif;
?>
<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="2%">#</th>
            <th width="7%">Tanggal</th>
            <th width="5%">ID Order</th>
            <th width="6%">No. Invoice</th>
            <th width="3%">K</th>
            <th width="37%">Client - Description</th>
            <th width="9%">Detail Icon</th>
            <th width="2%">S</th>
            <th width="<?= $width_bahan ?>">Bahan</th>
            <th width="8%">Qty</th>
            <th width="5%">
                <select name="SetterSearch" id="SetterSearch" onchange="SetterSearch();">
                    <option value="">Setter</option>
                    <?php
                    $sql = "
                        select
                            penjualan.setter,
                            setter.nama,
                            COUNT(penjualan.setter) as Qty,
                            penjualan.client
                        from
                            penjualan
                        LEFT JOIN 
                            (select pm_user.uid, pm_user.nama from pm_user) setter
                        ON
                            penjualan.setter = setter.uid  
                        LEFT JOIN 
                            (select customer.cid, customer.nama_client from customer) customer
                        ON
                            penjualan.client = customer.cid  
                        where
                            penjualan.oid != '' and
                            penjualan.client !='1'
                            $Add_Search
                            $Add_date
                        GROUP BY
                            penjualan.setter
                        ";

                    // Perform query
                    $result = $conn_OOP->query($sql);

                    if ($result->num_rows > 0) :
                        // output data of each row
                        while ($d = $result->fetch_assoc()) :
                            $Nama_Setter = ucwords($d['nama']);
                            if ($d['setter'] == "$test") {
                                $pilih = "selected";
                            } else {
                                $pilih = "";
                            }
                            echo "<option value='$d[setter]' $pilih>$Nama_Setter ($d[Qty])</option>";
                        endwhile;
                    else :

                    endif;
                    ?>
                </select>
            </th>
            <th width="<?= $width_icon ?>"></th>
        </tr>
        <?php
        $sql =
            "SELECT
            penjualan.oid,
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
            penjualan.acc, 
            penjualan.no_invoice,
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
            penjualan.ditunggu,
            (CASE
                WHEN penjualan.acc = 'Y' THEN 'Y'
                WHEN penjualan.acc = 'N' THEN 'N'
                ELSE 'N'
            END) as acc,
            penjualan.Design,
            penjualan.description,
            LEFT( penjualan.waktu, 10 ) as tanggal,
            LEFT(penjualan.kode, 1) as code,
            penjualan.kode as kode_barang,
            customer.nama_client,
            setter.nama as Nama_Setter,
            penjualan.cancel,
            penjualan.posisi_file,
            penjualan.img_design,
            penjualan.file_design,
            (CASE
                WHEN penjualan.pembayaran = 'lunas' THEN 'Y'
                ELSE 'N'
            END) as pembayaran,
            (CASE
                WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                WHEN penjualan.akses_edit = 'N' THEN 'N'
                ELSE 'N'
            END) as akses_edit
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
        where
            penjualan.oid != '' and
            penjualan.client !='1'
            $Add_Setter
            $Add_Search
            $Add_date
        order by
            penjualan.oid
        desc
        LIMIT
            500
    ";

        // Perform query
        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($d = $result->fetch_assoc()) :
                $kode_class = str_replace(" ", "_", $d['kode_barang']);

                if ($d['no_invoice'] != "0") :
                    $no_invoice = "#$d[no_invoice]";
                else :
                    $no_invoice = "-";
                endif;

                if ($_SESSION["level"] == "admin") :
                    $button_Logs = "<span class='icon_status' onclick='LaodForm(\"log\", \"" . $d['oid'] . "\")'><i class='fad fa-file-alt'></i></span>";
                    $selesai = "selesai_prog(\"" . $d['oid'] . " | " . $d['nama_client'] . " - " . $d['description'] . "\", \"" . $d['oid'] . "\", \"" . $d['Finished'] . "\")";
                    $pointer_selesai = "pointer";
                    if ($d['cancel'] != "Y") :
                        $button_Cancel = "<span class='icon_status' onclick='LaodForm(\"setter_penjualan_cancel\", \"" . $d['oid'] . "\", \" \")'><i class='far fa-trash-alt text-danger'></i></span>";
                        $css_cancel = "";
                    else :
                        $button_Cancel = "";
                        $css_cancel = "cancel";
                    endif;

                    if ($d['akses_edit'] == "Y") :
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"Y\", \"" . $d['oid'] . "\")'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "Y";
                    else :
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"" . $d['oid'] . "\")'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "Y";
                    endif;
                else :
                    $button_Cancel = "";
                    $button_Logs = "";
                    $selesai = "";
                    $pointer_selesai = "";
                    if ($d['cancel'] != "Y") :
                        $css_cancel = "";
                    else :
                        $css_cancel = "cancel";
                    endif;

                    if ($d['akses_edit'] == "Y") :
                        $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "$d[akses_edit]";
                    else :
                        $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "$d[akses_edit]";
                    endif;
                endif;

                if ($d['img_design'] != "") :
                    $button_Image = "<span class='icon_status pointer' onclick='LaodSubForm(\"setter_penjualan_preview\", \"" . $d['oid'] . "\")'><i class='fas fa-image'></i></span>";
                else :
                    $button_Image = "";
                endif;

                $array_kode = array("ditunggu", "acc", "Finished", "pembayaran");
                foreach ($array_kode as $kode) {
                    if ($d[$kode] != "" && $d[$kode] != "N") : ${'check_' . $kode} = "active";
                    else : ${'check_' . $kode} = "deactive";
                    endif;
                }

                $edit = "LaodForm(\"setter_penjualan\", \"" . $d['oid'] . "\", \"" . $Akses_Edit . "\")";

                echo "
                <tr class='$css_cancel'>
                    <td>" . $no++ . "</td>
                    <td>" . date("d M Y", strtotime($d['tanggal'])) . "</td>
                    <td onclick='" . $edit . "' style='cursor:pointer'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['oid']) . "</td>
                    <td>" . str_ireplace($cari_keyword, $bold_cari_keyword, $no_invoice) . "</td>
                    <td><center><span class='KodeProject $kode_class'>" . strtoupper($d['code']) . "</span></center></td>
                    <td onclick='" . $edit . "' style='cursor:pointer'><b>" . str_ireplace($cari_keyword_client, $bold_cari_keyword_client, $d['nama_client']) . "</b> - " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['description']) . " " . $d['ukuran'] . "</td>
                    <td>
                        <center>
                            $icon_akses_edit
                            <span class='icon_status $pointer_selesai' ondblclick='$selesai'><i class='fas fa-check-double $check_Finished'></i></span>
                            <span class='icon_status'><i class='fas fa-user-clock $check_ditunggu'></i></span>
                            <span class='icon_status'><i class='fas fa-cash-register $check_pembayaran'></i></span>
                        </center>
                    </td>
                    <td class='a-center'><span class=' $d[css_sisi] KodeProject'>$d[sisi]</span></td>
                    <td>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['bahan']) . "</td>
                    <td>$d[qty]</td>
                    <td>$d[Nama_Setter]</td>
                    <td>
                        $button_Cancel
                        $button_Logs
                        $button_Image
                    </td>
                </tr>
            ";
            endwhile;
        } else {
            echo "
                <tr>
                    <td colspan='13'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                </tr>
            ";
        }
        ?>
    </tbody>
</table>

<div id="result"></div>

<?php $conn->close(); ?>