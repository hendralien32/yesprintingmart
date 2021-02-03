<?php

    session_start();
    require_once "../../function.php";

    if($_POST['type_bahan']!="") {
        $add_where = "barang.jenis_barang ='$_POST[type_bahan]' and barang.status_bahan = '$_POST[show_delete]'";
    } elseif($_POST['data']!="") {
        $add_where = "( barang.nama_barang LIKE '%$_POST[data]%' ) and barang.status_bahan = '$_POST[show_delete]'";
    } else {
        $add_where = "barang.status_bahan = '$_POST[show_delete]'";
    }

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
    <table>
        <tbody>
            <tr>
                <th width="1%">#</th>
                <th width="8%">Kode Barang</th>
                <th width="25%">Nama Barang</th>
                <th width="13%">Ukuran</th>
                <th width="13%">Jenis Barang</th>
                <th width="12%">Minimal Stock</th>
                <th width="1%"></th>
            </tr>
            <?php
                $sql =
                "SELECT
                    barang.id_barang,
                    barang.nama_barang,
                    barang.jenis_barang,
                    barang.kode_barang,
                    barang.min_stock,
                    barang.satuan,
                    (CASE
                        WHEN barang.panjang_kertas > 0 || barang.lebar_kertas > 0 THEN CONCAT(barang.panjang_kertas, ' X ', barang.lebar_kertas, ' Cm')
                        ELSE '- - -'
                    END) as ukuran,
                    barang.status_bahan
                FROM
                    barang
                WHERE
                    $add_where
                ORDER BY
                    barang.kode_barang
                ASC
                ";
                $no = 0;
                $result = $conn_OOP -> query($sql);
                if ($result->num_rows > 0) :
                    while ($row = $result->fetch_assoc()) :
                        $no++;

                        if($row['status_bahan']=="a") :
                            $icon = "<i class='far fa-trash-alt text-danger'></i>";
                        else :
                            $icon = "<i class='fas fa-undo-alt text-success'></i>";
                        endif;

                        if($_SESSION['level']=="admin") :
                            $edit = "LaodForm(\"database_bahan\", \"". $row['id_barang'] ."\")";
                            $hapus = "<td class='pointer' ondblclick='hapus(\"". $row['id_barang'] ."\", \"". $row['nama_barang'] ."\", \"". $row['status_bahan'] ."\")'>$icon</td>";
                        else :
                            $edit ="";
                            $hapus ="";
                        endif;

                        echo "
                        <tr class='pointer'>
                            <td>$no</td>
                            <td onclick='". $edit ."'>$row[kode_barang]</td>
                            <td onclick='". $edit ."'>$row[nama_barang]</td>
                            <td onclick='". $edit ."'>$row[ukuran]</td>
                            <td onclick='". $edit ."'>$row[jenis_barang]</td>
                            <td onclick='". $edit ."'>$row[min_stock] ". ucfirst($row['satuan']) ."</td>
                            $hapus
                        </tr>
                        ";
                    endwhile;
                else :
                    echo "
                        <tr>
                            <td colspan='7'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
                endif;
            ?>
        </tbody>
    </table>