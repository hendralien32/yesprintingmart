<?php 
    require_once '../../function_new.php';
    $n = 0;
    $iconHapus = "";

$sql = 
    "SELECT
        pm_user.uid,
        pm_user.username,
        pm_user.nama,
        (CASE 
            WHEN pm_user.tanggal_masuk = '0000-00-00' THEN '---'
            ELSE pm_user.tanggal_masuk
        END ) as tanggal_masuk,
        (CASE 
            WHEN pm_user.jam_masuk = '00:00:00' THEN '---'
            ELSE pm_user.jam_masuk
        END ) as jam_masuk,
        (CASE 
            WHEN pm_user.reset_password = 'N' THEN '---'
            ELSE pm_user.val_reset
        END ) as reset_password
    FROM
        pm_user
    WHERE
        pm_user.status = 'a'
";

$result = $conn_OOP->query($sql);
$jumlah_order = $result->num_rows;

?>
<div class='list-karyawan'>
    <table>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Tanggal masuk</th>
            <th>Jam masuk</th>
            <th>Reset Password</th>
            <th></th>
        </tr>
        <?php 
            if($jumlah_order > 0) :
                while($d = $result->fetch_assoc()) {
                    $n++;
                    if($delete_Database_User == "Y") :
                        $iconHapus = "<span style='padding-right:8px;'><i class='fas fa-trash-alt btn' onclick='showForm(\"database_user\",\"Form_hapusUser\",\"$d[uid]\",\"lightbox-confirmation\")'></i></span>";
                    endif;

                    if($d['reset_password'] == "---") :
                        $icon_resetPwd = "<span style='padding-right:8px;'><i class='fas fa-redo-alt btn' onclick='showForm(\"database_user\",\"Form_resetPassword\",\"$d[uid]\",\"lightbox-confirmation\")'></i></span>";
                    else :
                        $icon_resetPwd = "";
                    endif;

                    $edit = "showForm(\"database_user\",\"Form_EditUsername\",\"$d[uid]\",\"lightbox-confirmation\")";

                    echo "
                        <tr>
                            <td>$n</td>
                            <td onclick='$edit' class='btn'>$d[username]</td>
                            <td onclick='$edit' class='btn'>$d[nama]</td>
                            <td onclick='$edit' class='btn'>$d[tanggal_masuk]</td>
                            <td onclick='$edit' class='btn'>$d[jam_masuk]</td>
                            <td onclick='$edit' class='btn'>$d[reset_password]</td>
                            <td>
                                <span style='padding-right:8px;'><i class='fas fa-address-card btn' onclick='showForm(\"database_user\",\"Form_EditProfile\",\"$d[uid]\",\"lightbox-Small\")'></i></span>
                                $icon_resetPwd
                                $iconHapus
                            </td>
                        </tr>
                    ";
                }
            endif; 
        ?>
    </table>
</div>
