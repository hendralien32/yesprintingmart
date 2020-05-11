<?php
    session_start();
    require_once "../../function.php";

    if(isset($_POST['ID_Order'])) {
        $status_submit = "update_client";
        $nama_submit = "Update Client";
        $sql = 
        "SELECT
            customer.cid,
            customer.nama_client,
            customer.no_telp,
            customer.email,
            customer.alamat_kantor,
            customer.level_client,
            customer.special
        FROM
            customer
        WHERE
            customer.cid = '$_POST[ID_Order]' and
            customer.status_client = 'A'
        ";
        $result = $conn_OOP -> query($sql);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        endif;
    } else {
        $status_submit = "submit_client";
        $nama_submit = "Submit Client";
    }

    if(isset($row)) {
        $cid = $row['cid'];
        $nama_client = $row['nama_client'];
        $no_telp = $row['no_telp'];
        $email = $row['email'];
        $alamat_kantor = $row['alamat_kantor'];
        $level_client = $row['level_client'];
        $Status_special = $row['special'];
        $alert = "<b style='color:green'> Nama Client Sama</b>";
    } else {
        $cid = "";
        $nama_client = "";
        $no_telp = "";
        $email = "";
        $alamat_kantor = "";
        $level_client = "D2";
        $Status_special = "";
        $alert = "";
    }

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
    <input type='hidden' value='<?= $cid; ?>' id='id_client'>
    <div class="row">
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Nama Client</td>
                    <td>
                        <input type="text" id="client" class='form md' value="<?= $nama_client ?>" autocomplete="off" onkeyup="validasi('client')" style='width:150px;'>
                        <input type='hidden' id='validasi_client' value="0" class='form sd' disabled>
                        <span id="Alert_Valclient"><?= $alert ?></span>
                    </td>
                </tr>
                <tr>
                    <td>No Telepon</td>
                    <td><input type="text" id="form_NoTelp" autocomplete="off" class='form md' value="<?= $no_telp ?>"></td>
                </tr>
                <tr>
                    <td>Email Customer</td>
                    <td><input type="text" id="form_EmailClient" autocomplete="off" class='form md' value="<?= $email ?>"></td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Deskripsi</td>
                    <td><input type="text" id="form_DeskClient" autocomplete="off" class='form md' value="<?= $alamat_kantor ?>"></td>
                </tr>
                <tr>
                    <td>Level Client</td>
                    <td>
                        <select name="" id="form_levelClient">
                        <?php
                            $array_kode = array(
                                "D1"  => "D1 - Good Client",
                                "D2"  => "D2 - Average Client",
                                "D3"  => "D3 - Bad Client",
                                "D4"  => "D4 - Blacklisted Client"
                            );

                            foreach($array_kode as $key => $value ) :
                                if($level_client=="$key") : $selected = "selected";
                                elseif($level_client=="") : $selected = "selected";
                                else : $selected = "";
                                endif;

                                echo "<option value='$key' $selected>$value</option>";
                            endforeach;
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Special Client</td>
                    <td>
                        <span style='margin-left:10px'>
                            <?php
                                if($Status_special=="Y") :
                                    $special = "checked";
                                else :
                                    $special = "";
                                endif;
                            ?>
                            <input class="input-checkbox100" id="form_special" type="checkbox" name="remember" <?= $special ?>>
                            <label class="label-checkbox100" for="form_special">Special Client</label>
                        </span>
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