<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

?>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Operator</td>
                <td><?= $_SESSION["username"] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Kertas</td>
                <td>
                    <input type="text" class="form md" style="width:185px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value=''>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_BahanDigital" class="form sd" readonly disabled>
                    <span id="Alert_ValBahanDigital"></span>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Sisi</td>
                <td> 
                    <select class="myselect" id="sisi">
                        <?php
                        $array_kode = array(
                            "1" => "1 Sisi",
                            "2" => "2 Sisi"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            echo "<option value='$kode'>$kd</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Tanggal</td>
                <td colspan="3">
                    <?php
                        if($_SESSION['level'] == "admin") :
                            echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='width:96%'>";
                        else :
                            echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='display:none'> ". date("d M Y", strtotime($date)) ."";
                        endif;
                    ?>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Warna</td>
                <td>
                    <select class="myselect" id="warna_cetakan">
                        <?php
                        $array_kode = array(
                            "FC" => "Fullcolor",
                            "BW" => "Grayscale"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            echo "<option value='$kode'>$kd</option>";
                        }
                        ?> 
                    </select>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Qty</td>
                <td>
                    <input id="Qty" type='number' class='form sd' style='width:150px' value="">
                    <div class="contact100-form-checkbox" style='float:right; margin-top:4px; margin-left:10px'>
                            <input class="input-checkbox100" id="jumlah_click" type="checkbox" name="remember">
                            <label class="label-checkbox100" for="jumlah_click"> 1 Click</label>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="submit_menu">
    <button onclick="submit_maintenance('submit_maintenance')" id="submitBtn">Submit Maintenance</button>
</div>
<div id="Result">

</div>