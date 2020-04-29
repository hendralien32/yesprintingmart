<?php
    session_start();
    require_once "../../function.php";

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

    <div class="row">
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Nama Client</td>
                    <td>
                        <input type="text" id="client" class='form md' value="" autocomplete="off" onkeyup="validasi('client')" style='width:150px;'>
                        <input type='hidden' id='validasi_client' class='form sd'>
                        <span id="Alert_Valclient"></span>
                    </td>
                </tr>
                <tr>
                    <td>No Telepon</td>
                    <td><input type="text" id="form_NoTelp" autocomplete="off" class='form md' value=""></td>
                </tr>
                <tr>
                    <td>Email Customer</td>
                    <td><input type="text" id="form_EmailClient" autocomplete="off" class='form md' value=""></td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Deskripsi</td>
                    <td><input type="text" id="form_DeskClient" autocomplete="off" class='form md' value=""></td>
                </tr>
                <tr>
                    <td>Level Client</td>
                    <td>
                        <select name="" id="form_levelClient">
                            <option value="">Pilih Level Client</option>
                            <option value="D1">D1 - Good Client</option>
                            <option value="D2" selected>D2 - Average Client</option>
                            <option value="D3">D3 - Bad Client</option>
                            <option value="D4">D4 - Blacklisted Client</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Special Client</td>
                    <td>
                        <span style='margin-left:10px'>
                            <input class="input-checkbox100" id="form_special" type="checkbox" name="remember">
                            <label class="label-checkbox100" for="form_special">Special Client</label>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div id="submit_menu">
            <hr>
            <button onclick="submit('submit_client')" id="submitBtn">Submit Client</button>
        </div>
        <div id="Result">
            
        </div>
    </div>

<?php $conn -> close(); ?>