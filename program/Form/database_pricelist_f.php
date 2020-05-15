<?php
    session_start();
    require_once "../../function.php";

    if(isset($_POST['ID_Order'])) {
        $status_submit = "update_bahan";
        $nama_submit = "Update Bahan";
    } else {
        $status_submit = "submit_bahan";
        $nama_submit = "Submit Bahan";
    }

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
    <input type='hidden' value='' id='id_bahan'>
    <input type='hidden' value='' id='kode_barang'>
    <div class="row">
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td style="width:120px">Kode Barang</td>
                    <td>
                    <select class="myselect" id="kode_barng" onchange="ChangeKodeBrg()" onchange="test('bahanFC')" onchange="test_valid('bahan')" onchange="validasi('bahanFC')">
                        <option value="">Pilih Kode Barang</option>
                        <?php
                            $array_kode = array(
                                "digital" => "Digital Printing",
                                "large format" => "Large Format",
                                "indoor" => "Indoor HP Latex"
                            );
                            foreach($array_kode as $kode => $kd) :
                                echo "<option value='$kode.$kd'>$kd</option>";
                            endforeach;
                        ?>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td style="width:120px">Bahan</td>
                    <td>
                        <input type='text' class='form md' id="bahanFC" value="" autocomplete="off" onkeyup="test('bahanFC')" onchange="test_valid('bahan')" onchange="validasi('bahanFC')">
                        <input type='hidden' id='id_bahanFC' value="" class='form sd' readonly disabled>
                        <input type='hidden' id='validasi_bahanFC' class='form sd'>
                        <input type='hidden' id='validasi_bahan' class='form sd'>
                        <span id="Alert_ValbahanFC"></span>
                    </td>
                </tr>
                <tr id='digital'>
                    <td style="width:120px">1 Lembar</td>
                    <td><input type="number" id="1_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">2 Lembar</td>
                    <td><input type="number" id="2_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">3-5 Lembar</td>
                    <td><input type="number" id="3sd5_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">6-9 Lembar</td>
                    <td><input type="number" id="6sd9_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">10 Lembar</td>
                    <td><input type="number" id="10_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">20 Lembar</td>
                    <td><input type="number" id="20_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">50 Lembar</td>
                    <td><input type="number" id="50_lembar" class='form md'></td>
                </td>

                <tr id='large_format'>
                    <td style="width:120px">1-2 Meter</td>
                    <td><input type="number" id="1sd2m" class='form md'></td>
                </td>
                <tr id='large_format'>
                    <td style="width:120px">3-9 Meter</td>
                    <td><input type="number" id="3sd9m" class='form md'></td>
                </td>

                <tr id='indoor'>
                    <td style="width:120px">Harga Indoor</td>
                    <td><input type="number" id="harga_indoor" class='form md'></td>
                </td>
                <tr id='indoor'>
                    <td style="width:120px">6-8 Pass</td>
                    <td><input type="number" id="6sd8pass_indoor" class='form md'></td>
                </td>
            </table>
        </div>
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Warna</td>
                    <td>
                        <select name="" id="from_Warna" onchange="test('bahanFC')" onchange="test_valid('bahan')" onchange="validasi('bahanFC')">
                        <?php
                            $array_kode = array(
                                "FC"        => "Fullcolor",
                                "BW"        => "Grayscale"
                            );

                            foreach($array_kode as $key => $value ) :
                                echo "<option value='$key'>$value</option>";
                            endforeach;
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Sisi</td>
                    <td>
                        <label class="sisi_radio">1 Sisi
                            <input type="radio" name="radio" id="satu_sisi" value="1" checked onchange="test('bahanFC')" onchange="test_valid('bahan')" onchange="validasi('bahanFC')">
                            <span class="checkmark"></span>
                        </label>
                        <label class="sisi_radio">2 Sisi
                            <input type="radio" name="radio" id="dua_sisi" value="2" onchange="test('bahanFC')" onchange="test_valid('bahan')" onchange="validasi('bahanFC')">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                </tr>
                <tr id='digital'>
                    <td>100 Lembar</td>
                    <td><input type="number" id="100_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>250 Lembar</td>
                    <td><input type="number" id="250_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>500 Lembar</td>
                    <td><input type="number" id="500_lembar" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>1 Kotak</td>
                    <td><input type="number" id="1_kotak" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>2-19 Kotak</td>
                    <td><input type="number" id="2sd19_kotak" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>20 Kotak</td>
                    <td><input type="number" id="20_kotak" class='form md'></td>
                </td>

                <tr id='large_format'>
                    <td>10 Meter</td>
                    <td><input type="number" id="10m" class='form md'></td>
                </td>
                <tr id='large_format'>
                    <td>50 Meter</td>
                    <td><input type="number" id="50m" class='form md'></td>
                </td>

                <tr id='indoor'>
                    <td>12 Pass</td>
                    <td><input type="number" id="12pass_indoor" class='form md'></td>
                </td>
                <tr id='indoor'>
                    <td>20 Pass</td>
                    <td><input type="number" id="20pass_indoor" class='form md'></td>
                </td>
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