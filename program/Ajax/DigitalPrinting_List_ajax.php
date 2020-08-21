<?php
session_start();
require_once "../../function.php";
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>

<table>
    <thead>
        <tr>
            <th width="2%">#</th>
            <th width="8%">Tanggal</th>
            <th width="3%">K</th>
            <th width="10%">Client</th>
            <th width="6%">ID</th>
            <th width="35%">Description</th>
            <th width="6%">Icons</th>
            <th width="15%">
                <select name="BahanSearch" id="BahanSearch" onchange="BahanSearch();">
                    <option value="">Bahan</option>
                </select>
            </th>
            <th width="3%">Sisi</th>
            <th width="9%">Qty</th>
            <th>Status</th>
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
                    penjualan.sisi,
                    (CASE
                        WHEN penjualan.sisi = '1' THEN 'satu'
                        WHEN penjualan.sisi = '2' THEN 'dua'
                        ELSE ''
                    END) as css_sisi
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
                    ( penjualan.kode = 'digital' or penjualan.kode='offset' or penjualan.kode='etc' ) and
                    penjualan.inv_check = 'Y' and
                    penjualan.cancel != 'Y' and
                    penjualan.status != 'selesai'
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
                    $array_kode = array("urgent", "laminating");
                    foreach ($array_kode as $kode) :
                        if ($d[$kode] != "" && $d[$kode] != "N") : ${'check_' . $kode} = "active";
                        else : ${'check_' . $kode} = "deactive";
                        endif;
                    endforeach;

                    if($d['id_client']=="1") :
                        $detail_yes = "<strong>";
                        if($d['id_yes']!="0") :
                            $detail_yes .= "$d[id_yes]";
                        else :
                            $detail_yes .= "";
                        endif;
                        if($d['so_yes']!="0") :
                            $detail_yes .= " / $d[so_yes] - ";
                        else :
                            $detail_yes .= "";
                        endif;
                        $detail_yes .= "<span style='color:#f1592a'>$d[client_yes]</span> </strong>";
                    else :
                        $detail_yes = "";
                    endif;
                    
                    echo "
                        <tr>
                            <td>$n</td>
                            <td class='a-center'>" . date("d M Y", strtotime($d['tanggal'])) . "</td>
                            <td><span class='KodeProject " . $kode_class . "'>" . strtoupper($d['code']) . "</span></td>
                            <td>$d[client]</td>
                            <td class='a-center'>$d[oid]</td>
                            <td>$detail_yes $d[description]</td>
                            <td>
                                <center>
                                    <span class='icon_status'><i class='fas fa-exclamation-triangle " . $check_urgent . "'></i></span>
                                    <span class='icon_status'><i class='fas fa-toilet-paper-alt " . $check_laminating . "'></i></span>
                                </center>
                            </td>
                            <td>$d[bahan]</td>
                            <td class='a-center'><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></td>
                            <td class='a-right'>$d[qty]</td>
                        </tr>
                    ";

                endwhile;
            endif;
        ?>
    </tbody>
</table>