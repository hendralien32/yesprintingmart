<?php

session_start();
require_once "../../function.php";

?>

<h3 class='title_form'><?= 'List SO Pemotongan Untuk Order ID No. ' . $_POST['ID_Order'] ?></h3>

<div class='row'>
    <table class='form_table'>
        <tr>
            <th width="1%">#</th>
            <th width="9%">Nomor SO</th>
            <th width="32%">List ID</th>
            <th width="12%">Bahan</th>
            <th width="8%">Ukuran</th>
            <th width="8%">Qty Cetak</th>
            <th width="15%">Kode Bahan</th>
            <th width="9%">Uk. Cetak</th>
            <th width="7%">Qty Cetak</th>
        </tr>

        <?php
        $sql =
            "SELECT
                large_format.so_kerja,
                large_format.kode_bahan,
                large_format.qty_jalan,
                large_format.Uk_Potong,
                large_format.status,
                large_format.kesalahan,
                GROUP_CONCAT(detail_LF.oid SEPARATOR '*_*') as oid,
                GROUP_CONCAT(detail_LF.id_yes SEPARATOR '*_*') as id_yes,
                GROUP_CONCAT(detail_LF.client SEPARATOR '*_*') as client,
                GROUP_CONCAT(detail_LF.description SEPARATOR '*_*') as deskripsi,
                GROUP_CONCAT(detail_LF.ukuran SEPARATOR '*_*') as ukuran,
                GROUP_CONCAT(detail_LF.bahan SEPARATOR '*_*') as bahan,
                GROUP_CONCAT(detail_LF.qty_cetak SEPARATOR '*_*') as qty_cetak
            FROM
                (
                    SELECT
                        large_format.so_kerja,
                        large_format.kode_bahan,
                        large_format.qty_jalan,
                        CONCAT(large_format.panjang_potong, ' X ', large_format.lebar_potong, ' Cm') as Uk_Potong,
                        large_format.status,
                        large_format.kesalahan
                    FROM
                        large_format
                    WHERE
                        large_format.oid = '$_POST[ID_Order]' and
                        ( large_format.cancel = '' or large_format.cancel = 'N' )
                ) large_format
            LEFT JOIN
                ( 
                    SELECT
                        large_format.oid,
                        large_format.so_kerja,
                        large_format.qty_cetak,
                        penjualan.description,
                        penjualan.ukuran,
                        penjualan.bahan,
                        penjualan.client,
                        penjualan.id_yes
                    FROM
                        large_format
                    LEFT JOIN
                        (
                            SELECT
                                penjualan.oid,
                                penjualan.description,
                                penjualan.id_yes,
                                (CASE
                                    WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                    WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                    ELSE ''
                                END) as ukuran,
                                (CASE
                                    WHEN barang.id_barang > 0 THEN barang.nama_barang
                                    ELSE penjualan.bahan
                                END) as bahan,
                                (CASE
                                    WHEN penjualan.client_yes != '' THEN penjualan.client_yes
                                    ELSE customer.nama_client 
                                END) AS client
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
                        ) penjualan
                    ON
                        large_format.oid = penjualan.oid
                    WHERE
                    ( large_format.cancel = '' or large_format.cancel = 'N' )
                ) detail_LF
            ON
                large_format.so_kerja = detail_LF.so_kerja
            GROUP BY
                large_format.so_kerja
        ";

        $result = $conn_OOP->query($sql);

        $n = 0;

        if ($result->num_rows > 0) :
            while ($d = $result->fetch_assoc()) :
                $n++;
                $oid = explode("*_*", "$d[oid]");
                $id_yes = explode("*_*", "$d[id_yes]");
                $client = explode("*_*", "$d[client]");
                $deskripsi = explode("*_*", "$d[deskripsi]");
                $ukuran = explode("*_*", "$d[ukuran]");
                $bahan = explode("*_*", "$d[bahan]");
                $qty_cetak = explode("*_*", "$d[qty_cetak]");
                $count_oid = count($oid);

                if ($id_yes[0] != '0') :
                    $Yes_ID = " ( $id_yes[0] )";
                else :
                    $Yes_ID = "";
                endif;

                if($d['status'] == 'rusak') :
                    $icon_rusak = "<i class='fas fa-window-close'></i> $d[kesalahan]";
                else :
                    $icon_rusak = "";
                endif;

                echo "
                    <tr>
                        <td rowspan='$count_oid'>$n</td>
                        <td rowspan='$count_oid' class='a-center'>$d[so_kerja]</td>
                        <td>$oid[0] - <strong>$client[0] $Yes_ID</strong> - $deskripsi[0] <span style='color:red; font-size:12px; padding-left:15px; font-style: italic;'>$icon_rusak</span></td>
                        <td>$bahan[0]</td>
                        <td class='a-center'>$ukuran[0]</td>
                        <td class='a-center'>$qty_cetak[0] Pcs</td>
                        <td rowspan='$count_oid'>$d[kode_bahan]</td>
                        <td rowspan='$count_oid'>$d[Uk_Potong]</td>
                        <td rowspan='$count_oid' class='a-center'>$d[qty_jalan]x</td>
                    </tr>
                ";
                for ($i = 1; $i < $count_oid; $i++) :
                    if ($id_yes[$i] != '0') :
                        $XYes_ID = " ( $id_yes[$i] )";
                    else :
                        $XYes_ID = "";
                    endif;

                    echo "
                        <tr>
                            <td>$oid[$i] - <strong>$client[$i] $XYes_ID</strong> - $deskripsi[$i] <span style='color:red; font-size:12px; padding-left:15px; font-style: italic;'>$icon_rusak</span></td>
                            <td>$bahan[$i]</td>
                            <td class='a-center'>$ukuran[$i]</td>
                            <td class='a-center'>$qty_cetak[$i] Pcs</td>
                        </tr>
                    ";
                endfor;
            endwhile;
        endif;
        ?>
    </table>
</div>