<?php
session_start();
require_once "../../function.php";

if (isset($_POST['Form_KodeBrg'])) {
    $kode_barang = $_POST['Form_KodeBrg'];

    if ($kode_barang == "digital") {
        $digital = "";
        $lf = "none";
        $latex = "none";
        $xuli = "none";
    } elseif ($kode_barang == "large format") {
        $digital = "none";
        $lf = "";
        $latex = "none";
        $xuli = "none";
    } elseif ($kode_barang == "indoor") {
        $digital = "none";
        $lf = "none";
        $latex = "";
        $xuli = "none";
    } elseif ($kode_barang == "xuli") {
        $digital = "none";
        $lf = "none";
        $latex = "none";
        $xuli = "";
    } else {
        $digital = "none";
        $lf = "none";
        $latex = "none";
        $xuli = "none";
    }
} else {
    $kode_barang = "";
    $digital = "none";
    $lf = "none";
    $latex = "none";
}

if ($kode_barang != "" and $_POST['data'] == "") {
    $add_where = "and pricelist.jenis ='$kode_barang' and pricelist.sisi ='$_POST[sisi_cetak]' and pricelist.warna ='$_POST[warna]'";
} elseif ($kode_barang != "" and $_POST['data'] != "") {
    $add_where = "and barang.nama_barang LIKE '%$_POST[data]%'";
} else {
    $add_where = "";
}

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="2%">#</th>
            <th width="16%">Nama Bahan</th>
            <th width="7%">Jenis</th>
            <th width="3%">Sisi</th>
            <th width="4%" style="display:<?= $digital ?>">1 Lbr</th>
            <th width="4%" style="display:<?= $digital ?>">2 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">3-5 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">6-9 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">10 Lbr</th>
            <th width="4%" style="display:<?= $digital ?>">20 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">50 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">100 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">250 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">500 Lbr</th>
            <th width="5%" style="display:<?= $digital ?>">1 Ktk</th>
            <th width="5%" style="display:<?= $digital ?>">2-19 Ktk</th>
            <th width="5%" style="display:<?= $digital ?>">20 ktk</th>
            <th width="15%" style="display:<?= $lf ?>">1-2 m</th>
            <th width="15%" style="display:<?= $lf ?>">3-9 m</th>
            <th width="15%" style="display:<?= $lf ?>">10 m</th>
            <th width="16%" style="display:<?= $lf ?>">50m</th>
            <th width="15%" style="display:<?= $xuli ?>">1-2 m</th>
            <th width="15%" style="display:<?= $xuli ?>">3-9 m</th>
            <th width="15%" style="display:<?= $xuli ?>">10 m</th>
            <th width="16%" style="display:<?= $xuli ?>">50m</th>
            <th width="15%" style="display:<?= $latex ?>">Indoor Latex</th>
            <th width="15%" style="display:<?= $latex ?>">6-8 Pass</th>
            <th width="15%" style="display:<?= $latex ?>">12 Pass</th>
            <th width="16%" style="display:<?= $latex ?>">20 Pass</th>
            <th width="5%">SPL Price</th>
            <th width="1%"></th>
        </tr>
        <?php
        $sql =
            "SELECT
                    pricelist.price_id,
                    barang.nama_barang,
                    pricelist.jenis,
                    pricelist.sisi,
                    (CASE
                        WHEN pricelist.sisi = '1' THEN 'satu'
                        WHEN pricelist.sisi = '2' THEN 'dua'
                        ELSE ''
                    END) as css_sisi,
                    pricelist.warna,
                    pricelist.1_lembar,
                    pricelist.2_lembar,
                    pricelist.3sd5_lembar,
                    pricelist.6sd9_lembar,
                    pricelist.10_lembar,
                    pricelist.20_lembar,
                    pricelist.50_lembar,
                    pricelist.100_lembar,
                    pricelist.250_lembar,
                    pricelist.500_lembar,
                    pricelist.1_kotak,
                    pricelist.2sd19_kotak,
                    pricelist.20_kotak,
                    pricelist.1sd2m,
                    pricelist.3sd9m,
                    pricelist.10m,
                    pricelist.50m,
                    pricelist.harga_indoor,
                    pricelist.6sd8pass_indoor,
                    pricelist.12pass_indoor,
                    pricelist.20pass_indoor,
                    pricelist.special_price_LF,
                    pricelist.status_pricelist
                FROM
                    pricelist
                LEFT JOIN
                    (
                        SELECT
                            barang.nama_barang,
                            barang.id_barang
                        FROM
                            barang
                    ) barang
                ON
                    barang.id_barang = pricelist.bahan
                WHERE
                    pricelist.status_pricelist = '$_POST[show_delete]'
                    $add_where
                ";

        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($row = $result->fetch_assoc()) :
                $no++;

                if ($row['status_pricelist'] == "a") :
                    $icon = "<i class='far fa-trash-alt text-danger'></i>";
                else :
                    $icon = "<i class='fas fa-undo-alt text-success'></i>";
                endif;

                if ($row['warna'] == "FC") : $status = "color:#f76c35;";
                elseif ($row['warna'] == "BW") : $status = "color:#535353;";
                else : $status = "#000000";
                endif;

                if ($_SESSION['level'] == "admin") :
                    $edit = "LaodForm(\"database_pricelist\", \"" . $row['price_id'] . "\")";
                else :
                    $edit = "";
                endif;

                echo "
                        <tr>
                            <td>$no</td>
                            <td onClick='$edit' class='pointer'><b style='$status'>▐</b> $row[nama_barang]</td>
                            <td onClick='$edit' class='pointer'>" . ucfirst($row['jenis']) . "</td>
                            <td onClick='$edit' class='pointer'><span class='" . $row['css_sisi'] . " KodeProject'>" . $row['sisi'] . "</span></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['1_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['2_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['3sd5_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['6sd9_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['10_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['20_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['50_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['100_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['250_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['500_lembar']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['1_kotak']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['2sd19_kotak']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$digital'><center>" . number_format($row['20_kotak']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$lf'><center>" . number_format($row['1sd2m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$lf'><center>" . number_format($row['3sd9m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$lf'><center>" . number_format($row['10m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$lf'><center>" . number_format($row['50m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$xuli'><center>" . number_format($row['1sd2m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$xuli'><center>" . number_format($row['3sd9m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$xuli'><center>" . number_format($row['10m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$xuli'><center>" . number_format($row['50m']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$latex'><center>" . number_format($row['harga_indoor']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$latex'><center>" . number_format($row['6sd8pass_indoor']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$latex'><center>" . number_format($row['12pass_indoor']) . "</center></td>
                            <td onClick='$edit' class='pointer' style='display:$latex'><center>" . number_format($row['20pass_indoor']) . "</center></td>
                            <td onClick='$edit' class='pointer'><center>" . number_format($row['special_price_LF']) . "</center></td>
                            <td class='pointer' ondblclick='hapus(\"" . $row['price_id'] . "\", \"" . $row['nama_barang'] . "\", \"" . $row['status_pricelist'] . "\")'>$icon</td>
                        </tr>
                        ";
            endwhile;
        else :
            echo "
                        <tr>
                            <td colspan='20'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
        endif;
        ?>
    </tbody>
</table>