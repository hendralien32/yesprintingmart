<?php
session_start();
require_once "../../function.php";
$test = "";
$bahan_Sort = isset($_POST['session_bahan']) ? $_POST['session_bahan'] : ' ';

if (isset($bahan_Sort)) :
    $test .= $bahan_Sort;
else :
    $test .= "$_SESSION[ListOrder_BahanDP]";
endif;

$_SESSION['ListOrder_BahanDP'] = "$test";

$session_MesinDP = "";
$mesin_sort = isset($_POST['type_mesin']) ? $_POST['type_mesin'] : ' ';

if (isset($mesin_sort)) :
    $session_MesinDP .= $mesin_sort;
else :
    $session_MesinDP .= "$_SESSION[session_MesinDP]";
endif;

$_SESSION['session_MesinDP'] = "$session_MesinDP";


if ($_SESSION['ListOrder_BahanDP'] != '') :
    $Add_Bahan = "and bahan='$_SESSION[ListOrder_BahanDP]'";
else :
    $Add_Bahan = "";
endif;

if ($_POST['data'] != "" and $_POST['date'] == "") :
    $add_where = "and ( customer.nama_client LIKE '%$_POST[data]%' or penjualan.oid LIKE '%$_POST[data]%' or penjualan.client_yes LIKE '%$_POST[data]%' or penjualan.id_yes LIKE '%$_POST[data]%' or penjualan.oid LIKE '%$_POST[data]%' )";
elseif ($_POST['date'] != "" and $_POST['data'] == "") :
    $add_where = "and LEFT( penjualan.waktu, 10 ) = '$_POST[date]'";
else :
    $add_where = "and penjualan.status != 'selesai'";
endif;

$cari_keyword = $_POST['data'];
$bold_cari_keyword = "<strong style='text-decoration:underline'>" . $_POST['data'] . "</strong>";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>

