<?php
session_start();
require_once "../../function.php";

$search = isset($_POST['search']) ? $_POST['search'] : "";
$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : "";
$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : "";

if ($search != "") {
    $add_where = "and ( flow_barang.no_do LIKE '%$search%' or barang.nama_barang LIKE '%$search%' or barang_Kode.nama_barang LIKE '%$search%' )";
} else {
    if ($date_from != "" and $date_to == "") {
        $add_where = "and ( flow_barang.tanggal>='$date_from-01' and flow_barang.tanggal <='$date_from-31' )";
    } elseif ($date_from == "" and $date_to != "") {
        $add_where = "and ( flow_barang.tanggal>='$date_to-01' and flow_barang.tanggal <='$date_to-31' )";
    } elseif ($date_from != "" and $date_to != "") {
        $add_where = "and ( flow_barang.tanggal>='$_POST[date_from]-01' and flow_barang.tanggal<='$date_to-31' )";
    } else {
        $add_where = "and ( flow_barang.tanggal>='$months-01' and flow_barang.tanggal<='$months-31' )";
    }
}

?>

<table>
    <tr>
        <th width="2%">#</th>
        <th width="10%">Tanggal</th>
        <th width="10%">No. DO</th>
        <th width="42%">Nama Bahan</th>
        <th width="10%">Qty</th>
        <th width="10%">Harga</th>
        <th width="10%">Total Harga</th>
        <th width="6%">Icon</th>
    </tr>

    <?php
        $sql =
            "SELECT
                flow_barang.no_do,
                flow_barang.tanggal,
                flow_barang.barang_masuk,
                flow_barang.harga_barang,
                (CASE
                    WHEN barang.nama_barang != '' THEN barang.nama_barang
                    WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
                    ELSE '- - -'
                END) as nama_barang
            FROM
                flow_barang
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
                barang.id_barang = flow_barang.ID_Bahan
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
                barang_Kode.kode_barang = flow_barang.kode_barang
            WHERE
                ( flow_barang.hapus = '' or flow_barang.hapus = 'N' ) and
                ( barang.nama_barang != '' or barang_Kode.nama_barang != '' )
                $add_where
        ";

        $n = 0;
        $result = $conn_OOP->query($sql);

        $jumlahQry = $result->num_rows;

        echo "<input type='hidden' id='total_check' value='$jumlahQry'>";

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;

                if($d['barang_masuk']!="0") {
                    $qty = "$d[barang_masuk]";
                    $icon = "<span class='icon_status'><i class='fas fa-arrow-alt-down' style='color:green'></i></span>";
                } else {
                    $qty = "$d[barang_keluar]";
                    $icon = "<span class='icon_status'><i class='fas fa-arrow-alt-up' style='color:red'></i></span>";
                }

                echo "
                    <tr>
                        <td>$n</td>
                        <td class='a-center'> " . date("d F Y", strtotime($d['tanggal'])) . "</td>
                        <td>$d[no_do]</td>
                        <td>$d[nama_barang]</td>
                        <td class='a-right'><strong>". number_format($qty) ."</strong> Lembar</td>
                        <td class='a-right'>". number_format($d['harga_barang']) ."</td>
                        <td class='a-right'>". number_format($d['barang_masuk'] * $d['harga_barang']) ."</td>
                        <td class='a-center'>
                            $icon
                            <span class='icon_status'><i class='far fa-trash-alt text-danger'></i></span>
                        </td>
                    </tr>
                ";
            endwhile;
        else :
            echo "
                <tr>
                    <td colspan='8'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                </tr>
            ";
        endif;
    ?>
</table>