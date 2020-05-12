<?php
    session_start();
    require_once "../../function.php";

    if(isset($_POST['ID_Order'])) {
        $status_submit = "update_username";
        $nama_submit = "Update User";
        $sql = 
        "SELECT
            pm_user.uid,
            pm_user.nama,
            pm_user.username,
            pm_user.phone,
            pm_user.tanggal_masuk,
            pm_user.tanggal_resign,
            pm_user.level
        FROM
            pm_user
        WHERE
            pm_user.uid = '$_POST[ID_Order]'
        ";
        $result = $conn_OOP -> query($sql);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        endif;
    } else {
        $status_submit = "submit_username";
        $nama_submit = "Submit User";
    }

    if(isset($row)) {
        $disabled = "disabled";
        $uid = $row['uid'];
        $username = $row['username'];
        $nama = $row['nama'];
        $phone = $row['phone'];
        $tanggal_masuk = $row['tanggal_masuk'];
        $tanggal_resign = $row['tanggal_resign'];
        $level = $row['level'];
    } else {
        $disabled = "";
        $uid = "";
        $username = "";
        $nama = "";
        $phone = "";
        $tanggal_masuk = "";
        $tanggal_resign = "";
        $level = "";
    }

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
    <input type='hidden' value='<?= $uid ?>' id='id_user'>
    <div class="row">
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Username</td>
                    <td>
                        <input type="text" id="username" class='form md' value="<?= $username ?>" autocomplete="off" onkeyup="validasi('username')" style='width:150px;' <?= $disabled ?>>
                        <input type='hidden' id='validasi_username' value="0" class='form sd' disabled>
                        <span id="Alert_Valusername"></span>
                    </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td><input type="text" id="form_Nama" autocomplete="off" class='form md' value="<?= $nama ?>"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" id="form_Password" style="-webkit-text-security: square;" autocomplete="off" class='form md' value=""></td>
                </tr>
                <tr>
                    <td>Retype Password</td>
                    <td><input type="password" id="retype_password" style="-webkit-text-security: square;" autocomplete="off" class='form md' value=""></td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>No Telp</td>
                    <td><input type="text" id="form_NoTelp" autocomplete="off" class='form md' value="<?= $phone ?>"></td>
                </tr>
                <tr>
                    <td>Tanggal Masuk</td>
                    <td><input type="date" id="tanggal_masuk" data-placeholder='Tgl Masuk' autocomplete="off" class='form md' value="<?= $tanggal_masuk ?>"></td>
                </tr>
                <tr>
                    <td>Tanggal keluar</td>
                    <td><input type="date" id="tanggal_keluar" data-placeholder='Tgl Keluar' autocomplete="off" class='form md' value="<?= $tanggal_resign ?>"></td>
                </tr>
                <tr>
                    <td>Level User</td>
                    <td>
                        <select name="" id="form_levelUser">
                        <option value="">Pilih Level User</option>
                        <?php
                            $array_kode = array(
                                "accounting"            => "Accounting",
                                "admin"                 => "Administrator",
                                "setter"                => "Setter",
                                "operator_lf"           => "Operator Large Format",
                                "operator_dp"           => "Operator Digital Printing",
                                "CS"                    => "Customer Service",
                                "creative_support"      => "Creative Support YES",
                                "admin_yes"             => "Adminstator YES",
                                "marketing"             => "Marketing YES"
                            );

                            foreach($array_kode as $key => $value ) :
                                if($level=="$key") : $selected = "selected";
                                elseif($level=="") : $selected = "selected";
                                else : $selected = "";
                                endif;

                                echo "<option value='$key' $selected>$value</option>";
                            endforeach;
                        ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div id="submit_menu">
            <hr>
            <button onclick="submit('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
        </div>
        <div id="Result">
            
        </div>
    </div>

<?php $conn -> close(); ?>