<table>
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="7%">Tanggal</th>
            <th width="3%">K</th>
            <th width="10%">Client</th>
            <th width="6%">ID</th>
            <th width="34%">Description</th>
            <th width="10%">Icons</th>
            <th width="8%">
                <select name="BahanSearch" id="BahanSearch" onchange="BahanSearch();">
                    <option value="">Kertas</option>
                    <?php
                    $sql =
                        "SELECT
                            bahan,
                            count(bahan) as qty
                        FROM
                            (
                                SELECT
                                    penjualan.oid,
                                    (CASE
                                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                                        ELSE penjualan.bahan
                                    END) as bahan
                                FROM
                                    penjualan
                                LEFT JOIN 
                                    (
                                        SELECT 
                                            barang.id_barang, 
                                            barang.nama_barang 
                                        FROM 
                                            barang
                                    ) barang
                                ON
                                    penjualan.ID_Bahan = barang.id_barang 
                                WHERE
                                    (   penjualan.kode = 'digital'
                                        -- or penjualan.kode='offset'
                                        -- or penjualan.kode='etc' 
                                    ) and
                                    penjualan.inv_check = 'Y' and
                                    penjualan.cancel != 'Y' and
                                    penjualan.status != 'selesai'
                            ) penjualan  
                        GROUP BY
                            bahan
                    ";

                    // Perform query
                    $result = $conn_OOP->query($sql);

                    if ($result->num_rows > 0) :
                        // output data of each row
                        while ($d = $result->fetch_assoc()) :
                            if ($d['bahan'] == "$test") {
                                $pilih = "selected";
                            } else {
                                $pilih = "";
                            }
                            echo "<option value='$d[bahan]' $pilih>$d[bahan] ($d[qty])</option>";
                        endwhile;
                    else :

                    endif;
                    ?>
                </select>
            </th>
            <th width="3%">Sisi</th>
            <th width="8%">Qty</th>
            <th width="8%">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql =
            "SELECT
                    penjualan.oid,
                    LEFT( penjualan.waktu, 10 ) as tanggal,
                    LEFT(penjualan.kode, 1) as code,
                    penjualan.kode as kode_barang,
                    penjualan.id_yes,
                    penjualan.so_yes,
                    penjualan.client as id_client,
                    customer.nama_client as client,
                    penjualan.client_yes,
                    penjualan.description,
                    CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                    (CASE
                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                        ELSE penjualan.bahan
                    END) as bahan,
                    penjualan.urgent,
                    (CASE
                        WHEN penjualan.laminate !='' THEN 'Y'
                        ELSE 'N'
                    END) as laminating,
                    (CASE
                        WHEN penjualan.potong !='' THEN 'Y'
                        WHEN penjualan.potong_gantung !='' THEN 'Y'
                        WHEN penjualan.pon !='' THEN 'Y'
                        WHEN penjualan.perporasi !='' THEN 'Y'
                        WHEN penjualan.CuttingSticker !='' THEN 'Y'
                        WHEN penjualan.Hekter_Tengah !='' THEN 'Y'
                        WHEN penjualan.Blok !='' THEN 'Y'
                        WHEN penjualan.Spiral !='' THEN 'Y'
                        WHEN penjualan.b_potong > 0 THEN 'Y'
                        ELSE 'N'
                    END) as finishing,
                    penjualan.sisi,
                    (CASE
                        WHEN penjualan.sisi = '1' THEN 'satu'
                        WHEN penjualan.sisi = '2' THEN 'dua'
                        ELSE ''
                    END) as css_sisi,
                    penjualan.status
                FROM
                    penjualan
                LEFT JOIN 
                    (
                        SELECT 
                            customer.cid, 
                            customer.nama_client 
                        FROM 
                            customer
                    ) customer
                ON
                    penjualan.client = customer.cid  
                LEFT JOIN 
                    (
                        SELECT 
                            barang.id_barang, 
                            barang.nama_barang 
                        FROM 
                            barang
                    ) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang 
                WHERE
                    (   penjualan.kode = 'digital'
                        -- or penjualan.kode='offset'
                        -- or penjualan.kode='etc' 
                    ) and
                    penjualan.inv_check = 'Y' and
                    penjualan.cancel != 'Y'
                    $add_where
                    $Add_Bahan
                ORDER BY
                    penjualan.oid
                ASC
            ";

        $n = 0;
        $result = $conn_OOP->query($sql);

        $jumlahQry = $result->num_rows;

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;

                $kode_class = str_replace(" ", "_", $d['kode_barang']);
                $array_kode = array("urgent", "laminating", "finishing");
                foreach ($array_kode as $kode) :
                    if ($d[$kode] != "" && $d[$kode] != "N") : ${'check_' . $kode} = "active";
                    else : ${'check_' . $kode} = "deactive";
                    endif;
                endforeach;

                if ($d['id_client'] == "1") :
                    $detail_yes = "<strong>";
                    if ($d['id_yes'] != "0") :
                        $detail_yes .= str_ireplace($cari_keyword, $bold_cari_keyword, $d['id_yes']);
                    else :
                        $detail_yes .= "";
                    endif;
                    if ($d['so_yes'] != "0") :
                        $detail_yes .= " / " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['so_yes']) . " - ";
                    else :
                        $detail_yes .= "";
                    endif;
                    $detail_yes .= "<span style='color:#f1592a'> " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['client_yes']) . "</span> </strong>";
                else :
                    $detail_yes = "";
                endif;

                if (($d['status']) == "selesai") {
                    $status = "<i class='fad fa-check-double'></i> Selesai";
                } elseif (($d['status']) == "proff") {
                    $status = "<i class='fad fa-hourglass-half'></i> Proffing";
                } else {
                    $status = "<i class='fad fa-spinner'></i> OnProgress";
                }

                if($_SESSION['session_MesinDP']!="") {
                    $edit = "LaodForm(\"DigitalPrinting\",\"$d[oid]\")";
                    $pointer = "pointer";
                } else {
                    $edit = "";
                    $pointer = "";
                }

                echo "
                        <tr>
                            <td>$n</td>
                            <td class='a-center'>" . date("d M Y", strtotime($d['tanggal'])) . "</td>
                            <td><span class='KodeProject " . $kode_class . "'>" . strtoupper($d['code']) . "</span></td>
                            <td onclick='" . $edit . "' class='$pointer'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['client']) . "</td>
                            <td onclick='" . $edit . "' class='a-center $pointer'>" . str_ireplace($cari_keyword, $bold_cari_keyword, $d['oid']) . "</td>
                            <td onclick='" . $edit . "' class='$pointer'>$detail_yes " . str_ireplace($cari_keyword, $bold_cari_keyword, $d['description']) . "</td>
                            <td>
                                <center>
                                    <span class='icon_status'><a href='print.php?type=print_oid&oid=$d[oid]' target='_blank' class='pointer'><i class='fad fa-print'></i></a></span>
                                    <span class='icon_status'><i class='fas fa-exclamation-triangle " . $check_urgent . "'></i></span>
                                    <span class='icon_status'><i class='fas fa-scalpel-path " . $check_finishing . "'></i></span>
                                    <span class='icon_status'><i class='fas fa-toilet-paper-alt " . $check_laminating . "'></i></span>
                                </center>
                            </td>
                            <td onclick='" . $edit . "' class='$pointer'>$d[bahan]</td>
                            <td class='a-center'><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></td>
                            <td class='a-right'>$d[qty]</td>
                            <td>$status</td>
                        </tr>
                    ";

            endwhile;
        endif;
        ?>
    </tbody>
</table>