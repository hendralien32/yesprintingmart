<?php
session_start();
require_once '../../function.php';

$n = 0;

$Setter = $_POST['Setter'];
$client = $_POST['client'];
$data = $_POST['data'];
$drTgl = $_POST['drTgl'];
$keTgl = $_POST['keTgl'];
$limit = $_POST['limit'];
$idSetter = "";

if (isset($Setter)) :
    $idSetter .= $Setter;
else :
    $idSetter .= "$_SESSION[Setter_ID]";
endif;

$_SESSION['Setter_ID'] = "$idSetter";

$SearchDate = 
    ($drTgl != "" and $keTgl != "")
        ? "and (LEFT( penjualan.waktu, 10 )>='$drTgl' and LEFT( penjualan.waktu, 10 )<='$keTgl')"
        : ((($drTgl != "" and $keTgl == "") || ($drTgl == "" and $keTgl != ""))
            ? "and (LEFT( penjualan.waktu, 10 )='$drTgl')"
            : ""
        );

$show_limit = 
    ( $client != "" || $data != "") 
        ? "LIMIT $limit" 
        : "";

$SearchSetter = 
    ( $Setter!="" )
        ? "and penjualan.setter = '$Setter'" 
        : "";

$SearchClient = 
    ( $client!="" )
        ? "and customer.nama_client LIKE '%$client%'" 
        : "";

$SearchData = 
    ( $data!="" )
        ? "and ( penjualan.description LIKE '%$data%' or penjualan.oid LIKE '%$data%' or penjualan.no_invoice LIKE '%$data%')" 
        : "";

// if else Ternary Operator (?:) dengan jumlah variable lebih dari satu
// ( $client!="" )
//     ? 
//         $SearchClient = "and customer.nama_client LIKE '%$client%'" xor 
//         $pClient = "client <b>$client</b>"
//     : 
//         $SearchClient = "" xor 
//         $pClient = "";

$sql =
    "SELECT
        penjualan.oid,
        penjualan.waktu as tanggal,
        UPPER(LEFT(penjualan.kode, 1)) as code,
        (CASE
            WHEN penjualan.kode = 'large format' THEN 'lf'
            WHEN penjualan.kode = 'digital' THEN 'dp'
            WHEN penjualan.kode = 'indoor' THEN 'il'
            WHEN penjualan.kode = 'Xuli' THEN 'ix'
            WHEN penjualan.kode = 'offset' THEN 'o'
            WHEN penjualan.kode = 'etc' THEN 'e'
            ELSE 'e'
        END) as kode_barang,
        penjualan.sisi,
        (CASE
            WHEN penjualan.status = 'selesai' THEN 'Y'
            WHEN penjualan.status = '' THEN 'N'
            ELSE 'N'
        END) as Finished,
        (CASE
            WHEN penjualan.no_invoice != '0' THEN 'Y'
            ELSE 'N'
        END) as invoice,
        (CASE
            WHEN penjualan.laminate !='' THEN 'Y'
            ELSE 'N'
        END) as laminating,
        (CASE
            WHEN penjualan.img_design !='' THEN 'Y'
            ELSE 'N'
        END) as image_design,
        (CASE
            WHEN penjualan.CuttingSticker ='Y' THEN 'Y'
            WHEN penjualan.potong ='Y' THEN 'Y'
            WHEN penjualan.potong_gantung ='Y' THEN 'Y'
            WHEN penjualan.pon ='Y' THEN 'Y'
            WHEN penjualan.perporasi ='Y' THEN 'Y'
            WHEN penjualan.Hekter_Tengah ='Y' THEN 'Y'
            WHEN penjualan.Blok ='Y' THEN 'Y'
            WHEN penjualan.Spiral ='Y' THEN 'Y'
            WHEN penjualan.b_potong > 0 THEN 'Y'
            ELSE 'N'
        END) as finishing,
        (CASE
            WHEN barang.id_barang > 0 THEN barang.nama_barang
            ELSE penjualan.bahan
        END) as bahan,
        CONCAT('<b>', penjualan.qty, '</b> ' ,penjualan.satuan) as qty,
        (CASE
            WHEN ( penjualan.panjang > 0 || penjualan.lebar ) > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            ELSE ''
        END) as ukuran,
        penjualan.description,
        customer.nama_client,
        customer.no_telp,
        setter.nama as Nama_Setter,
        (CASE
            WHEN penjualan.pembayaran = 'lunas' THEN 'Y'
            ELSE 'N'
        END) as pembayaran,
        (CASE
            WHEN penjualan.akses_edit = 'Y' THEN 'Y'
            ELSE 'N'
        END) as akses_edit
    FROM
        penjualan
    LEFT JOIN 
        (select customer.cid, customer.nama_client, customer.no_telp from customer) customer
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
    WHERE
        penjualan.oid != '' and
        penjualan.client !='1'
        $SearchSetter
        $SearchData
        $SearchDate
        $SearchClient
    ORDER BY
        penjualan.oid
    DESC
    $show_limit
";

// Perform query
$result = $conn_OOP->query($sql);
$jumlah_order = $result->num_rows;
?>

<span class='display-none' id='jumlah_order'><?= number_format($jumlah_order) ?> Order</span>

