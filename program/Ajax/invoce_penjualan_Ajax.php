<?php
session_start();

require_once '../../function.php';

$n = 0;
$Nilai_Total = null;

if ($_POST['data'] != '' && $_POST['client'] == '' && $_POST['invoice'] == '') :
    $Add_Search = "and ( penjualan.description LIKE '%$_POST[data]%' or penjualan.oid LIKE '%$_POST[data]%' or bahan LIKE '%$_POST[data]%')";
elseif ($_POST['data'] == '' && $_POST['client'] != '' && $_POST['invoice'] == "") :
    $Add_Search = "and customer.nama_client LIKE '%$_POST[client]%'";
elseif ($_POST['data'] != '' && $_POST['client'] != '' && $_POST['invoice'] == "") :
    $Add_Search = "and customer.nama_client LIKE '%$_POST[client]%' and penjualan.description LIKE '%$_POST[data]%'";
elseif ($_POST['invoice'] != "") :
    $Add_Search = "and penjualan.no_invoice LIKE '%$_POST[invoice]%'";
else :
    $Add_Search = "and penjualan.cancel!='Y'";
endif;

if ($_POST['date'] != '') :
    $Add_date = "and LEFT( penjualan.invoice_date, 10 ) = '$_POST[date]'";
    $tanggal_total = date("d M Y", strtotime($_POST['date']));
else :
    $Add_date = "";
    $tanggal_total = date("d M Y", strtotime($date));
endif;

$cari_keyword = $_POST['data'];
$bold_cari_keyword = "<strong style='text-decoration:underline'>" . $_POST['data'] . "</strong>";

$cari_keyword_client = $_POST['client'];
$bold_cari_keyword_client = "<strong style='text-decoration:underline'>" . $_POST['client'] . "</strong>";
?>

<script>
    $(function() {
        $("td").hover(function() {
            $el = $(this);
            $el.parent().addClass("hover");
            var tdIndex = $('tr').index($el.parent());
            if ($el.parent().has('td[rowspan]').length == 0) {
                $el.parent().prevAll('tr:has(td[rowspan]):first')
                    .find('td[rowspan]').filter(function() {
                        return checkRowSpan(this, tdIndex);
                    }).addClass("hover");
            }
        }, function() {
            $el.parent()
                .removeClass("hover")
                .prevAll('tr:has(td[rowspan]):first')
                .find('td[rowspan]')
                .removeClass("hover");
        });
    });

    function checkRowSpan(element, pIndex) {
        var rowSpan = parseInt($(element).attr('rowspan'));
        var cIndex = $('tr').index($(element).parent());
        return rowSpan >= pIndex + 1 || (cIndex + rowSpan) > pIndex;
    }
