<?php
session_start();
require_once "../../function.php";

$ID_Order = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

if($ID_Order != "") : $add_where = "WHERE billing_konika.billing_id='$ID_Order'";
else : $add_where = "";
endif;

$sql =
    "SELECT
		billing_konika.billing_id,
		billing_konika.tanggal_billing,
		billing_konika.FC_awal,
		billing_konika.BW_awal,
		billing_konika.FC_akhir,
		billing_konika.BW_akhir
	FROM
		billing_konika
	$add_where
	LIMIT
		1
";

$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    // output data of each row
    $d = $result->fetch_assoc();
    $validasi = "1";
    $display = "";
    $onlick_submit = "update_counter";
    $nama_submit = "Update";
    $tanggal_billing = "$d[tanggal_billing]";
    $FC_awal = "$d[FC_awal]";
    $BW_awal = "$d[BW_awal]";
    $FC_akhir = "$d[FC_akhir]";
    $BW_akhir = "$d[BW_akhir]";
else :
    $validasi = "0";
    $display = "display:none";
    $onlick_submit = "submit_counter";
    $nama_submit = "Submit";
    $tanggal_billing = "$date";
    $FC_awal = "";
    $BW_awal = "";
    $FC_akhir = "";
    $BW_akhir = "";
endif;

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<input type="hidden" id="validasi" value="<?= $validasi ?>">
<input type="hidden" id="billing_id" value="<?= $ID_Order ?>">

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Tanggal</td>
                <td><input type='date' id='tanggal_Counter' data-placeholder='Tanggal' class='form md' value='<?= $tanggal_billing ?>' max='<?= $date ?>'></td>
            </tr>
            <tr>
                <td style='width:145px'>Counter Awal FC</td>
                <td><input id="Counter_Awal_FC" type='number' placeholder='Counter Awal FC' class='form sd' style='width:150px' value="<?= $FC_awal ?>"></td>
            </tr>
            <tr style='<?= $display ?>'>
                <td style='width:145px'>Counter Akhir FC</td>
                <td><input id="Counter_Akhir_FC" type='number' placeholder='Counter Akhir FC' class='form sd' style='width:150px' value="<?= $FC_akhir ?>"></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Mesin</td>
                <td>
                    <select id="type_mesin">
                        <?php
                        $array_kode = array(
                            "Konika_C-6085" => "Konika C-6085",
                            "Konika_C7000" => "Konika C-7000"
                        );
                        foreach ($array_kode as $kode => $kd) :
                            if ($kode == "$_SESSION[session_MesinDP]") : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Counter Awal BW</td>
                <td><input id="Counter_Awal_BW" type='number' placeholder='Counter Awal BW' class='form sd' style='width:150px' value="<?= $BW_awal ?>"></td>
            </tr>
            <tr style='<?= $display ?>'>
                <td style='width:145px'>Counter Akhir BW</td>
                <td><input id="Counter_Akhir_BW" type='number' placeholder='Counter Akhir BW' class='form sd' style='width:150px' value="<?= $BW_akhir ?>"></td>
            </tr>
        </table>
    </div>
</div>
<div id="submit_menu">
    <button onclick="submit('<?= $onlick_submit ?>')" id="submitBtn"><?= $nama_submit ?> Maintenance</button>
</div>
<div id="Result">

</div>