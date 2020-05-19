<?php
    session_start();
    require_once "../../function.php";

    if(isset($_POST['ID_Order'])) {
        $status_submit = "update_pricelist";
        $nama_submit = "Update Pricelist";
        $sql = 
        "SELECT
            pricelist.price_id,
            pricelist.bahan,
            barang.nama_barang,
            pricelist.jenis,
            pricelist.sisi,
            pricelist.warna,
            pricelist.1_lembar,
            pricelist.2_lembar,
            pricelist.3sd5_lembar,
            pricelist.6sd9_lembar,
            pricelist.10_lembar,
            pricelist.20_lembar,
            pricelist.50_lembar,
            pricelist.100_lembar,
            pricelist.250_lembar,
            pricelist.500_lembar,
            pricelist.1_kotak,
            pricelist.2sd19_kotak,
            pricelist.20_kotak,
            pricelist.1sd2m,
            pricelist.3sd9m,
            pricelist.10m,
            pricelist.50m,
            pricelist.harga_indoor,
            pricelist.6sd8pass_indoor,
            pricelist.12pass_indoor,
            pricelist.20pass_indoor,
            pricelist.special_price,
            pricelist.status_pricelist
        FROM
            pricelist
        LEFT JOIN
            (
                SELECT
                    barang.nama_barang,
                    barang.id_barang
                FROM
                    barang
            ) barang
        ON
            barang.id_barang = pricelist.bahan
        WHERE
            pricelist.price_id = '$_POST[ID_Order]'
        ";
        $result = $conn_OOP -> query($sql);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        endif;
    } else {
        $status_submit = "submit_pricelist";
        $nama_submit = "Submit Pricelist";
    }

    if(isset($row)) {
        $f_price_id = $row['price_id'];
        $f_bahan = $row['bahan'];
        $f_nama_barang = $row['nama_barang'];
        $f_jenis = $row['jenis'];
        $f_sisi = $row['sisi'];
        if($f_sisi == "1") {
            $satu = "checked";
            $dua = "";
        } elseif($f_sisi == "2") {
            $satu = "";
            $dua = "checked";
        } else {
            $satu = "checked";
            $dua = "";
        }
        $f_warna = $row['warna'];
        $f_1_lembar = $row['1_lembar'];
        $f_2_lembar = $row['2_lembar'];
        $f_3sd5_lembar = $row['3sd5_lembar'];
        $f_6sd9_lembar = $row['6sd9_lembar'];
        $f_10_lembar = $row['10_lembar'];
        $f_20_lembar = $row['20_lembar'];
        $f_50_lembar = $row['50_lembar'];
        $f_100_lembar = $row['100_lembar'];
        $f_250_lembar = $row['250_lembar'];
        $f_500_lembar = $row['500_lembar'];
        $f_1_kotak = $row['1_kotak'];
        $f_2sd19_kotak = $row['2sd19_kotak'];
        $f_20_kotak = $row['20_kotak'];
        $f_1sd2m = $row['1sd2m'];
        $f_3sd9m = $row['3sd9m'];
        $f_10m = $row['10m'];
        $f_50m = $row['50m'];
        $f_harga_indoor = $row['harga_indoor'];
        $f_6sd8pass_indoor = $row['6sd8pass_indoor'];
        $f_12pass_indoor = $row['12pass_indoor'];
        $f_20pass_indoor = $row['20pass_indoor'];
        $f_special_price = $row['special_price'];
        $alert = "<b style='color:green'> Pricelist Nama Bahan Sama</b>";
    } else {
        $f_price_id = "";
        $f_bahan = "";
        $f_nama_barang = "";
        $f_jenis = "digital";
        $f_sisi = "1";
        if($f_sisi == "1") {
            $satu = "checked";
            $dua = "";
        } elseif($f_sisi == "2") {
            $satu = "";
            $dua = "checked";
        } else {
            $satu = "checked";
            $dua = "";
        }
        $f_warna = "FC";
        $f_1_lembar = "";
        $f_2_lembar = "";
        $f_3sd5_lembar = "";
        $f_6sd9_lembar = "";
        $f_10_lembar = "";
        $f_20_lembar = "";
        $f_50_lembar = "";
        $f_100_lembar = "";
        $f_250_lembar = "";
        $f_500_lembar = "";
        $f_1_kotak = "";
        $f_2sd19_kotak = "";
        $f_20_kotak = "";
        $f_1sd2m = "";
        $f_3sd9m = "";
        $f_10m = "";
        $f_50m = "";
        $f_harga_indoor = "";
        $f_6sd8pass_indoor = "";
        $f_12pass_indoor = "";
        $f_20pass_indoor = "";
        $f_special_price = "";
        $alert = "";
    }

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
    <input type='hidden' value='<?= $f_price_id ?>' id='id_pricelist'>
    <div class="row">
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td style="width:120px">Kode Barang</td>
                    <td>
                    <select class="myselect" id="kode_barng" onChange="ChangeKodeBrg(); test_valid('bahan'); validasi('bahanFC')">
                        <option value="">Pilih Kode Barang</option>
                        <?php
                            $array_kode = array(
                                "digital" => "Digital Printing",
                                "large format" => "Large Format",
                                "indoor" => "Indoor HP Latex"
                            );
                            foreach($array_kode as $kode => $kd) :
                                if($f_jenis=="$kode") : $selected = "selected";
                                elseif($f_jenis=="") : $selected = "selected";
                                else : $selected = "";
                                endif;

                                echo "<option value='$kode.$kd' $selected>$kd</option>";
                            endforeach;
                        ?>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td style="width:120px">Bahan</td>
                    <td>
                        <input type='text' class='form md' id="bahanFC" value="<?= $f_nama_barang ?>" autocomplete="off" onkeyup="test('bahanFC')" onchange="test_valid('bahan')" onkeyup="validasi('bahanFC')" onchange="validasi('bahanFC')">
                        <input type='hidden' id='id_bahanFC' value="<?= $f_bahan ?>" class='form sd' readonly disabled>
                        <input type='hidden' id='validasi_bahanFC' value="0"  class='form sd' readonly disabled>
                        <input type='hidden' id='validasi_bahan' value="1" class='form sd' readonly disabled>
                        <span id="Alert_ValbahanFC"><?= $alert ?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:120px">Special Price</td>
                    <td><input type="number" id="SpecialPrice" value="<?= $f_special_price ?>"  class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">1 Lembar</td>
                    <td><input type="number" id="1_lembar" value="<?= $f_1_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">2 Lembar</td>
                    <td><input type="number" id="2_lembar" value="<?= $f_2_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">3-5 Lembar</td>
                    <td><input type="number" id="3sd5_lembar" value="<?= $f_3sd5_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">6-9 Lembar</td>
                    <td><input type="number" id="6sd9_lembar" value="<?= $f_6sd9_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">10 Lembar</td>
                    <td><input type="number" id="10_lembar" value="<?= $f_10_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td style="width:120px">20 Lembar</td>
                    <td><input type="number" id="20_lembar" value="<?= $f_20_lembar ?>" class='form md'></td>
                </td>

                <tr id='large_format'>
                    <td style="width:120px">1-2 Meter</td>
                    <td><input type="number" id="1sd2m" value="<?= $f_1sd2m ?>" class='form md'></td>
                </td>
                <tr id='large_format'>
                    <td style="width:120px">3-9 Meter</td>
                    <td><input type="number" id="3sd9m" value="<?= $f_3sd9m ?>" class='form md'></td>
                </td>

                <tr id='indoor'>
                    <td style="width:120px">Harga Indoor</td>
                    <td><input type="number" id="harga_indoor" value="<?= $f_harga_indoor ?>" class='form md'></td>
                </td>
                <tr id='indoor'>
                    <td style="width:120px">6-8 Pass</td>
                    <td><input type="number" id="6sd8pass_indoor" value="<?= $f_6sd8pass_indoor ?>" class='form md'></td>
                </td>
            </table>
        </div>
        <div class="col-6">
            <table class='table-pelunasan'>
                <tr>
                    <td>Warna</td>
                    <td>
                        <select id="from_Warna" onChange="test_valid('bahan'); validasi('bahanFC')">
                        <?php
                            $array_kode = array(
                                "FC"        => "Fullcolor",
                                "BW"        => "Grayscale"
                            );

                            foreach($array_kode as $key => $value ) :
                                if($f_warna=="$key") : $selected = "selected";
                                elseif($f_warna=="") : $selected = "selected";
                                else : $selected = "";
                                endif;

                                echo "<option value='$key' $selected>$value</option>";
                            endforeach;
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Sisi</td>
                    <td>
                        <label class="sisi_radio" onClick="test_valid('bahan'); validasi('bahanFC')">1 Sisi
                            <input type="radio" name="radio" id="satu_sisi" value="1" <?= $satu; ?>>
                            <span class="checkmark"></span>
                        </label>
                        <label class="sisi_radio" onClick="test_valid('bahan'); validasi('bahanFC')">2 Sisi
                            <input type="radio" name="radio" id="dua_sisi" value="2" <?= $dua; ?>>
                            <span class="checkmark"></span>
                        </label>
                    </td>
                </tr>
                <tr id='digital'>
                    <td style="width:120px">50 Lembar</td>
                    <td><input type="number" id="50_lembar" value="<?= $f_50_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>100 Lembar</td>
                    <td><input type="number" id="100_lembar" value="<?= $f_100_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>250 Lembar</td>
                    <td><input type="number" id="250_lembar" value="<?= $f_250_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>500 Lembar</td>
                    <td><input type="number" id="500_lembar" value="<?= $f_500_lembar ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>1 Kotak</td>
                    <td><input type="number" id="1_kotak" value="<?= $f_1_kotak ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>2-19 Kotak</td>
                    <td><input type="number" id="2sd19_kotak" value="<?= $f_2sd19_kotak ?>" class='form md'></td>
                </td>
                <tr id='digital'>
                    <td>20 Kotak</td>
                    <td><input type="number" id="20_kotak" value="<?= $f_20_kotak ?>" class='form md'></td>
                </td>

                <tr id='large_format'>
                    <td>10 Meter</td>
                    <td><input type="number" id="10m" value="<?= $f_10m ?>" class='form md'></td>
                </td>
                <tr id='large_format'>
                    <td>50 Meter</td>
                    <td><input type="number" id="50m" value="<?= $f_50m ?>" class='form md'></td>
                </td>

                <tr id='indoor'>
                    <td>12 Pass</td>
                    <td><input type="number" id="12pass_indoor" value="<?= $f_12pass_indoor ?>" class='form md'></td>
                </td>
                <tr id='indoor'>
                    <td>20 Pass</td>
                    <td><input type="number" id="20pass_indoor" value="<?= $f_20pass_indoor ?>" class='form md'></td>
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