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
        $add_where = "and ( flow_barang.tanggal>='$date_from-01' and flow_barang.tanggal<='$date_to-31' )";
    } else {
        $add_where = "and ( flow_barang.tanggal>='$months-01' and flow_barang.tanggal<='$months-31' )";
    }
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
                flow_barang.fid,
                flow_barang.no_do,
                flow_barang.tanggal,
                GROUP_CONCAT(flow_barang.barang_masuk) as barang_masuk,
                GROUP_CONCAT(flow_barang.barang_keluar) as barang_keluar,
                GROUP_CONCAT(flow_barang.harga_barang) as harga_barang,
                GROUP_CONCAT((CASE
                    WHEN barang.nama_barang != '' THEN barang.nama_barang
                    WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
                    ELSE '- - -'
                END)) as nama_barang,
                flow_barang.hapus
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
            GROUP BY
                flow_barang.no_do
        ";
        echo "$sql";
        $n = 0;
        $result = $conn_OOP->query($sql);

        $jumlahQry = $result->num_rows;

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;
                $barang_masuk = explode(",", "$d[barang_masuk]");
                $barang_keluar = explode(",", "$d[barang_keluar]");
                $harga_barang = explode(",", "$d[harga_barang]");
                $nama_barang = explode(",", "$d[nama_barang]");
                $Count_NamaBrg = count($nama_barang);

                if($barang_masuk[0]!="0") {
                    $qty = "$barang_masuk[0]";
                    $icon = "<span class='icon_status'><i class='fas fa-arrow-alt-down' style='color:green'></i></span>";
                } else {
                    $qty = "$barang_keluar[0]";
                    $icon = "<span class='icon_status'><i class='fas fa-arrow-alt-up' style='color:red'></i></span>";
                }

                if ($_SESSION['level'] == "admin") {
                    $icon_hapus = "<span class='icon_status pointer' ondblclick='hapus(\"". $d['no_do'] ."\", \"". $d['no_do'] ."\", \"". $d['hapus'] ."\")'><i class='far fa-trash-alt text-danger'></i></span>";
                } else {
                    $icon = "";
                }

                $edit = "LaodForm(\"Tambah_StockDP\",\"$d[no_do]\")";

                echo "
                    <tr>
                        <td rowspan='$Count_NamaBrg'>$n</td>
                        <td rowspan='$Count_NamaBrg' class='a-center pointer' onclick='$edit'> " . date("d F Y", strtotime($d['tanggal'])) . "</td>
                        <td rowspan='$Count_NamaBrg' onclick='$edit' class='pointer'>$d[no_do]</td>
                        <td onclick='$edit' class='pointer'>$nama_barang[0]</td>
                        <td class='a-right'><strong>". number_format($qty) ."</strong> Lembar</td>
                        <td class='a-right'>". number_format($harga_barang[0]) ."</td>
                        <td class='a-right'>". number_format($qty * $harga_barang[0]) ."</td>
                        <td rowspan='$Count_NamaBrg' class='a-center'>
                            $icon
                            $icon_hapus
                        </td>
                    </tr>
                ";

                for ($i = 1; $i < $Count_NamaBrg; $i++) :
                    if($barang_masuk[$i]!="0") {
                        $qtyX = "$barang_masuk[$i]";
                    } else {
                        $qtyX = "$barang_keluar[$i]";
                    }

                    echo "
                        <tr>
                            <td onclick='$edit' class='pointer'>$nama_barang[$i]</td>
                            <td class='a-right'><strong>". number_format($qtyX) ."</strong> Lembar</td>
                            <td class='a-right'>". number_format($harga_barang[$i]) ."</td>
                            <td class='a-right'>". number_format($qtyX * $harga_barang[$i]) ."</td>
                        </tr>
                    ";
                endfor;
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