</script>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="1%">#</th>
            <th width="8%">Tanggal</th>
            <th width="12%">Client</th>
            <th width="6%">No. Invoice</th>
            <th width="3%">K</th>
            <th width="26%">Description</th>
            <th width="7%">Icon</th>
            <th width="3%">S</th>
            <th width="9%">Bahan</th>
            <th width="7%">Qty</th>
            <th width="6%">Harga @</th>
            <th width="4%">Disc.</th>
            <th width="7%">Total Harga</th>
            <th width="1%"></th>
        </tr>
        <?php
        $sql =
            "SELECT
                        penjualan.no_invoice,
                        GROUP_CONCAT((CASE
                            WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                            WHEN penjualan.akses_edit = 'N' THEN 'N'
                            ELSE 'N'
                        END)) as akses_edit,
                        LEFT( penjualan.invoice_date, 10 ) as tanggal,
                        customer.nama_client,
                        GROUP_CONCAT(penjualan.oid) as oid,
                        GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
                        GROUP_CONCAT((CASE
                            WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            ELSE ''
                        END)) as ukuran,
                        GROUP_CONCAT(LEFT(penjualan.kode, 1)) as code,
                        GROUP_CONCAT(penjualan.kode) as kode_barang,
                        GROUP_CONCAT(penjualan.sisi) as sisi,
                        GROUP_CONCAT((CASE
                            WHEN penjualan.sisi = '1' THEN 'satu'
                            WHEN penjualan.sisi = '2' THEN 'dua'
                            ELSE ''
                        END)) as css_sisi,
                        GROUP_CONCAT((CASE
                            WHEN barang.id_barang > 0 THEN barang.nama_barang
                            ELSE penjualan.bahan
                        END)) as bahan,
                        GROUP_CONCAT(CONCAT(penjualan.qty, ' ' ,penjualan.satuan)) as qty,
                        GROUP_CONCAT((CASE
                            WHEN penjualan.status = 'selesai' THEN 'Y'
                            WHEN penjualan.status = '' THEN 'N'
                            ELSE ''
                        END)) as Finished,
                        GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)) as harga_satuan,
                        GROUP_CONCAT(penjualan.discount) as discount,
                        GROUP_CONCAT((((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty)) as total,
                        penjualan.cancel,
                        penjualan.pembayaran,
                        sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                        pelunasan.total_bayar,
                        (CASE
                            WHEN penjualan.inv_check = 'Y' THEN 'Y'
                            ELSE 'N'
                        END) as inv_check
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
                        (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar from pelunasan group by pelunasan.no_invoice) pelunasan
                    ON
                        penjualan.no_invoice = pelunasan.no_invoice  
                    where
                        penjualan.no_invoice != '' and
                        penjualan.client !='1'
                        $Add_date
                        $Add_Search
                    GROUP BY
                        penjualan.no_invoice
                    order by
                        penjualan.no_invoice
                    desc
                ";

        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;
                $kode_class = str_replace(" ", "_", $d['kode_barang']);
                $oid = explode(",", "$d[oid]");
                $description = explode("*_*", $d['description']);
                $ukuran = explode(",", "$d[ukuran]");
                $kode_barang = explode(",", "$kode_class");
                $code = explode(",", "$d[code]");
                $sisi = explode(",", "$d[sisi]");
                $css_sisi = explode(",", "$d[css_sisi]");
                $bahan = explode(",", "$d[bahan]");
                $qty = explode(",", "$d[qty]");
                $Finished = explode(",", "$d[Finished]");
                $discount = explode(",", "$d[discount]");
                $harga_satuan = explode(",", "$d[harga_satuan]");
                $total = explode(",", "$d[total]");
                $akses_edit = explode(",", "$d[akses_edit]");
                $array_sum = array_sum($total);
                $array_kode = array("Finished");
                foreach ($array_kode as $kode) {
                    if ($Finished[0] != "" && $Finished[0] != "N") : ${'check_' . $kode} = "active";
                    else :
                        ${'check_' . $kode} = "deactive";
                    endif;
                }

                $count_oid = count($oid);

                if ($d['pembayaran'] == "lunas") : $check_Lunas = "active";
                elseif ($d['Total_keseluruhan'] == $d['total_bayar']) : $check_Lunas = "active";
                else : $check_Lunas = "deactive";
                endif;

                if ($akses_edit['0'] == "Y") :
                    if ($_SESSION["level"] == "admin") {
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"Y\", \"" . $oid['0'] . "\")'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "Y";
                    } else {
                        $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "$akses_edit[0]";
                    } else :
                    if ($_SESSION["level"] == "admin") {
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"" . $oid['0'] . "\")'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "Y";
                    } else {
                        $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "$akses_edit[0]";
                    }
                endif;

                if ($_SESSION["level"] == "admin") :
                    if ($d['pembayaran'] == "lunas" and $_SESSION["level"] == "admin") {
                        $force_paid = "";
                    } elseif ($d['Total_keseluruhan'] == $d['total_bayar'] and $_SESSION["level"] == "admin") {
                        $force_paid = "";
                    } else {
                        $force_paid = "<i class='fas fa-hand-holding-usd pointer text-danger' ondblclick='force_paid(\"" . $d['no_invoice'] . "\")'></i>";
                    }

                    if ($d['cancel'] != "Y") {
                        $button_Cancel = "<span class='icon_status' onclick='LaodForm(\"setter_penjualan_cancel\", \"" . $d['no_invoice'] . "\", \"cancel_invoice\")'><i class='far fa-trash-alt text-danger'></i></span>";
                        $css_cancel = "";
                        $sub_css_cancel = "background-color:#b5d0f5;";
                    } else {
                        $button_Cancel = "";
                        $css_cancel = "cancel";
                        $sub_css_cancel = "background-color:red;";
                    } else :
                    $force_paid = "";
                    $button_Cancel = "";
                    if ($d['cancel'] != "Y") {
                        $css_cancel = "";
                        $sub_css_cancel = "background-color:#b5d0f5;";
                    } else {
                        $css_cancel = "cancel";
                        $sub_css_cancel = "background-color:red;";
                    }
                endif;

                if ($d['inv_check'] == "N") :
                    $check_invoice = "<span style='background-color:green; padding:3px 10px; margin-left:10px; color:white; border-radius:5px; box-sizing:border-box; cursor:pointer; user-select: none;' onclick='check_invoice_form(\"" . $d['no_invoice'] . "\")'>Cek Invoice</span>";
                    $print_invoice = "";
                else :
                    $check_invoice = "";
                    $print_invoice = "<a href='print.php?type=sales_invoice&no_invoice=$d[no_invoice]' target='_blank' class='pointer'><i class='fad fa-print'></i></a>";
                endif;

                $edit = "LaodForm(\"setter_penjualan\", \"" . $oid['0'] . "\", \"" . $Akses_Edit . "\")";

                echo "
                            <tr class='" . $css_cancel . "'>
                                <td rowspan='$count_oid' style='vertical-align:top'>$n</td>
                                <td rowspan='$count_oid' style='vertical-align:top'>" . date("d M Y", strtotime($d['tanggal'])) . "</td>
                                <td rowspan='$count_oid' style='vertical-align:top'>" . str_ireplace($cari_keyword_client, $bold_cari_keyword_client, $d['nama_client']) . "</td>
                                <td rowspan='$count_oid' style='vertical-align:top'>#" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['no_invoice']) . "</td>
                                <td class='a-center'><span class='KodeProject $kode_barang[0]'>" . strtoupper($code['0']) . "</span></td>
                                <td onclick='$edit' class='pointer'> " . str_ireplace($cari_keyword, $bold_cari_keyword, $oid['0']) . " - " . str_ireplace($cari_keyword, $bold_cari_keyword, $description['0']) . " $ukuran[0] </td>
                                <td class='a-center'>
                                    $icon_akses_edit
                                    <span class='icon_status'><i class='fas fa-check-double $check_Finished'></i></span>
                                    <span class='icon_status'><i class='fas fa-cash-register $check_Lunas'></i></span>
                                </td>
                                <td class='a-center'><span class='$css_sisi[0] KodeProject'>$sisi[0]</span></td>
                                <td>" . str_ireplace($cari_keyword, $bold_cari_keyword, $bahan['0']) . "</td>
                                <td>$qty[0]</td>
                                <td style='text-align:right'>" . number_format($harga_satuan['0']) . "</td>
                                <td style='text-align:right'>" . number_format($discount['0']) . "</td>
                                <td style='text-align:right'>" . number_format($total['0']) . "</td>
                                <td rowspan='$count_oid' style='vertical-align:top'>$print_invoice</td>
                            </tr>
                        ";

                for ($i = 1; $i < $count_oid; $i++) {
                    $X_oid              = $oid[$i];
                    $X_description      = $description[$i];
                    $X_ukuran           = $ukuran[$i];
                    $X_kode_barang      = $kode_barang[$i];
                    $X_code             = $code[$i];
                    $X_sisi             = $sisi[$i];
                    $X_css_sisi         = $css_sisi[$i];
                    $X_bahan            = $bahan[$i];
                    $X_qty              = $qty[$i];
                    $X_harga_satuan     = $harga_satuan[$i];
                    $X_total            = $total[$i];
                    $X_Finished         = $Finished[$i];
                    $X_akses_edit       = $akses_edit[$i];
                    $X_discount         = $discount[$i];

                    if ($X_akses_edit == "Y") :
                        if ($_SESSION["level"] == "admin") {
                            $Xicon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"Y\", \"" . $X_oid . "\")'><i class='fad fa-lock-open-alt'></i></span>";
                            $XAkses_Edit = "Y";
                        } else {
                            $Xicon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-open-alt'></i></span>";
                            $XAkses_Edit = "$X_akses_edit ";
                        } else :
                        if ($_SESSION["level"] == "admin") {
                            $Xicon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"" . $X_oid . "\")'><i class='fad fa-lock-alt'></i></span>";
                            $XAkses_Edit = "Y";
                        } else {
                            $Xicon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-alt'></i></span>";
                            $XAkses_Edit = "$X_akses_edit";
                        }
                    endif;

                    $array_kode = array("Finished");
                    foreach ($array_kode as $kode) {
                        if ($X_Finished != "" && $X_Finished != "N") : ${'X_check_' . $kode} = "active";
                        else : ${'X_check_' . $kode} = "deactive";
                        endif;
                    }

                    $X_edit = "LaodForm(\"setter_penjualan\", \"" . $oid[$i] . "\", \"" . $XAkses_Edit . "\")";

                    echo "
                                <tr class='" . $css_cancel . "'>
                                    <td><span class='KodeProject $X_kode_barang'>" . strtoupper($X_code) . "</span></td>
                                    <td onclick='$X_edit' class='pointer'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $X_oid) . " - " . str_ireplace($cari_keyword, $bold_cari_keyword, $X_description) . " $X_ukuran</td>
                                    <td>
                                        $Xicon_akses_edit
                                        <span class='icon_status'><i class='fas fa-check-double $X_check_Finished'></i></span>
                                        <span class='icon_status'><i class='fas fa-cash-register $check_Lunas'></i></span>
                                    </td>
                                    <td><span class='$X_css_sisi KodeProject'>$X_sisi</span></td>
                                    <td>" . str_ireplace($cari_keyword, $bold_cari_keyword, $X_bahan) . "</td>
                                    <td>$X_qty</td>
                                    <td style='text-align:right'>" . number_format($X_harga_satuan) . "</td>
                                    <td style='text-align:right'>" . number_format($X_discount) . "</td>
                                    <td style='text-align:right'>" . number_format($X_total) . "</td>
                                </tr>
                            ";
                }

                $total_penjualan[]   = $array_sum;
                $Nilai_Total = number_format(array_sum($total_penjualan));

                echo "
                        <tr style='$sub_css_cancel font-weight:bold; display:" . $_POST['display'] . "' id='total_invoice'>
                            <td colspan='12'>Total Invoice $button_Cancel $force_paid $check_invoice</td>
                            <td style='text-align:right'>" . number_format($array_sum) . "</td>
                        </tr>
                        ";
            endwhile;
        else :
            $Nilai_Total = 0;
            echo "
                        <tr>
                            <td colspan='14'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
        endif;
        ?>
        <tr>
            <th colspan="12">Total Penjualan <?= $tanggal_total ?></th>
            <th style='text-align:right'><?= $Nilai_Total; ?></th>
        </tr>
    </tbody>
</table>

<?php $conn->close(); ?>