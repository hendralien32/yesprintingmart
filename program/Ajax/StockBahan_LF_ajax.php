<?php
session_start();
require_once "../../function.php";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>

<table>
    <tbody>
        <tr>
            <th width="1%">#</th>
            <th width="8%">Supplier</th>
            <th width="7%">Kode Order</th>
            <th width="17%">Kode Bahan</th>
            <th width="8%">Tgl Buka</th>
            <th width="8%">Tgl Habis</th>
            <th width="10%">Ukuran Bahan (M<sup>2</sup>)</th>
            <th width="10%">Pemakaian Bahan (M<sup>2</sup>)</th>
            <th width="10%">Sisa Bahan (M<sup>2</sup>)</th>
            <th width="7%">Icon</th>
            <th width="1%"></th>
        </tr>

        <?php
        if ($_SESSION['session_mesin'] == "Polaris") :
            $jenis_barang = "and barang.jenis_barang = 'LF'";
        elseif ($_SESSION['session_mesin'] == "Xuli") :
            $jenis_barang = "and barang.jenis_barang = 'INDOOR'";
        elseif ($_SESSION['session_mesin'] == "HP Latex 360") :
            $jenis_barang = "and barang.jenis_barang = 'INDOOR'";
        else :
            $jenis_barang = "";
        endif;

        if ($_POST['search_data'] != "") {
            $add_where = "and ( flow_bahanlf.kode_pemesanan LIKE '%$_POST[search_data]%' or supplier.nama_supplier LIKE '%$_POST[search_data]%' or CONCAT(barang.nama_bahan,'.',flow_bahanlf.no_bahan) LIKE '%$_POST[search_data]%' )";
        } else {
            if ($_POST['type_bahan'] == "D1") :
                $add_where = "and flow_bahanlf.habis = 'N' and flow_bahanlf.tanggal_buka != '0000-00-00'";
            elseif ($_POST['type_bahan'] == "D2") :
                $add_where = "and flow_bahanlf.tanggal_buka = '0000-00-00'";
            elseif ($_POST['type_bahan'] == "D3") :
                $add_where = "and flow_bahanlf.habis = 'Y'";
            endif;
        }

        $sql =
            "SELECT
                barang.jenis_barang,
                flow_bahanlf.bid,
                flow_bahanlf.kode_pemesanan,
                supplier.nama_supplier,
                flow_bahanlf.panjang,
                flow_bahanlf.lebar,
                CONCAT(barang.nama_bahan,'.',flow_bahanlf.no_bahan) as kode_bahan,
                ((flow_bahanlf.panjang*flow_bahanlf.lebar)/10000) as Ukuran,
                IFNULL(sum(large_format.Total_cetak), 0) as Total_cetak,
                (((flow_bahanlf.panjang*flow_bahanlf.lebar)/10000)-IFNULL(SUM(large_format.Total_cetak), 0)) as sisa,
                flow_bahanlf.diterima,
                flow_bahanlf.habis,
                (CASE
                    WHEN flow_bahanlf.tanggal_buka = '0000-00-00' THEN 'N'
                    ELSE 'Y'
                END) as buka,
                flow_bahanlf.tanggal_buka,
                flow_bahanlf.tanggal_habis
            FROM
                flow_bahanlf
            LEFT JOIN
                (
                    SELECT
                        barang_sub_lf.ID_BarangLF,
                        barang.nama_barang,
                        barang.jenis_barang,
                        barang_sub_lf.ukuran, 
                        concat(barang.nama_barang,'.',barang_sub_lf.ukuran) as nama_bahan
                    FROM
                        barang_sub_lf
                    LEFT JOIN
                        (
                            SELECT
                                barang.id_barang, 
                                barang.nama_barang,
                                barang.jenis_barang
                            FROM
                                barang
                        ) barang
                    ON
                        barang.ID_barang = barang_sub_lf.id_barang
                ) barang
            ON
                barang.ID_BarangLF = flow_bahanlf.id_bahanLF
            LEFT JOIN
                (
                    SELECT
                        large_format.id_BrngFlow, 
                        ((large_format.panjang_potong*large_format.lebar_potong*large_format.qty_jalan)/ 10000) as Total_cetak
                    FROM
                        large_format
                    WHERE
                        large_format.cancel='' or large_format.cancel='N'
                    GROUP BY
                        large_format.so_kerja
                ) large_format
            ON
                large_format.id_BrngFlow = flow_bahanlf.bid
            LEFT JOIN
                (
                    SELECT
                        supplier.id_supplier, 
                        supplier.nama_supplier
                    FROM
                        supplier
                ) supplier
            ON
                supplier.id_supplier = flow_bahanlf.id_supplier
            WHERE
                flow_bahanlf.hapus = 'N'
                $add_where
                $jenis_barang
            GROUP BY
                flow_bahanlf.bid
            ";

        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($row = $result->fetch_assoc()) :
                $no++;
                $sisa = $row['Ukuran'] - $row['Total_cetak'];
                $array_kode = array("diterima", "habis", "buka");
                foreach ($array_kode as $kode) {
                    if ($row[$kode] != "" && $row[$kode] != "N") : ${'check_' . $kode} = "active";
                    else : ${'check_' . $kode} = "deactive";
                    endif;
                }

                if ($row['buka'] == "Y") {
                    $habis_action = "habis(\"$row[bid]\",\"$row[habis]\",\"$row[kode_bahan]\",\"$row[sisa]\",\"bahan_habis\",\"$row[buka]\", \"$row[diterima]\")";
                    $tanggal_buka = date("d M Y", strtotime($row['tanggal_buka']));
                } else {
                    $habis_action = "";
                    $tanggal_buka = "- - -";
                }

                if ($row['tanggal_habis'] != "0000-00-00" && $row['Total_cetak'] != 0) {
                    $tanggal_habis = date("d M Y", strtotime($row['tanggal_habis']));
                    $buka_action = "";
                } else {
                    $tanggal_habis = "- - -";
                    $buka_action = "habis(\"$row[bid]\",\"$row[habis]\",\"$row[kode_bahan]\",\"$row[sisa]\",\"buka_bahan\",\"$row[buka]\", \"$row[diterima]\")";
                }

                $terima_action = "habis(\"$row[bid]\",\"$row[habis]\",\"$row[kode_bahan]\",\"$row[sisa]\",\"terima_bahan\",\"$row[buka]\", \"$row[diterima]\")";

                if ($row['diterima'] != "Y" or $_SESSION["level"] == "admin") {
                    $edit = "LaodForm(\"StockBahan_LF\", \"" . $row['kode_pemesanan'] . "\")";
                } else {
                    $edit = "";
                }

                $panjang = $row['panjang'] / 100;
                $lebar = $row['lebar'] / 100;

                echo "
                    <tr>
                        <td>$no</td>
                        <td>$row[nama_supplier]</td>
                        <td class='pointer a-center' onclick='$edit'>$row[kode_pemesanan]</td>
                        <td>$row[kode_bahan] <i>( Uk. $panjang x $lebar M )</i></td>
                        <td class='a-center'>$tanggal_buka</td>
                        <td class='a-center'>$tanggal_habis</td>
                        <td class='a-center'>" . number_format($row['Ukuran'], 2) . "</td>
                        <td class='a-center'>" . number_format($row['Total_cetak'], 2) . "</td>
                        <td class='a-center'>" . number_format($row['sisa'], 2) . "</td>
                        <td class='a-center'>
                            <span class='icon_status pointer' ondblclick='$terima_action'><i class='fas fa-hand-holding-box $check_diterima'></i></span>
                            <span class='icon_status pointer' ondblclick='$buka_action'><i class='fas fa-box-open $check_buka'></i></span>
                            <span class='icon_status pointer' ondblclick='$habis_action'><i class='fas fa-empty-set $check_habis'></i></span>
                        </td>
                    </tr>
                ";
            endwhile;
        endif;
        ?>
    </tbody>
</table>