<?php
session_start();
require_once "../../function.php";

if ($_POST['data'] != "") {
    $add_where = "and ( supplier.nama_supplier LIKE '%$_POST[data]%' )";
} else {
    $add_where = "";
}
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="1%">#</th>
            <th width="8%" id="cid">ID Supplier</th>
            <th width="25%" id="client">Supplier</th>
            <th width="40%">No Telp</th>
            <th width="1%"></th>
        </tr>
        <?php
        $sql =
            "SELECT
                supplier.id_supplier,
                supplier.nama_supplier,
                supplier.keterangan,
                supplier.hapus
            FROM
                supplier
            WHERE
                supplier.hapus = '$_POST[show_delete]'
                $add_where
            ORDER BY
                supplier.nama_supplier 
            ASC
        ";
        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($row = $result->fetch_assoc()) :
                $no++;
                $SID = sprintf("%02d", $row['id_supplier']);

                if ($row['hapus'] == "Y") :
                    $icon = "<i class='fas fa-undo-alt text-success'></i>";
                else :
                    $icon = "<i class='far fa-trash-alt text-danger'></i>";
                endif;

                if ($_SESSION['level'] == "admin") :
                    $edit = "LaodForm(\"database_supplier\", \"" . $row['id_supplier'] . "\")";
                else :
                    $edit = "";
                endif;

                echo "
                    <tr class='pointer'>
                        <td>$no</td>
                        <td onclick='" . $edit . "'>S-$SID</td>
                        <td onclick='" . $edit . "'>$row[nama_supplier]</td>
                        <td onclick='" . $edit . "'>$row[keterangan]</td>
                        <td class='pointer' ondblclick='hapus(\"" . $row['id_supplier'] . "\", \"" . $row['nama_supplier'] . "\", \"" . $row['hapus'] . "\")'>$icon</td>
                    </tr>
                ";
            endwhile;
        else :
            echo "
                <tr>
                    <td colspan='5'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='fafa-empty-set'></i></b></center></td>
                </tr>
            ";
        endif;
        ?>
    </tbody>
</table>