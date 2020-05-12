<?php
    session_start();

    require_once "../../function.php";

    if($_POST['data']!="") {
        $add_where = "( pm_user.nama LIKE '%$_POST[data]%' or pm_user.username LIKE '%$_POST[data]%' )";
    } else {
        $add_where = "pm_user.status = '$_POST[show_delete]'";
    }

    $cari_keyword = $_POST['data'];
    $bold_cari_keyword = "<strong style='text-decoration:underline'>".$_POST['data']."</strong>";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
    <table>
        <tbody>
            <tr>
                <th width="3%">#</th>
                <th width="15%">Username</th>
                <th width="15%">Nama</th>
                <th width="12%">Level</th>
                <th width="6%">Tanggal Masuk</th>
                <th width="1%"></th>
            </tr>
            <?php
                $sql =
                "SELECT
                    pm_user.uid,
                    pm_user.nama,
                    pm_user.username,
                    pm_user.level,
                    pm_user.tanggal_masuk,
                    pm_user.status
                FROM
                    pm_user
                WHERE
                    $add_where
                ORDER BY
                    pm_user.username
                DESC
                ";
                $no = 0;
                $result = $conn_OOP -> query($sql);
                if ($result->num_rows > 0) :
                    while ($row = $result->fetch_assoc()) :
                        $no++;

                        if($row['status']=="a") :
                            $icon = "<i class='far fa-trash-alt text-danger'></i>";
                        else :
                            $icon = "<i class='fas fa-undo-alt text-success'></i>";
                        endif;

                        $edit = "LaodForm(\"database_user\", \"". $row['uid'] ."\")";

                        if($row['tanggal_masuk'] != "0000-00-00") {
                            $tanggal_masuk = date("d M Y",strtotime($row['tanggal_masuk']));
                        } else {
                            $tanggal_masuk = "0000-00-00";
                        }

                        echo "
                        <tr class='pointer'>
                            <td>$no</td>
                            <td onclick='". $edit ."'>". str_ireplace($cari_keyword,$bold_cari_keyword,$row['username']) ."</td>
                            <td onclick='". $edit ."'>$row[nama]</td>
                            <td onclick='". $edit ."'>$row[level]</td>
                            <td onclick='". $edit ."'>$tanggal_masuk</td>
                            <td class='pointer' ondblclick='hapus(\"". $row['uid'] ."\", \"". $row['username'] ."\", \"". $row['status'] ."\")'>$icon</td>
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

    <?php $conn -> close(); ?>