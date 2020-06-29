<?php
session_start();
require_once "../../function.php";

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
            <th width="12%">Supplier</th>
            <th width="8%">Tanggal Order</th>
            <th width="8%">Kode Order</th>
            <th width="16%">Kode Bahan</th>
            <th width="6%">Ukuran Bahan (M<sup>2</sup>)</th>
            <?php if ($_SESSION["level"] == "admin") : ?>
                <th width="6%">Harga</th>
                <th width="6%">Total Harga</th>
            <?php endif; ?>
            <th width="2%">Diterima</th>
        </tr>

        <?php

        if ($_POST['search_data'] != "") {
            $add_where = "and ( flow_bahanlf.kode_pemesanan LIKE '%$_POST[search_data]%' or supplier.nama_supplier LIKE '%$_POST[search_data]%' )";
        } elseif ($_POST['Check_box'] == "N") {
            $add_where = "and flow_bahanlf.diterima = 'N'";
        } else {
            if ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] != "") :
                $add_where = "and (flow_bahanlf.tanggal_order>='$_POST[Dari_Tanggal]' and flow_bahanlf.tanggal_order<='$_POST[Ke_Tanggal]')";
            elseif ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] == "") :
                $add_where = "and (flow_bahanlf.tanggal_order='$_POST[Dari_Tanggal]')";
            elseif ($_POST['Dari_Tanggal'] == "" and $_POST['Ke_Tanggal'] != "") :
                $add_where = "and (flow_bahanlf.tanggal_order='$_POST[Ke_Tanggal]')";
            else :
                $add_where = "and flow_bahanlf.diterima = 'Y'";
            endif;
        }

        $sql =
            "SELECT
                GROUP_CONCAT(flow_bahanlf.bid) as bid,
                GROUP_CONCAT(flow_bahanlf.harga) as harga,
                GROUP_CONCAT((flow_bahanlf.panjang*flow_bahanlf.lebar/10000)) as ukuran_bahan,
                GROUP_CONCAT(CONCAT(barang.nama_bahan,'.',flow_bahanlf.no_bahan)) as kode_bahan,
                GROUP_CONCAT(flow_bahanlf.harga*(flow_bahanlf.panjang*flow_bahanlf.lebar/10000)) as total_harga,
                flow_bahanlf.diterima,
                flow_bahanlf.tanggal_order,
                flow_bahanlf.kode_pemesanan,
                supplier.nama_supplier
            FROM
                flow_bahanlf 
            LEFT JOIN
                (
                    SELECT
                        barang_sub_lf.ID_BarangLF,
                        barang.nama_barang,
                        barang_sub_lf.ukuran, 
                        concat(barang.nama_barang,'.',barang_sub_lf.ukuran) as nama_bahan
                    FROM
                        barang_sub_lf
                    LEFT JOIN
                        (
                            SELECT
                                barang.id_barang, 
                                barang.nama_barang
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
            GROUP BY
                flow_bahanlf.kode_pemesanan
            ";

        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($row = $result->fetch_assoc()) :
                $no++;
                $bid = explode(",", "$row[bid]");
                $kode_bahan = explode(",", "$row[kode_bahan]");
                $harga = explode(",", "$row[harga]");
                $total_harga = explode(",", "$row[total_harga]");
                $ukuran_bahan = explode(",", "$row[ukuran_bahan]");
                $array_sum = array_sum($total_harga);
                $count_bid = count($bid);

                if ($row['diterima'] == "Y") {
                    $css_terima = "active";
                } else {
                    $css_terima = "";
                }

                if ($row['diterima'] != "Y" or $_SESSION["level"] == "admin") {
                    $edit = "LaodForm(\"StockBahan_LF\", \"" . $row['kode_pemesanan'] . "\")";
                } else {
                    $edit = "";
                }

                echo "
                    <tr>
                        <td rowspan='$count_bid'>$no</td>
                        <td rowspan='$count_bid'>$row[nama_supplier]</td>
                        <td rowspan='$count_bid'>" . date("d M Y", strtotime($row['tanggal_order'])) . "</td>
                        <td rowspan='$count_bid' class='pointer' onclick='$edit'>$row[kode_pemesanan]</td>
                        <td>$kode_bahan[0]</td>
                        <td class='a-center'>" . number_format($ukuran_bahan[0], 2) . "</td>
                ";

                if ($_SESSION["level"] == "admin") :
                    echo "
                        <td>" . number_format($harga[0]) . "</td>
                        <td class='a-center'>" . number_format($total_harga[0]) . "</td>
                    ";
                endif;

                echo "
                        <td rowspan='$count_bid' ondblclick='terima_Barang(\" $row[kode_pemesanan] \")' class='pointer a-center'> <span class='icon_status'><i class='fas fa-hand-holding-box $css_terima'></i></span> </td>
                    </tr>
                ";

                for ($i = 1; $i < $count_bid; $i++) {
                    echo "
                        <tr>
                            <td>$kode_bahan[$i]</td>
                            <td class='a-center'>" . number_format($ukuran_bahan[$i], 2) . "</td>
                    ";

                    if ($_SESSION["level"] == "admin") :
                        echo "
                            <td>" . number_format($harga[$i]) . "</td>
                            <td class='a-center'>" . number_format($total_harga[$i]) . "</td>
                        ";
                    endif;

                    echo " 
                        </tr>
                    ";
                }
                echo "
                    <tr style='background-color:#b5d0f5;' id='total_invoice'>
                        <th colspan='7'>Total Kode Order</th>
                        <th>" . number_format($array_sum) . "</th>
                    </tr>
                ";
            endwhile;
        endif;
        ?>
    </tbody>
</table>