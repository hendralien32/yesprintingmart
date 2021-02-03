<?php
session_start();
require_once "../../function.php";

if (isset($_POST['ID_Order'])) {
    $status_submit = "update_bahan";
    $nama_submit = "Update Bahan";
    $sql =
        "SELECT
            barang.id_barang,
            barang.nama_barang,
            barang.jenis_barang,
            barang.kode_barang,
            barang.min_stock,
            barang.satuan,
            barang.panjang_kertas,
            barang.lebar_kertas 
        FROM
            barang
        WHERE
            barang.id_barang = '$_POST[ID_Order]'
        ";
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    endif;
} else {
    $status_submit = "submit_bahan";
    $nama_submit = "Submit Bahan";
}

if (isset($row)) {
    $id_barang = $row['id_barang'];
    $nama_barang = $row['nama_barang'];
    $jenis_barang = $row['jenis_barang'];
    $kode_barang = $row['kode_barang'];
    $min_stock = $row['min_stock'];
    $satuan = $row['satuan'];
    $panjang_kertas = $row['panjang_kertas'];
    $lebar_kertas = $row['lebar_kertas'];
} else {
    $id_barang = "";
    $nama_barang = "";
    $jenis_barang = "";
    $kode_barang = "";
    $min_stock = "";
    $satuan = "";
    $panjang_kertas = "";
    $lebar_kertas = "";
}

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
<input type='hidden' value='<?= $id_barang ?>' id='id_bahan'>
<input type='hidden' value='<?= $kode_barang ?>' id='kode_barang'>
<div class="row">
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td style='width:125px'>Nama Bahan</td>
                <td>
                    <input type="text" id="bahan" class='form md' value="<?= $nama_barang ?>" autocomplete="off" onkeyup="validasi('bahan')" style='width:225px;'>
                    <input type='hidden' id='validasi_bahan' value="0" class='form sd' disabled>
                    <span id="Alert_Valbahan"></span>
                </td>
            </tr>
            <tr>
                <td style='width:125px'>Jenis Bahan</td>
                <td>
                    <select name="" id="form_JenisBahan" onchange="ChangeKodeBrg()">
                        <option value="">Pilih Jenis Bahan</option>
                        <?php
                        $array_kode = array(
                            "KRTS"          => "Kertas Digital",
                            "LF"            => "Bahan Large Format",
                            "INDOOR"        => "Bahan Indoor",
                            "TNT"           => "Tinta",
                            "SPRT"          => "Sparepart",
                            "ETC"           => "Lain-Lain"
                        );

                        foreach ($array_kode as $key => $value) :
                            if ($jenis_barang == "$key") : $selected = "selected";
                            elseif ($jenis_barang == "") : $selected = "selected";
                            else : $selected = "";
                            endif;

                            echo "<option value='$key' $selected>$value</option>";
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr id='ukuran'>
                <td style='width:125px'>Ukuran Kertas</td>
                <td><input type="number" id="form_panjang" autocomplete="off" class='form sd' value="<?= $panjang_kertas ?>"> x <input type="number" id="form_lebar" autocomplete="off" class='form sd' value="<?= $lebar_kertas ?>"> Cm</td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td>Minimal Stock</td>
                <td><input type="number" id="form_MinStock" autocomplete="off" class='form md' value="<?= $min_stock ?>"></td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td><input type="text" id="form_Satuan" autocomplete="off" class='form md' value="<?= $satuan ?>"></td>
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

<?php $conn->close(); ?>