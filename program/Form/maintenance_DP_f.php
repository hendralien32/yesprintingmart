<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

$ID_Order = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

$sql =
    "SELECT
        digital_printing.did,
        digital_printing.sisi,
        digital_printing.color,
        digital_printing.qty_cetak,
        digital_printing.hitungan_click,
        digital_printing.kode_bahan,
        digital_printing.id_bahan,
        LEFT(digital_printing.tgl_cetak,10) as tanggal,
        RIGHT(digital_printing.tgl_cetak,8) as Jam,
        (CASE
            WHEN barang.nama_barang != '' THEN barang.nama_barang
            WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
            ELSE '- - -'
        END) as nama_barang
    FROM
        digital_printing
    LEFT JOIN
        (SELECT
            barang.id_barang,
            barang.nama_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = 'KRTS'
        ) barang
    ON 
        barang.id_barang = digital_printing.id_bahan
    LEFT JOIN
        (SELECT
            barang.kode_barang,
            barang.nama_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = 'KRTS'
        ) barang_Kode
    ON 
        barang_Kode.kode_barang = digital_printing.kode_bahan
    WHERE
        digital_printing.did='$ID_Order'
    LIMIT
        1
";

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    // output data of each row
    $d = $result->fetch_assoc();
    $tanggal_Update = $d['tanggal'];
    $Jam_Update = $d['Jam'];
    $sisi = $d['sisi'];
    $color = $d['color'];
    $qty_cetak = $d['qty_cetak']/2;
    $hitungan_click = $d['hitungan_click'];
    $id_bahan = $d['id_bahan'];
    $nama_barang = $d['nama_barang'];
else :
    $tanggal_Update = $date;
    $Jam_Update = "";
    $sisi = "1";
    $color = "FC";
    $qty_cetak = "";
    $hitungan_click = "2";
    $id_bahan = "";
    $nama_barang = "";
endif;

?>
<input type='hidden' id="did" value="<?= $ID_Order ?>">

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
                    <input type="text" class="form md" style="width:185px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value='<?= $nama_barang ?>'>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='<?= $id_bahan ?>' class="form sd" readonly disabled>
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
                            if ($kode == $sisi) : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
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
                            if($ID_Order!="0") :
                                echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$tanggal_Update' max='$date' style='width:96%'>";
                            else :
                                echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='width:96%'>";
                            endif;
                        else :
                            echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='display:none'> ". date("d M Y", strtotime($date)) ."";
                        endif;
                    ?>
                    <input type='hidden' id="jam" class='form md' value="<?= $Jam_Update ?>">
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
                            if ($kode == $color) : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
                        }
                        ?> 
                    </select>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Qty</td>
                <td>
                    <input id="Qty" type='number' class='form sd' style='width:150px' value="<?= $qty_cetak ?>">
                    <div class="contact100-form-checkbox" style='float:right; margin-top:4px; margin-left:10px'>
                        <input class="input-checkbox100" id="jumlah_click" type="checkbox" name="remember" <?php if($hitungan_click == "1") { echo "checked"; } else { echo ""; }; ?>>
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