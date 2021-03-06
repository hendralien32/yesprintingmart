<?php
session_start();

require_once '../../function.php';

$OperatorSearch = ($_POST['OperatorSearch'] != "undefined" && $_POST['OperatorSearch'] != "") ? $_POST['OperatorSearch'] : $_SESSION['uid'];

if ($_POST['search'] != "") {
    $add_where = "and ( large_format.so_kerja LIKE '%$_POST[search]%' or large_format.oid LIKE '%$_POST[search]%' or penjualan.id_yes LIKE '%$_POST[search]%' or penjualan.description LIKE '%$_POST[search]%' or penjualan.client LIKE '%$_POST[search]%' )";
} else {
    if ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( large_format.date, 10 )>='$_POST[Dari_Tanggal]' and LEFT( large_format.date, 10 )<='$_POST[Ke_Tanggal]')";
    elseif ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] == "") :
        $add_where = "and (LEFT( large_format.date, 10 )='$_POST[Dari_Tanggal]')";
    elseif ($_POST['Dari_Tanggal'] == "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( large_format.date, 10 )='$_POST[Ke_Tanggal]')";
    else :
        $add_where = "";
    endif;
}

if ($OperatorSearch != "undefined" && $OperatorSearch != "") {
    $where_operator = "and large_format.uid = '$OperatorSearch'";
} else {
    $where_operator = "";
}

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
            <th width="7%">
                <select name="OperatorSearch" id="OperatorSearch" onchange="OperatorSearch();">
                    <option value="">Operator</option>
                    <?php
                    $sql = "
                        select
                            pm_user.uid,
                            pm_user.nama,
                            COUNT(large_format.so_kerja) as Qty
                        from
                            pm_user
                        LEFT JOIN
                            (
                                select
                                    large_format.uid,
                                    large_format.so_kerja
                                from
                                    large_format
                                LEFT JOIN
                                    (
                                        SELECT
                                            penjualan.oid,
                                            penjualan.id_yes,
                                            (CASE
                                                WHEN penjualan.client_yes != '' THEN penjualan.client_yes
                                                ELSE customer.nama_client 
                                            END) AS client,
                                            penjualan.description,
                                            (CASE
                                                WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                                WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                                ELSE ''
                                            END) as ukuran
                                        FROM
                                            penjualan
                                        LEFT JOIN 
                                            (select customer.cid, customer.nama_client from customer) customer
                                        ON
                                            penjualan.client = customer.cid 
                                    ) penjualan
                                ON
                                    penjualan.oid = large_format.oid
                                where
                                    large_format.cancel != 'Y'
                                    $add_where
                                GROUP BY
                                    large_format.so_kerja
                            ) large_format
                        ON
                            pm_user.uid = large_format.uid
                        WHERE
                            large_format.so_kerja != '0' 
                        GROUP BY
                            large_format.uid
                    ";

                    $result = $conn_OOP->query($sql);
                    if ($result->num_rows > 0) :
                        // output data of each row
                        while ($d = $result->fetch_assoc()) :
                            $Nama_Operator = ucwords($d['nama']);
                            if ($d['uid'] == "$OperatorSearch") {
                                $pilih = "selected";
                            } else {
                                $pilih = "";
                            }
                            echo "<option value='$d[uid]' $pilih>$Nama_Operator ($d[Qty])</option>";
                        endwhile;
                    else :

                    endif;
                    ?>
                </select>
            </th>
            <th width="7%">Tanggal</th>
            <th width="8%">SO Pemotongan</th>
            <th width="6%">ID</th>
            <th width="37%">ID Order - Description</th>
            <th width="15%">Kode Bahan</th>
            <th width="8%">Ukuran Cetak</th>
            <th width="5%">Qty Jln</th>
            <th width="6%">Total</th>
        </tr>
        <?php

        $sql =
            "SELECT
                large_format.so_kerja,
                operator.username as nama_operator,
                LEFT(large_format.date,10) as tgl_cetak,
                GROUP_CONCAT(large_format.oid) as id_order,
                GROUP_CONCAT(penjualan.client) as client,
                GROUP_CONCAT(penjualan.description) as description,
                GROUP_CONCAT(penjualan.ukuran) as ukuran,
                GROUP_CONCAT(penjualan.id_yes) as id_yes,
                (CASE
                    WHEN large_format.panjang_potong > 0 THEN CONCAT(large_format.panjang_potong, ' X ', large_format.lebar_potong, ' Cm') 
                    WHEN large_format.lebar_potong > 0 THEN CONCAT(large_format.panjang_potong, ' X ', large_format.lebar_potong, ' Cm') 
                    ELSE '- - -'
                END) as ukuran_cetak,
                CONCAT(large_format.qty_jalan, 'x') as qty_jalan,
                coalesce(large_format.panjang_potong,0) as panjang_potong,
                coalesce(large_format.lebar_potong,0) as lebar_potong,
                large_format.qty_jalan as Jalan,
                (CASE
                    WHEN large_format.kode_bahan != '' THEN large_format.kode_bahan
                    else test.kode_bahan
                END) as kode_bahan,
                large_format.pass,
                large_format.restan,
                large_format.status,
                large_format.kesalahan
            FROM
                large_format
            LEFT JOIN
                (
                    SELECT
                        flow_bahanlf.bid,
                        CONCAT(barang.nama_bahan,'.',flow_bahanlf.no_bahan) as kode_bahan
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
                ) test
            ON
                large_format.id_BrngFlow = test.bid
            LEFT JOIN
                (
                    SELECT
                        penjualan.oid,
                        penjualan.id_yes,
                        (CASE
                            WHEN penjualan.client_yes != '' THEN penjualan.client_yes
                            ELSE customer.nama_client 
                        END) AS client,
                        penjualan.description,
                        (CASE
                            WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            ELSE ''
                        END) as ukuran
                    FROM
                        penjualan
                    LEFT JOIN 
                        (select customer.cid, customer.nama_client from customer) customer
                    ON
                        penjualan.client = customer.cid 
                ) penjualan
            ON
                penjualan.oid = large_format.oid
            LEFT JOIN
                (
                    SELECT
                        pm_user.uid,
                        pm_user.username
                    FROM
                        pm_user
                ) operator
            ON
                operator.uid = large_format.uid
            
            WHERE
                ( large_format.cancel = '' or large_format.cancel = 'N' )
                $add_where
                $where_operator
            GROUP BY
                large_format.so_kerja
        ";
        $result = $conn_OOP->query($sql);
        $n = 0;
        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;
                $id_order = explode(",", "$d[id_order]");
                $client = explode(",", "$d[client]");
                $description = explode(",", "$d[description]");
                $ukuran = explode(",", "$d[ukuran]");
                $id_yes = explode(",", "$d[id_yes]");
                $count_oid = count($id_order);

                if ($id_yes[0] == 0) {
                    $ID_YESCOM = "";
                } else {
                    $ID_YESCOM = "$id_yes[0] - ";
                }

                if ($d['restan'] == 'Y') :
                    $icon_restan = "<i class='fad fa-recycle'></i>";
                else :
                    $icon_restan = "";
                endif;

                $total_cetak = (($d['panjang_potong'] * $d['lebar_potong']) / 10000) * $d['Jalan'];

                if ($d['status'] == 'rusak') :
                    $edit = "LaodFormLF(\"LargeFormat_Rusak\", \"" . $d['so_kerja'] . "\")";
                    $icon_rusak = "<i class='fas fa-window-close'></i> $d[kesalahan]";
                else :
                    $edit = "LaodFormLF(\"LargeFormat\", \"" . $d['so_kerja'] . "\")";
                    $icon_rusak = "";
                endif;

                echo "
                    <tr>
                        <td rowspan='$count_oid'>$n</td>
                        <td rowspan='$count_oid'>" . ucfirst($d['nama_operator']) . "</td>
                        <td class='a-center' rowspan='$count_oid'>" . date("d M Y", strtotime($d['tgl_cetak'])) . "</td>
                        <td onClick='$edit' rowspan='$count_oid' class='a-center pointer'>$d[so_kerja]</td>
                        <td onClick='$edit' class='a-center pointer'>$id_order[0]</td>
                        <td onClick='$edit' class='pointer'><b>$ID_YESCOM $client[0]</b> - $description[0] <b><i>Uk. $ukuran[0]</i></b> <span style='color:red; font-size:12px; padding-left:15px; font-style: italic;'>$icon_rusak</span></td>
                        <td onClick='$edit' class='pointer' rowspan='$count_oid'><span style='background-color:#f86e2b; padding:3px 4px; margin-right:3px; color:white; font-weight:bold'>$d[pass]</span> $d[kode_bahan] $icon_restan</td>
                        <td onClick='$edit' class='pointer' rowspan='$count_oid'>$d[ukuran_cetak]</td>
                        <td onClick='$edit' class='a-center' rowspan='$count_oid'>$d[qty_jalan]</td>
                        <td rowspan='$count_oid' class='a-right'>$total_cetak M<sup>2</sup></td>
                ";

                if ($_SESSION["level"] == "admin") :
                    echo "
                        <td rowspan='$count_oid'><span class='icon_status' onClick='hapus_SOLF($d[so_kerja])'><i class='far fa-trash-alt text-danger'></i></span></td>
                        </tr>
                    ";
                else :
                    echo "</tr>";
                endif;


                for ($i = 1; $i < $count_oid; $i++) :
                    if ($id_yes[$i] == 0) {
                        $ID_YESCOM = "";
                    } else {
                        $ID_YESCOM = "$id_yes[$i] - ";
                    }

                    echo "
                        <tr>
                            <td onClick='$edit' class='a-center pointer'>$id_order[$i]</td>
                            <td onClick='$edit' class='pointer'><b>$ID_YESCOM $client[$i]</b>  - $description[$i] <b><i>Uk. $ukuran[$i]</i></b> <span style='color:red; font-size:12px; padding-left:15px; font-style: italic;'>$icon_rusak</span></td>
                        </tr>
                    ";
                endfor;

                $total[]   = $total_cetak;
                $Nilai_total = array_sum($total);
            endwhile;

            echo "
                    <tr>
                        <th colspan='9'>Total Meter Cetak</th>
                        <th class='a-left' style='text-align:right; vertical-align:top; padding-right: 0.4em;'>" . number_format($Nilai_total) . " M<sup>2</sup></th>
                    </tr>
                ";
        endif;
        ?>
    </tbody>
</table>