<div class='content-table'>
    <table class='table-list'>
        <tr>
            <th width="2%">#</th>
            <th width="8%">Tanggal</th>
            <th width="6%">Order ID</th>
            <th width="43%">Description</th>
            <th width="8%">Icon</th>
            <th width="2%">Sisi</th>
            <th width="14%">Bahan</th>
            <th width="8%">Qty</th>
            <th width="6%">
                <select id="search_Setter" onchange="SearchSetter();">
                    <option value="">Setter</option>
                    <?php
                    $setter_SQL =
                        "SELECT
                            penjualan.setter,
                            (CASE
                                WHEN setter.nama != '' THEN CONCAT(setter.nama, ' (' ,COUNT(penjualan.setter), ')')
                                ELSE CONCAT('- Blank - (' ,COUNT(penjualan.setter), ')')
                            END) as nama
                        FROM
                            penjualan
                        LEFT JOIN 
                            (select pm_user.uid, pm_user.nama from pm_user) setter
                        ON
                            penjualan.setter = setter.uid
                        LEFT JOIN 
                            (select customer.cid, customer.nama_client, customer.no_telp from customer) customer
                        ON
                            penjualan.client = customer.cid  
                        WHERE
                            penjualan.oid != '' and
                            penjualan.client !='1'
                            $SearchData
                            $SearchDate
                            $SearchClient
                        GROUP BY
                            penjualan.setter
                        ORDER BY
                            setter.nama
                        ASC
                        $show_limit
                        ";

                    $result_Setter = $conn_OOP->query($setter_SQL);

                    if ($result_Setter->num_rows > 0) :
                        while ($d = $result_Setter->fetch_assoc()) :
                            $pilih = $d['setter'] == "$Setter" ? "selected" : "";
                            echo "<option value='$d[setter]' $pilih>". ucwords($d['nama']) ."</option>";
                        endwhile;
                    endif;
                    ?>
                </select>
            </th>
            <th width="2%"> </th>
        </tr>
        <?php
        if ($jumlah_order > 0) {
            // output data of each row
            while ($d = $result->fetch_assoc()) :
                $n++;
                $tanggal = date_format(date_create($d['tanggal']), 'j M Y');
                $Waktu = date_format(date_create($d['tanggal']), 'h:i A');
                $No_telp = "$d[no_telp]";

                if (strlen($No_telp) == 12) :
                    $telp = sprintf(
                        "- %s-%s-%s",
                        substr($No_telp, 0, 4),
                        substr($No_telp, 4, 4),
                        substr($No_telp, 8)
                    );
                elseif (strlen($No_telp) == 10) :
                    $telp = sprintf(
                        "- %s-%s-%s",
                        substr($No_telp, 0, 4),
                        substr($No_telp, 4, 3),
                        substr($No_telp, 7)
                    );
                elseif (strlen($No_telp) == 7) :
                    $telp = sprintf(
                        "- (061) %s-%s",
                        substr($No_telp, 0, 3),
                        substr($No_telp, 3, 4)
                    );
                else :
                    $telp = "";
                endif;

                $array_kode = array(
                    "Finished",
                    "pembayaran",
                    "akses_edit",
                    "invoice",
                    "finishing",
                    "laminating",
                    "image_design"
                );
                foreach ($array_kode as $kode) {
                    if ($d[$kode] != "" && $d[$kode] != "N") :
                        ${'check_' . $kode} = "active";
                        ${'pointer_' . $kode} = "pointer";
                    else :
                        ${'check_' . $kode} = "";
                        ${'pointer_' . $kode} = "default";
                    endif;
                }

                echo "
                <tr>
                    <td><center>$n</center></td>
                    <td>
                        <div class='test'>
                            <span class='client_name'>
                                <center>$tanggal</center>
                            </span>
                            <span>
                                <center>$Waktu</center>
                            </span>
                        </div>
                    </td>
                    <td><center>$d[oid]</center></td>
                    <td class='deskripsi'>
                        <div class='$d[kode_barang]'>
                            <span>
                                $d[code]
                            </span>
                        </div>
                        <div class='test'>
                            <span class='client_name'>
                                $d[nama_client] $telp
                            </span>
                            <span>
                                $d[description] $d[ukuran]
                            </span>
                        </div>
                    </td>
                    <td class='status_icon'>
                        <div>
                            <span class='$check_akses_edit'><i class='fas fa-pen'></i></span>
                            <span class='$check_invoice default'><i class='fas fa-receipt'></i></span>
                            <span class='$check_pembayaran default'><i class='fas fa-cash-register'></i></span>
                            <span class='active pointer'><i class='fas fa-file-alt'></i></span>
                        </div>
                        <div>
                            <span class='$check_Finished' ondblclick='finished($d[oid],\"$d[Finished]\")'><i class='fas fa-check-double'></i></span>
                            <span class='$check_finishing default'><i class='fas fa-cut'></i></span>
                            <span class='$check_laminating default'><i class='fas fa-toilet-paper-alt'></i></span>
                            <span class='$check_image_design $pointer_image_design'><i class='fas fa-file-image'></i></span>
                        </div>
                    </td>
                    <td><center><b>$d[sisi]</b></center></td>
                    <td>$d[bahan]</td>
                    <td>$d[qty]</td>
                    <td>$d[Nama_Setter]</td>
                    <td><center><i class='fas fa-trash pointer'></i></center></td>
                </tr>
                ";
            endwhile;
        } else {
            echo "
                <tr>
                    <td colspan='10' class='data_tidak_ditemukan'><center><i class='far fa-times-circle'></i> Data tidak ditemukan</center></td>
                </tr>
            ";
        }

        $result->close();
        ?>
    </table>
</div>