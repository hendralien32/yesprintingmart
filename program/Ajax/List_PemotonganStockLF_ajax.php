<?php
session_start();

require_once '../../function.php';

if ($_POST['search'] != "") {
    $add_where = "and ( large_format.oid LIKE '%$_POST[search]%' or penjualan.id_yes LIKE '%$_POST[search]%' or penjualan.description LIKE '%$_POST[search]%' or penjualan.client LIKE '%$_POST[search]%' )";
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

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="1%">#</th>
            <th width="8%">Operator</th>
            <th width="8%">Tanggal</th>
            <th width="8%">SO Pemotongan</th>
            <th width="7%">ID</th>
            <th width="33%">ID Order - Description</th>
            <th width="15%">Kode Bahan</th>
            <th width="8%">Ukuran Cetak</th>
            <th width="5%">Qty Jln</th>
            <th width="8%">Total Potong</th>
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
                    else '- - -'
                END) as kode_bahan,
                large_format.pass
            FROM
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
                large_format.cancel = ''
                $add_where
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

                $total_cetak = (($d['panjang_potong'] * $d['lebar_potong']) / 10000) * $d['Jalan'];

                echo "
                    <tr>
                        <td rowspan='$count_oid'>$n</td>
                        <td rowspan='$count_oid'>" . ucfirst($d['nama_operator']) . "</td>
                        <td rowspan='$count_oid'>" . date("d M Y", strtotime($d['tgl_cetak'])) . "</td>
                        <td rowspan='$count_oid' class='a-center'>$d[so_kerja]</td>
                        <td class='a-center'>$id_order[0]</td>
                        <td><b>$ID_YESCOM $client[0]</b> - $description[0] Uk. $ukuran[0]</td>
                        <td rowspan='$count_oid'><span style='background-color:#f86e2b; padding:3px 4px; margin-right:3px; color:white'>$d[pass]</span> $d[kode_bahan]</td>
                        <td rowspan='$count_oid'>$d[ukuran_cetak]</td>
                        <td class='a-center' rowspan='$count_oid'>$d[qty_jalan]</td>
                        <td rowspan='$count_oid'>$total_cetak M<sup>2</sup></td>
                    </tr>
                ";
                for ($i = 1; $i < $count_oid; $i++) :
                    if ($id_yes[$i] == 0) {
                        $ID_YESCOM = "";
                    } else {
                        $ID_YESCOM = "$id_yes[$i] - ";
                    }

                    echo "
                        <tr>
                            <td class='a-center'>$id_order[$i]</td>
                            <td><b>$ID_YESCOM $client[$i]</b>  - $description[$i] Uk. $ukuran[$i]</td>
                        </tr>
                    ";
                endfor;
            endwhile;
        endif;
        ?>
    </tbody>
</table>