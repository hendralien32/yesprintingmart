<?php
session_start();
require_once "../../function.php";

$sql_query =
    "SELECT
            GROUP_CONCAT(penjualan.oid) as oid,
            GROUP_CONCAT((CASE
                WHEN penjualan.kode = 'large format' THEN 'Large Format'
                WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                WHEN penjualan.kode = 'etc' THEN 'ETC'
                ELSE '- - -'
            END)) as kode,
            customer.nama_client,
            GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
            GROUP_CONCAT((CASE
                WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                ELSE 'A3+'
            END)) as ukuran,
            GROUP_CONCAT((CASE
                WHEN barang.id_barang > 0 THEN barang.nama_barang
                ELSE penjualan.bahan
            END)) as bahan,
            GROUP_CONCAT(penjualan.sisi) as sisi,
            GROUP_CONCAT(CONCAT(penjualan.qty, ' ' ,penjualan.satuan)) as qty,
            GROUP_CONCAT((CASE
                WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating'
                WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                ELSE '- - -'
            END)) as laminating,
            GROUP_CONCAT((CASE
                WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                ELSE '- - -'
            END)) as alat_tambahan,
            GROUP_CONCAT((CASE
                WHEN penjualan.potong = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as potong,
            GROUP_CONCAT((CASE
                WHEN penjualan.potong_gantung = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as potong_gantung,
            GROUP_CONCAT((CASE
                WHEN penjualan.pon = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as pon,
            GROUP_CONCAT((CASE
                WHEN penjualan.perporasi = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as perporasi,
            GROUP_CONCAT((CASE
                WHEN penjualan.CuttingSticker = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as CuttingSticker,
            GROUP_CONCAT((CASE
                WHEN penjualan.Hekter_Tengah = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as Hekter_Tengah,
            GROUP_CONCAT((CASE
                WHEN penjualan.Blok = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as Blok,
            GROUP_CONCAT((CASE
                WHEN penjualan.Spiral = 'Y' THEN 'Y'
                ELSE 'N'
            END)) as Spiral,
            GROUP_CONCAT(penjualan.b_digital) as b_digital,
            GROUP_CONCAT(penjualan.b_xbanner) as b_xbanner,
            GROUP_CONCAT(penjualan.b_lain) as b_lain,
            GROUP_CONCAT(penjualan.b_offset) as b_offset,
            GROUP_CONCAT(penjualan.b_large) as b_large,
            GROUP_CONCAT(penjualan.b_kotak) as b_kotak,
            GROUP_CONCAT(penjualan.b_laminate) as b_laminate,
            GROUP_CONCAT(penjualan.b_potong) as b_potong,
            GROUP_CONCAT(penjualan.b_design) as b_design,
            GROUP_CONCAT(penjualan.b_indoor) as b_indoor,
            GROUP_CONCAT(penjualan.b_delivery) as b_delivery,
            GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)) as harga_satuan,
            GROUP_CONCAT(penjualan.discount) as discount,
            GROUP_CONCAT((((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty)) as total
        FROM
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid  
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) barang
        ON
            penjualan.ID_Bahan = barang.id_barang  
        WHERE
            penjualan.no_invoice = $_POST[data] and
            penjualan.cancel != 'Y'
        GROUP BY
            penjualan.no_invoice
        ORDER BY
            penjualan.oid
        DESC
    ";

// Perform query
$result = $conn_OOP->query($sql_query);

if ($result->num_rows > 0) :
    // output data of each row
    $row = $result->fetch_assoc();

    $oid = explode(",", "$row[oid]");
    $description = explode("*_*", "$row[description]");
    $ukuran = explode(",", "$row[ukuran]");
    $bahan = explode(",", "$row[bahan]");
    $sisi = explode(",", "$row[sisi]");
    $qty = explode(",", "$row[qty]");
    $kode = explode(",", "$row[kode]");
    $laminating = explode(",", "$row[laminating]");
    $alat_tambahan = explode(",", "$row[alat_tambahan]");
    $potong = explode(",", "$row[potong]");
    $potong_gantung = explode(",", "$row[potong_gantung]");
    $pon = explode(",", "$row[pon]");
    $perporasi = explode(",", "$row[perporasi]");
    $CuttingSticker = explode(",", "$row[CuttingSticker]");
    $Hekter_Tengah = explode(",", "$row[Hekter_Tengah]");
    $Blok = explode(",", "$row[Blok]");
    $Spiral = explode(",", "$row[Spiral]");
    $b_digital = explode(",", "$row[b_digital]");
    $b_xbanner = explode(",", "$row[b_xbanner]");
    $b_lain = explode(",", "$row[b_lain]");
    $b_offset = explode(",", "$row[b_offset]");
    $b_large = explode(",", "$row[b_large]");
    $b_kotak = explode(",", "$row[b_kotak]");
    $b_laminate = explode(",", "$row[b_laminate]");
    $b_potong = explode(",", "$row[b_potong]");
    $b_design = explode(",", "$row[b_design]");
    $b_indoor = explode(",", "$row[b_indoor]");
    $b_delivery = explode(",", "$row[b_delivery]");
    $discount = explode(",", "$row[discount]");
    $harga_satuan = explode(",", "$row[harga_satuan]");
    $total = explode(",", "$row[total]");
    $count_oid = count($oid);
else : endif;
?>

<h3 class='title_form'>Check Invoice Penjualan <span style="text-decoration:underline"><?= $row['nama_client'] ?></span> dengan No. Invoice <span style="text-decoration:underline">#<?= $_POST['data'] ?></span></h3>

<table class='table_checkInv'>
    <thead>
        <tr>
            <th width="1%">#</th>
            <th width="5%">OID</th>
            <th width="48%" colspan="2">Deskripsi</th>
            <th width="14%">Finishing</th>
            <th width="32%" colspan="2">Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < $count_oid; $i++) :
            $n = $i + 1;

            if ($potong[$i] == "Y") :
                $potong_X = "<i class='fad fa-check-square'></i>";
            else :
                $potong_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($potong_gantung[$i] == "Y") :
                $potong_gantung_X = "<i class='fad fa-check-square'></i>";
            else :
                $potong_gantung_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($pon[$i] == "Y") :
                $pon_X = "<i class='fad fa-check-square'></i>";
            else :
                $pon_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($perporasi[$i] == "Y") :
                $perporasi_X = "<i class='fad fa-check-square'></i>";
            else :
                $perporasi_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($CuttingSticker[$i] == "Y") :
                $CuttingSticker_X = "<i class='fad fa-check-square'></i>";
            else :
                $CuttingSticker_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($Hekter_Tengah[$i] == "Y") :
                $Hekter_Tengah_X = "<i class='fad fa-check-square'></i>";
            else :
                $Hekter_Tengah_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($Blok[$i] == "Y") :
                $Blok_X = "<i class='fad fa-check-square'></i>";
            else :
                $Blok_X = "<i class='fad fa-times-square'></i>";
            endif;

            if ($Spiral[$i] == "Y") :
                $Spiral_X = "<i class='fad fa-check-square'></i>";
            else :
                $Spiral_X = "<i class='fad fa-times-square'></i>";
            endif;

            switch ($kode[$i]):
                case "Large Format":
                    $finishing = "
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Stiker Cutting</b></td>
                                <td>$CuttingSticker_X</td>
                            </tr>
                        </table>
                        ";
                    $harga = "
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>Biaya LF</b></td>
                                    <td>" . number_format($b_large[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>By Alat Tambahan</b></td>
                                    <td>" . number_format($b_xbanner[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Laminating</b></td>
                                    <td>" . number_format($b_laminate[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Finishing</b></td>
                                    <td>" . number_format($b_potong[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Delivery</b></td>
                                    <td>" . number_format($b_delivery[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                case "Digital Printing":
                    $finishing = "
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Potong Putus</b></td>
                                <td>$potong_X</td>
                            </tr>
                            <tr>
                                <td><b>Potong Gantung</b></td>
                                <td>$potong_gantung_X</td>
                            </tr>
                            <tr>
                                <td><b>Pon Garis</b></td>
                                <td>$pon_X</td>
                            </tr>
                            <tr>
                                <td><b>Perporasi</b></td>
                                <td>$perporasi_X</td>
                            </tr>
                            <tr>
                                <td><b>Stiker Cutting</b></td>
                                <td>$CuttingSticker_X</td>
                            </tr>
                        </table>
                        ";
                    $harga = "
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>Biaya Digital</b></td>
                                    <td>" . number_format($b_digital[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Kotak</b></td>
                                    <td>" . number_format($b_kotak[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Laminating</b></td>
                                    <td>" . number_format($b_laminate[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Finishing</b></td>
                                    <td>" . number_format($b_potong[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Design</b></td>
                                    <td>" . number_format($b_design[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Delivery</b></td>
                                    <td>" . number_format($b_delivery[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                case "Indoor HP Latex":
                    $finishing = "
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Stiker Cutting</b></td>
                                <td>$CuttingSticker_X</td>
                            </tr>
                        </table>
                        ";
                    $harga = "
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>Biaya Indoor</b></td>
                                    <td>" . number_format($b_indoor[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>By Alat Tambahan</b></td>
                                    <td>" . number_format($b_xbanner[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Laminating</b></td>
                                    <td>" . number_format($b_laminate[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Finishing</b></td>
                                    <td>" . number_format($b_potong[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Delivery</b></td>
                                    <td>" . number_format($b_delivery[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                case "Indoor Xuli":
                    $finishing = "
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Stiker Cutting</b></td>
                                <td>$CuttingSticker_X</td>
                            </tr>
                        </table>
                        ";
                    $harga = "
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>Biaya Indoor</b></td>
                                    <td>" . number_format($b_indoor[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>By Alat Tambahan</b></td>
                                    <td>" . number_format($b_xbanner[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Laminating</b></td>
                                    <td>" . number_format($b_laminate[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Finishing</b></td>
                                    <td>" . number_format($b_potong[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Biaya Delivery</b></td>
                                    <td>" . number_format($b_delivery[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                case "Offset Printing":
                    $finishing = "";
                    $harga = "
                        <td>
                            <b>Biaya Offset</b>" . number_format($b_offset[$i]) . " <br>
                            <b>Biaya Delivery</b>" . number_format($b_delivery[$i]) . " <br>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                case "ETC":
                    $finishing = "
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Hekter Tengah</b></td>
                                <td>$Hekter_Tengah_X</td>
                            </tr>
                            <tr>
                                <td><b>Blok</b></td>
                                <td>$Blok_X</td>
                            </tr>
                            <tr>
                                <td><b>Spiral</b></td>
                                <td>$Spiral_X</td>
                            </tr>
                        </table>
                        ";
                    $harga = "
                        <td>
                            <b>Biaya lain</b>" . number_format($b_lain[$i]) . " <br>
                            <b>Biaya Finishing</b>" . number_format($b_potong[$i]) . " <br>
                            <b>Biaya Delivery</b>" . number_format($b_delivery[$i]) . " <br>
                        </td>
                        <td>
                            <table class='detail_checkInv'>
                                <tr>
                                    <td><b>@ Harga</b></td>
                                    <td>" . number_format($harga_satuan[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td>" . number_format($discount[$i]) . "</td>
                                </tr>
                                <tr>
                                    <td><b>Total Harga</b></td>
                                    <td>" . number_format($total[$i]) . "</td>
                                </tr>
                            </table>
                        </td>
                        ";
                    break;
                default:
                    $finishing = "";
                    $harga = "";
            endswitch;

            echo "
                <tr>
                    <td>$n</td>
                    <td>$oid[$i]</td>
                    <td>
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Kode Barang</b></td>
                                <td>$kode[$i]</td>
                            </tr>
                            <tr>
                                <td><b>Ukuran</b></td>
                                <td>$ukuran[$i]</td>
                            </tr>
                            <tr>
                                <td><b>Bahan</b></td>
                                <td>$bahan[$i]</td>
                            </tr>
                            <tr>
                                <td><b>Sisi</b></td>
                                <td>$sisi[$i]</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class='detail_checkInv'>
                            <tr>
                                <td><b>Qty</b></td>
                                <td>$qty[$i]</td>
                            </tr>
                            <tr>
                                <td><b>laminating</b></td>
                                <td>$laminating[$i]</td>
                            </tr>
                            <tr>
                                <td><b>Alat Tambahan</b></td>
                                <td>$alat_tambahan[$i]</td>
                            </tr>
                        </table>
                    </td>
                    <td>$finishing</td>
                    $harga
                </tr>
                ";
        endfor;
        ?>
    </tbody>
</table>

<div id="submit_menu">
    <button onclick="check_invoice('<?= $_POST['data'] ?>', '<?= $_SESSION['username'] ?>')" id="submitBtn">Invoice Sudah di CHECK</button>
</div>
<div id="Result">

</div>