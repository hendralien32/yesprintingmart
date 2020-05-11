<?php
    session_start();
    require_once "../../function.php";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
    <table>
        <tbody>
            <tr>
                <th width="3%">#</th>
                <th width="34%">Username</th>
                <th width="30%">Nama</th>
                <th width="20%">Password</th>
                <th width="12%">Level</th>
                <th width="1%"></th>
            </tr>
            <?php
                $sql =
                "SELECT
                    pm_user.uid,
                    pm_user.nama,
                    pm_user.username,
                    pm_user.password,
                    pm_user.password_visible,
                    pm_user.phone,
                    pm_user.tanggal_masuk,
                    pm_user.tanggal_resign,
                    pm_user.level
                FROM
                    pm_user
                WHERE
                    pm_user.status = 'a'
                ORDER BY
                    pm_user.username
                DESC
                ";
                $no = 0;
                $result = $conn_OOP -> query($sql);
                if ($result->num_rows > 0) :
                    while ($row = $result->fetch_assoc()) :
                        $no++;
                        echo "
                        <tr class='pointer'>
                            <td>$no</td>
                            <td>X</td>
                            <td>X</td>
                            <td>X</td>
                            <td>X</td>
                            <td>X</td>
                            <td class='pointer' ondblclick='hapus()'>X</td>
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