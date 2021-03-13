<?php
session_start();
require_once "../../function.php";

?>

<div id="form">
    <div id="formLeft">
        <table>
            <tr>
                <td>Kode Barang</td>
                <td>
                    <select class="myselect md pointer" id="kodeBrg">
                        <?php
                        $array_kode = array(
                            "digital" => "Digital Printing",
                            "large format" => "Large Format",
                            "indoor" => "Indoor HP Latex",
                            "Xuli" => "Indoor Xuli",
                            "offset" => "Offset",
                            "etc" => "ETC"
                        );
                        foreach ($array_kode as $kode => $kd) :
                            echo "<option value='$kode.$kd'>$kd</option>";
                        endforeach;
                        ?>
                    </select>
                    -
                    <select class="myselect sd pointer" id="warnaCetakan">
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
                <td>Client</td><td><input class='md' type='text'></td>
            </tr>
            <tr>
                <td>Deskripsi</td><td><input class='ld' type='text'></td>
            </tr>
            <tr>
                <td>Ukuran</td><td><input class='sd' type='text'> <input class='ssd' type='number'> x <input class='ssd' type='number'></td>
            </tr>
            <tr>
                <td>Sisi</td>
                <td>
                    <label class="sisi_radio">1 Sisi
                        <input type="radio" name="radio" id="satu_sisi" value="1">
                        <span class="checkmark"></span>
                    </label>
                    <label class="sisi_radio">2 Sisi
                        <input type="radio" name="radio" id="dua_sisi" value="2">
                        <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Bahan</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Notes / Finishing LF</td>
                <td><textarea class='md ld'>test</textarea></td>
            </tr>
        </table>
    </div>
    <div id="formRight">
        <table>
            <tr>
                <td>Qty</td><td><input class='ssd' type='number'><input class='sd' type='text'></td>
            </tr>
            <tr>
                <td>Laminating</td>
                <td>
                    <select class="myselect" id="laminating" onchange="autoCalc()">
                        <option value=".">Pilih Laminating</option>
                        <?php
                        $array_kode = array(
                            "kilat1" => "Laminating Kilat 1 Sisi",
                            "kilat2" => "Laminating Kilat 2 Sisi",
                            "doff1" => "Laminating Doff 1 Sisi",
                            "doff2" => "Laminating Doff 2 Sisi",
                            "kilatdingin1" => "Laminating Kilat Dingin",
                            "doffdingin1" => "Laminating Doff Dingin",
                            "hard_lemit" => "Hard Laminating / Lamit KTP",
                            "laminating_floor" => "Laminating Floor"
                        );
                        foreach ($array_kode as $kode => $kd) :
                            echo "<option value='$kode.$kd' class='$kode'>$kd</option>";
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alat Tambahan</td>
                <td>
                    <select class="myselect" id="alat_tambahan" onchange="autoCalc()">
                        <option value=".">Pilih Alat Tambahan</option>
                        <?php
                        $array_kode = array(
                            "Ybanner" => "Ybanner",
                            "RU_60" => "Roller Up 60 x 160 Cm",
                            "RU_80" => "Roller Up 80 x 200 Cm",
                            "RU_85" => "Roller Up 85 x 200 Cm",
                            "Tripod" => "Tripod",
                            "Softboard" => "Softboard",
                            "KotakNC" => "Kotak Kartu Nama"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            echo "<option value='$kode.$kd' class='$kode'>$kd</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Finishing</td>
                <td>
                <label class="container">One
                    <input type="checkbox" checked="checked">
                    <span class="checkmark"></span>
                </label>
                </td>
            </tr>
            <tr>
                <td>Permintaan Order</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>File Design</td><td><input type='file'></td>
            </tr>
            <tr>
                <td>File Image Preview</td><td><input type='file'></td>
            </tr>
            <tr>
                <td>Admin Menu</td><td><input type='text'></td>
            </tr>
        </table>
    </div>
    <div id="formPriceRight">
        <table>
            <tr>
                <td>Biaya Digital</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Kotak</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Finishing</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Laminating</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Lain-Lain</td><td><input type='text'></td>
            </tr>
        </table>
    </div>
    <div id="formPriceLeft">
        <table>
            <tr>
                <td>Biaya Alat Tambahan</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Indoor</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Large Format</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Biaya Design</td><td><input type='text'></td>
            </tr>
            <tr>
                <td>Diskon</td><td><input type='text'></td>
            </tr>
        </table>
    </div>
</div>