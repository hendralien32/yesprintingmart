<?php
session_start();
require_once "../../function.php";

if (!isset($_POST['ID_Order'])) :
?>
    <div id='generator_container'>
        <h3 class='title_form'><?= $_POST['judul_form'] ?></h3>
        <textarea id="generator_select" placeholder="Copy Paste Generate Code Here"></textarea>
        <button type="button" id="button_copy" onclick="submit_GeneratorCode()">Submit Generator Code</button>
    </div>
    <div id="result"></div>

    <?php
else :

    $AksesEdit = isset($_POST['AksesEdit']) ? $_POST['AksesEdit'] : "";
    if ($AksesEdit == "Y" or $AksesEdit == "") {

        if (isset($_POST['ID_Order'])) {
            $ID_Order = $_POST['ID_Order'];
            $sql =
                "SELECT
                    penjualan.oid,
                    penjualan.kode,
                    penjualan.jenis_wo,
                    penjualan.send_by,
                    penjualan.marketing,
                    (CASE
                        WHEN penjualan.no_invoice != '' THEN 'Y'
                        ELSE 'N'
                    END) as no_invoice,
                    penjualan.no_invoice as Invoice_Number,
                    penjualan.id_yes,
                    penjualan.so_yes,
                    penjualan.client_yes,
                    penjualan.description,
                    penjualan.ID_Bahan,
                    penjualan.ukuran,
                    penjualan.ukuran_jadi,
                    penjualan.panjang,
                    penjualan.lebar,
                    penjualan.sisi,
                    penjualan.potong,
                    penjualan.potong_gantung,
                    penjualan.pon,
                    penjualan.perporasi,
                    penjualan.CuttingSticker,
                    penjualan.Hekter_Tengah,
                    penjualan.Blok,
                    penjualan.Spiral,
                    penjualan.laminate,
                    penjualan.keterangan,
                    penjualan.qty,
                    penjualan.qty_jadi,
                    penjualan.satuan,
                    penjualan.urgent,
                    penjualan.CS_Generate,
                    penjualan.warna_cetak,
                    penjualan.alat_tambahan,
                    penjualan.date_create,
                    penjualan.DateSO_Yes,
                    penjualan.dead_line,
                    penjualan.additional_charge_YES,
                    penjualan.harga_YES,
                    penjualan.ppn_YES,
                    penjualan.designer_YES,
                    penjualan.cs_YES,
                    penjualan.Shipto_YES,
                    penjualan.Proffing,
                    penjualan.ditunggu,
                    (CASE
                        WHEN penjualan.ID_Bahan = '0' THEN penjualan.bahan
                        ELSE barang.nama_barang
                    END) as nama_barang,
                    penjualan.b_digital,
                    penjualan.b_lain,
                    penjualan.b_potong,
                    penjualan.b_large,
                    penjualan.b_indoor,
                    penjualan.b_xbanner,
                    penjualan.b_kotak,
                    penjualan.b_laminate,
                    penjualan.bahan_sendiri,
                    penjualan.akses_edit
                FROM
                    penjualan
                LEFT JOIN 
                    (select barang.id_barang, barang.nama_barang from barang) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang  
                WHERE
                    penjualan.oid = '$_POST[ID_Order]'
            ";
            $result = $conn_OOP->query($sql);
            if ($result->num_rows > 0) :
                $row = $result->fetch_assoc();
            endif;
        } else {
            $ID_Order = "";
        }

        if (isset($row)) {
            $oid = "$row[oid]";
            $kode_barang = "$row[kode]";
            $jenis_wo = "$row[jenis_wo]";
            $send_by = "$row[send_by]";
            $marketing = "$row[marketing]";
            $id_yes = "$row[id_yes]";
            $so_yes = "$row[so_yes]";
            $client_yes = "$row[client_yes]";
            $description = "$row[description]";
            $ID_Bahan = "$row[ID_Bahan]";
            $ukuran = "$row[ukuran]";
            $ukuran_jadi = "$row[ukuran_jadi]";
            $panjang = "$row[panjang]";
            $lebar = "$row[lebar]";
            $sisi = "$row[sisi]";
            if ($row['potong'] == "Y") {
                $potong = "checked";
            } else {
                $potong = "";
            }
            if ($row['potong_gantung'] == "Y") {
                $potong_gantung = "checked";
            } else {
                $potong_gantung = "";
            }
            if ($row['pon'] == "Y") {
                $pon = "checked";
            } else {
                $pon = "";
            }
            if ($row['perporasi'] == "Y") {
                $perporasi = "checked";
            } else {
                $perporasi = "";
            }
            if ($row['CuttingSticker'] == "Y") {
                $CuttingSticker = "checked";
            } else {
                $CuttingSticker = "";
            }
            if ($row['Hekter_Tengah'] == "Y") {
                $Hekter_Tengah = "checked";
            } else {
                $Hekter_Tengah = "";
            }
            if ($row['Blok'] == "Y") {
                $Blok = "checked";
            } else {
                $Blok = "";
            }
            if ($row['Spiral'] == "Y") {
                $Spiral = "checked";
            } else {
                $Spiral = "";
            }
            if ($row['Proffing'] == "Y") {
                $proffing = "checked";
            } else {
                $proffing = "";
            }
            if ($row['ditunggu'] == "Y") {
                $ditunggu = "checked";
            } else {
                $ditunggu = "";
            }
            $laminate = "$row[laminate]";
            $keterangan = "$row[keterangan]";
            $qty = "$row[qty]";
            $qty_jadi = "$row[qty_jadi]";
            $satuan = "$row[satuan]";
            if ($row['urgent'] == "Y") {
                $urgent = "checked";
            } else {
                $urgent = "";
            }
            $CS_Generate = "$row[CS_Generate]";
            $warna_cetak = "$row[warna_cetak]";
            $alat_tambahan = "$row[alat_tambahan]";
            $date_create = "$row[date_create]";
            $DateSO_Yes = "$row[DateSO_Yes]";
            $dead_line = "$row[dead_line]";
            $additional_charge_YES = "$row[additional_charge_YES]";
            $harga_YES = "$row[harga_YES]";
            $ppn_YES = "$row[ppn_YES]";
            $designer_YES = "$row[designer_YES]";
            $cs_YES = "$row[cs_YES]";
            $Shipto_YES = "$row[Shipto_YES]";
            $nama_barang = "$row[nama_barang]";
            $validasi_bahan = "1";
            if ($row['b_digital'] != "") {
                $b_digital = "$row[b_digital]";
            } else {
                $b_digital = "0";
            }
            if ($row['b_lain'] != "") {
                $b_lain = "$row[b_lain]";
            } else {
                $b_lain = "0";
            }
            if ($row['b_potong'] != "") {
                $b_potong = "$row[b_potong]";
            } else {
                $b_potong = "0";
            }
            if ($row['b_large'] != "") {
                $b_large = "$row[b_large]";
            } else {
                $b_large = "0";
            }
            if ($row['b_indoor'] != "") {
                $b_indoor = "$row[b_indoor]";
            } else {
                $b_indoor = "0";
            }
            if ($row['b_xbanner'] != "") {
                $b_xbanner = "$row[b_xbanner]";
            } else {
                $b_xbanner = "0";
            }
            if ($row['b_kotak'] != "") {
                $b_kotak = "$row[b_kotak]";
            } else {
                $b_kotak = "0";
            }
            if ($row['b_laminate'] != "") {
                $b_laminate = "$row[b_laminate]";
            } else {
                $b_laminate = "0";
            }
            $Invoice_Number = "$row[Invoice_Number]";
            $bahan_sendiri = "$row[bahan_sendiri]";
            if ($row['akses_edit'] == "Y") {
                $akses_edit = "checked";
            } else {
                $akses_edit = "";
            }
        } else {
            $oid = "";
            $kode = "";
            $jenis_wo = "";
            $send_by = "";
            $marketing = "";
            $id_yes = "";
            $so_yes = "";
            $client_yes = "";
            $description = "";
            $ID_Bahan = "";
            $ukuran = "";
            $ukuran_jadi = "";
            $panjang = "";
            $lebar = "";
            $sisi = "";
            $potong = "";
            $potong_gantung = "";
            $pon = "";
            $perporasi = "";
            $CuttingSticker = "";
            $Hekter_Tengah = "";
            $Blok = "";
            $Spiral = "";
            $laminate = "";
            $keterangan = "";
            $qty = "";
            $qty_jadi = "";
            $satuan = "";
            $urgent = "";
            $CS_Generate = "";
            $warna_cetak = "";
            $alat_tambahan = "";
            $date_create = "";
            $DateSO_Yes = "";
            $dead_line = "";
            $additional_charge_YES = "";
            $harga_YES = "";
            $ppn_YES = "";
            $designer_YES = "";
            $cs_YES = "";
            $Shipto_YES = "";
            $Proffing = "";
            $ditunggu = "";
            $nama_barang = "";
            $validasi_bahan = "";
            $b_digital = "0";
            $b_lain = "0";
            $b_potong = "0";
            $b_large = "0";
            $b_indoor = "0";
            $b_xbanner = "0";
            $b_kotak = "0";
            $b_laminate = "0";
            $Invoice_Number = "";
            $bahan_sendiri = "";
            $akses_edit = "";
        }

    ?>

        <h3 class='title_form'><?= $_POST['judul_form'] . " OID " . $ID_Order ?></h3>

        <div class="row">
            <div class="col-6">
                <input type="hidden" id="id_Order" value="<?= $oid ?>">
                <input type="hidden" id="no_invoice" value="<?= $Invoice_Number ?>">
                <input type="hidden" id="level_user" value="<?= $_SESSION['level']; ?>">
                <table class='table-form'>
                    <tr>
                        <td style='width:150px;'>Kode Barang</td>
                        <td>
                            <select class="myselect" id="kode_barng" onchange="ChangeKodeBrg()">
                                <?php
                                $array_kode = array(
                                    "digital" => "Digital Printing",
                                    "large format" => "Large Format",
                                    "indoor" => "Indoor HP Latex",
                                    "Xuli" => "Indoor Xuli",
                                    "etc" => "ETC"
                                );
                                foreach ($array_kode as $kode => $kd) :
                                    if ($kode == $kode_barang) :
                                        $selected = "selected";
                                    else :
                                        $selected = "";
                                    endif;
                                    echo "<option value='$kode.$kd' $selected>$kd</option>";
                                endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>ID</td>
                        <td>
                            <input type="text" class='form sd' i id="id_yescom" value="<?= $id_yes ?>" class="form md">
                            <?php
                            echo "<span style='padding:5px 15px; background-color:#f76c35; cursor:pointer; color:white;margin-left:10px' onClick='LaodSubForm(\"Detail_YesID\",\"$_POST[ID_Order]\")'>View Detail</span>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>Client</td>
                        <td>
                            <input type='text' class='form md' id="client" value='<?= $client_yes ?>' autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>Deskripsi</td>
                        <td><input type='text' id="deskripsi" class='form ld' value='<?= $description ?>' autocomplete="off"></td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>Ukuran</td>
                        <td>
                            <input type='text' class='form' id='ukuran' value='<?= $ukuran ?>'>
                            <span id="ukuran_LF"><input type='number' class='form sd' id='panjang' onkeyup="calc_meter(); autoCalc();" value='<?= $panjang ?>'> x <input type='number' class='form sd' id='lebar' onkeyup="calc_meter(); autoCalc();" value='<?= $lebar ?>'></span><span id="perhitungan_meter"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>sisi</td>
                        <td>
                            <?php
                            if ($sisi == "1") {
                                $satu = "checked";
                                $dua = "";
                            } else if ($sisi == "2") {
                                $satu = "";
                                $dua = "checked";
                            } else {
                                $satu = "checked";
                                $dua = "";
                            }
                            ?>
                            <label class="sisi_radio">1 Sisi
                                <input type="radio" name="radio" id="satu_sisi" onchange="autoCalc()" value="1" <?= $satu ?>>
                                <span class="checkmark"></span>
                            </label>
                            <label class="sisi_radio">2 Sisi
                                <input type="radio" name="radio" id="dua_sisi" onchange="autoCalc()" value="2" <?= $dua ?>>
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;' rowspan="2">Bahan</td>
                        <td>
                            <input type='text' class='form md' id="bahan" autocomplete="off" onchange="autoCalc()" onkeyup="test('bahan')" value='<?= $nama_barang ?>' onChange="validasi('bahan'); Check_KertasSendiri();">
                            <input type='text' id='id_bahan' class='form sd' value='<?= $ID_Bahan ?>' readonly disabled style="display:none">
                            <input type='text' id='validasi_bahan' class='form sd' value='<?= $validasi_bahan ?>' readonly disabled style="display:none">
                            <span id="Alert_Valbahan"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px;'><input type='text' class='form md' style="width:150px; display:none" value="<?= $bahan_sendiri ?>" id="bahan_sendiri" autocomplete="off" placeholder="Kertas / bahan Sendiri"> <span id="YES_bahan"></span></td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>Notes / Finishing LF</td>
                        <td><textarea id='notes' class='form ld' style="height:50px;"><?= $keterangan ?></textarea></td>
                    </tr>
                    <tr>
                        <td style='width:150px;'>Qty</td>
                        <td colspan="3">
                            <input type='number' class='form sd' id="qty" onkeyup="autoCalc()" value='<?= $qty ?>'>
                            <input type='text' class='form' list="list_satuan" id="satuan" autocomplete="off" onchange="autoCalc()" onkeyup="satuan_val()" value='<?= $satuan ?>'>
                            <datalist id="list_satuan">
                                <?php
                                $array_kode = array("Kotak", "Lembar", "Rim", "Blok", "Pcs");
                                foreach ($array_kode as $kode) :
                                    echo "<option value='$kode'>";
                                endforeach;
                                ?>
                            </datalist>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
                    <tr>
                        <td>WO - Warna</td>
                        <td colspan="3">
                            <select class="myselect" id="wo_yescom">
                                <?php
                                $array_kode = array(
                                    "Kuning"    => "Kuning",
                                    "Hijau"     => "Hijau"
                                );
                                foreach ($array_kode as $kode => $kd) {
                                    if ($kode == $jenis_wo) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='$kode' $selected>$kd</option>";
                                }
                                ?>
                            </select>
                            -
                            <select class="myselect" id="warna_cetakan">
                                <?php
                                $array_kode = array(
                                    "FC" => "Fullcolor",
                                    "BW" => "Grayscale"
                                );
                                foreach ($array_kode as $kode => $kd) {
                                    if ($kode == $warna_cetak) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='$kode' $selected>$kd</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>SO - Marketing</td>
                        <td colspan="3">
                            <input type='text' class='form md' style='width:150px' id="so_yescom" value='<?= $so_yes ?>'> -
                            <input type='text' class='form md' style='width:150px' readonly disabled id="marketing_yescom" value='<?= $marketing ?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>Ukuran WO</td>
                        <td colspan="3"><input type='text' class='form ld' readonly disabled value="<?= $ukuran_jadi ?>" id="ukuran_yescom"></td>
                    </tr>
                    <tr>
                        <td>Qty WO</td>
                        <td colspan="3"><input type='text' class='form md' readonly disabled value="<?= $qty_jadi ?>" id="qty_yescom"></td>
                    </tr>
                    <tr>
                        <td>Laminating</td>
                        <td colspan="3">
                            <select class="myselect" id="laminating" onchange="autoCalc()">
                                <option value="">Pilih Laminating</option>
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
                                    if ($kode == $laminate) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='$kode.$kd' class='$kode' $selected>$kd</option>";
                                endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Alat Tambahan</td>
                        <td colspan="3">
                            <select class="myselect" id="alat_tambahan" onchange="autoCalc()">
                                <option value="">Pilih Alat Tambahan</option>
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
                                foreach ($array_kode as $kode => $kd) :
                                    if ($kode == $alat_tambahan) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='$kode.$kd' class='$kode' $selected>$kd</option>";
                                endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Finishing</td>
                        <td style='vertical-align:top'>
                            <div class="contact100-form-checkbox Ptg_Pts">
                                <input class="input-checkbox100" id="Ptg_Pts" type="checkbox" name="remember" onchange="autoCalc()" <?= $potong ?>>
                                <label class="label-checkbox100" for="Ptg_Pts"> Ptg Putus</label>
                            </div>
                            <div class="contact100-form-checkbox Ptg_Gantung">
                                <input class="input-checkbox100" id="Ptg_Gantung" type="checkbox" name="remember" onchange="autoCalc()" <?= $potong_gantung ?>>
                                <label class="label-checkbox100" for="Ptg_Gantung"> Ptg Gantung</label>
                            </div>
                            <div class="contact100-form-checkbox CuttingSticker">
                                <input class="input-checkbox100" id="CuttingSticker" type="checkbox" name="remember" onchange="autoCalc()" <?= $CuttingSticker ?>>
                                <label class="label-checkbox100" for="CuttingSticker"> Cutting Sticker</label>
                            </div>
                            <div class="contact100-form-checkbox Hekter_Tengah">
                                <input class="input-checkbox100" id="Hekter_Tengah" type="checkbox" name="remember" <?= $Hekter_Tengah ?>>
                                <label class="label-checkbox100" for="Hekter_Tengah"> Hekter Tengah</label>
                            </div>
                        </td>
                        <td style='vertical-align:top' colspan="2">
                            <div class="contact100-form-checkbox Pon_Garis">
                                <input class="input-checkbox100" id="Pon_Garis" type="checkbox" name="remember" onchange="autoCalc()" <?= $pon ?>>
                                <label class="label-checkbox100" for="Pon_Garis"> Pon Garis</label>
                            </div>
                            <div class="contact100-form-checkbox Perporasi">
                                <input class="input-checkbox100" id="Perporasi" type="checkbox" name="remember" onchange="autoCalc()" <?= $perporasi ?>>
                                <label class="label-checkbox100" for="Perporasi"> Perporasi</label>
                            </div>
                            <div class="contact100-form-checkbox Blok">
                                <input class="input-checkbox100" id="Blok" type="checkbox" name="remember" <?= $Blok ?>>
                                <label class="label-checkbox100" for="Blok"> Blok</label>
                            </div>
                            <div class="contact100-form-checkbox Ring_Spiral">
                                <input class="input-checkbox100" id="Spiral" type="checkbox" name="remember" <?= $Spiral ?>>
                                <label class="label-checkbox100" for="Spiral"> Ring Spiral</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Status Order</td>
                        <td>
                            <div class="contact100-form-checkbox urgent">
                                <input class="input-checkbox100" id="urgent" type="checkbox" name="remember" <?= $urgent ?>>
                                <label class="label-checkbox100" for="urgent"> Urgent</label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="proffing" type="checkbox" name="remember" <?= $proffing ?>>
                                <label class="label-checkbox100" for="proffing"> Proffing</label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ditunggu" type="checkbox" name="remember" <?= $ditunggu ?>>
                                <label class="label-checkbox100" for="ditunggu"> Ditunggu</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Admin Menu</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="Auto_Calc" type="checkbox" name="remember" onclick="AksesEdit()" <?php if ($_SESSION["level"] != "admin") {
                                                                                                                                            echo "Disabled";
                                                                                                                                        } ?>>
                                <label class="label-checkbox100" for="Auto_Calc"> Auto Calc </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="akses_edit" type="checkbox" name="remember" onclick="AksesEdit()" <?php if ($_SESSION["level"] != "admin") {
                                                                                                                                            echo "Disabled ";
                                                                                                                                        }
                                                                                                                                        echo "$akses_edit"; ?>>
                                <label class="label-checkbox100" for="akses_edit"> Akses Edit</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if ($row['no_invoice'] == "Y" && ($_SESSION['level'] == "admin" || $_SESSION['level'] == "CS" || $_SESSION['level'] == "accounting")) : ?>
            <div class="row">
                <div class="col-6">
                    <table class='table-form'>
                        <tr class='b_digital'>
                            <td style='width:150px;'>Biaya Digital</td>
                            <td><input id="b_digital" type='number' class='form ld' value="<?= $b_digital; ?>"></td>
                        </tr>
                        <tr class='b_large'>
                            <td style='width:150px;'>Biaya Large Format</td>
                            <td><input id="b_large" type='number' class='form ld' value="<?= $b_large; ?>"></td>
                        </tr>
                        <tr class='b_indoor'>
                            <td style='width:150px;'>Biaya Indoor</td>
                            <td><input id="b_indoor" type='number' class='form ld' value="<?= $b_indoor; ?>"></td>
                        </tr>
                        <tr class='b_lain'>
                            <td style='width:150px;'>Biaya Lain</td>
                            <td><input id="b_lain" type='number' class='form ld' value="<?= $b_lain; ?>"></td>
                        </tr>
                        <tr class='b_finishing'>
                            <td style='width:150px;'>Biaya Finishing</td>
                            <td><input id="b_finishing" type='number' class='form ld' value="<?= $b_potong; ?>"></td>
                        </tr>
                        <tr class='b_xbanner'>
                            <td style='width:150px;'>Biaya Xbanner</td>
                            <td><input id="b_xbanner" type='number' class='form ld' value="<?= $b_xbanner; ?>"></td>
                        </tr>

                    </table>
                </div>
                <div class="col-6">
                    <table class='table-form'>
                        <tr class='b_kotak'>
                            <td>Biaya Kotak</td>
                            <td><input id="b_kotak" type='number' class='form ld' value="<?= $b_kotak; ?>"></td>
                        </tr>
                        <tr class='b_laminate'>
                            <td>Biaya Laminating</td>
                            <td><input id="b_laminate" type='number' class='form ld' value="<?= $b_laminate; ?>"></td>
                        </tr>
                    </table>
                </div>
            </div>

        <?php else : ?>

            <input type="hidden" id="b_digital" value="<?= $b_digital ?>">
            <input type="hidden" id="b_lain" value="<?= $b_lain ?>">
            <input type="hidden" id="b_finishing" value="<?= $b_potong ?>">
            <input type="hidden" id="b_large" value="<?= $b_large ?>">
            <input type="hidden" id="b_indoor" value="<?= $b_indoor ?>">
            <input type="hidden" id="b_xbanner" value="<?= $b_xbanner ?>">
            <input type="hidden" id="b_kotak" value="<?= $b_kotak ?>">
            <input type="hidden" id="b_laminate" value="<?= $b_laminate ?>">

        <?php endif; ?>


        <div id="submit_menu">
            <?php if ($Invoice_Number != "0") : ?>
                <button onclick="submit_order('Update_PenjualanYESCOM')" id="submitBtn">Update Order</button>
                <button onclick="LaodForm('penjualan_invoice_yescom', '<?= $Invoice_Number; ?>', '<?= $jenis_wo; ?>')">Re-Add Invoice <?= $Invoice_Number; ?></button>
            <?php else : ?>
                <button onclick="submit('Update')">Update Order</button>
            <?php endif; ?>
        </div>

    <?php  } else {

        $ID_Order = "$_POST[ID_Order]";
        $sql =
            "SELECT 
                penjualan.oid ,
                penjualan.client_yes,
                penjualan.id_yes,
                penjualan.marketing,
                penjualan.so_yes,
                penjualan.jenis_wo,
                penjualan.warna_cetak,
                penjualan.ukuran_jadi,
                penjualan.qty_jadi,
                penjualan.description,
                (CASE
                    WHEN penjualan.kode = 'large format' THEN 'Large Format'
                    WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                    WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                    WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                    WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                    WHEN penjualan.kode = 'etc' THEN 'ETC'
                    ELSE '- - -'
                END) as kode,
                (CASE
                    WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    ELSE penjualan.ukuran
                END) as ukuran,
                CONCAT(penjualan.sisi, ' Sisi') as sisi,
                (CASE
                    WHEN penjualan.ID_Bahan > 0 THEN barang.nama_barang
                    ELSE penjualan.bahan
                END) as bahan,
                penjualan.keterangan,
                (CASE
                    WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                    WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                    WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                    WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                    WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                    WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                    WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                    WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                    ELSE '- - -'
                END) as laminating,
                (CASE
                    WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                    WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                    WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                    WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                    WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                    WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                    WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                    ELSE '- - -'
                END) as alat_tambahan,
                (CASE
                    
                    WHEN penjualan.bahan_sendiri != '' THEN CONCAT(' - ' ,penjualan.bahan_sendiri)
                    ELSE ''
                END) as bahan_sendiri,
                CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                penjualan.potong,
                penjualan.potong_gantung,
                penjualan.pon,
                penjualan.perporasi,
                penjualan.CuttingSticker,
                penjualan.Hekter_Tengah,
                penjualan.Blok,
                penjualan.Spiral,
                penjualan.urgent,
                penjualan.Ditunggu,
                penjualan.Proffing,
                penjualan.b_digital,
                penjualan.b_xbanner,
                penjualan.b_lain,
                penjualan.b_large,
                penjualan.b_kotak,
                penjualan.b_laminate,
                penjualan.b_potong,
                penjualan.b_indoor,
                ((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount) as harga_satuan,
                (((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as total
            FROM 
                penjualan
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) barang
            ON
                penjualan.ID_Bahan = barang.id_barang  
            WHERE
                penjualan.oid = '$ID_Order'
        ";

        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        else : endif;
    ?>
        <div class="row">
            <div class="col-6">
                <table class='table-form'>
                    <tr>
                        <td style='width:150px'>Kode Barang</td>
                        <td><?= $row['kode']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>ID</td>
                        <td>
                            <?php
                            echo "$row[id_yes]";
                            $edit = "LaodSubForm(\"Detail_YesID\",\"" . $row['oid'] . "\")";
                            ?>
                            <span style='padding:5px 15px; background-color:#f76c35; cursor:pointer; color:white;margin-left:10px' onclick='<?= $edit ?>'>View Detail</span>
                        </td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Client</td>
                        <td><?= ucwords($row['client_yes']); ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Deskripsi</td>
                        <td><?= ucfirst($row['description']); ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Ukuran</td>
                        <td><?= $row['ukuran']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>sisi</td>
                        <td><?= $row['sisi']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Bahan</td>
                        <td><?= $row['bahan'] . " " . $row['bahan_sendiri']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Notes / Finishing LF</td>
                        <td><?= ucfirst($row['keterangan']); ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Qty</td>
                        <td><?= $row['qty']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Laminating</td>
                        <td><?= $row['laminating']; ?></td>
                    </tr>
                    <tr>
                        <td style='width:150px'>Alat Tambahan</td>
                        <td><?= $row['alat_tambahan']; ?></td>
                    </tr>
                    <?php
                    if ($row['kode'] == "Digital Printing" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                    ?>
                        <tr>
                            <td style='width:150px'>Biaya Digital</td>
                            <td><?= "Rp. " . number_format($row['b_digital']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Kotak</td>
                            <td><?= "Rp. " . number_format($row['b_kotak']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Finishing</td>
                            <td><?= "Rp. " . number_format($row['b_potong']) . ""; ?></td>
                        </tr>
                    <?php
                    } elseif ($row['kode'] == "Large Format" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                    ?>
                        <tr>
                            <td style='width:150px'>Biaya Large Format</td>
                            <td><?= "Rp. " . number_format($row['b_large']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Finishing</td>
                            <td><?= "Rp. " . number_format($row['b_potong']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Xbanner</td>
                            <td><?= "Rp. " . number_format($row['b_xbanner']) . ""; ?></td>
                        </tr>
                    <?php
                    } elseif ($row['kode'] == "Indoor HP Latex" or $row['kode'] == "Indoor Xuli" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                    ?>
                        <tr>
                            <td style='width:150px'>Biaya Indoor</td>
                            <td><?= "Rp. " . number_format($row['b_indoor']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Finishing</td>
                            <td><?= "Rp. " . number_format($row['b_potong']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Xbanner</td>
                            <td><?= "Rp. " . number_format($row['b_xbanner']) . ""; ?></td>
                        </tr>
                    <?php
                    } elseif ($row['kode'] == "ETC" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                    ?>
                        <tr>
                            <td style='width:150px'>Biaya Lain</td>
                            <td><?= "Rp. " . number_format($row['b_lain']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td style='width:150px'>Biaya Finishing</td>
                            <td><?= "Rp. " . number_format($row['b_potong']) . ""; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
                    <tr>
                        <td>WO</td>
                        <td colspan="3"><?= $row['jenis_wo']; ?></td>
                    </tr>
                    <tr>
                        <td>SO</td>
                        <td colspan="3"><?= $row['so_yes']; ?></td>
                    </tr>
                    <tr>
                        <td>Marketing</td>
                        <td colspan="3"><?= $row['marketing']; ?></td>
                    </tr>
                    <tr>
                        <td>Ukuran WO</td>
                        <td colspan="3"><?= $row['ukuran_jadi']; ?></td>
                    </tr>
                    <tr>
                        <td>Qty WO</td>
                        <td colspan="3"><?= $row['qty_jadi']; ?></td>
                    </tr>
                    <tr>
                        <td>Warna</td>
                        <td colspan="3"><?= $row['warna_cetak']; ?></td>
                    </tr>
                    <tr>
                        <td>Finishing</td>
                        <?php
                        $array_kode = array(
                            "potong",
                            "potong_gantung",
                            "pon",
                            "perporasi",
                            "CuttingSticker",
                            "Hekter_Tengah",
                            "Blok",
                            "Spiral",
                            "urgent",
                            "Ditunggu",
                            "Proffing"
                        );
                        foreach ($array_kode as $kode) :
                            if ($row[$kode] == "Y") : ${'check_' . $kode} = "<i class='fad fa-check-square'></i>";
                            else : ${'check_' . $kode} = "<i class='fad fa-times-square'></i>";
                            endif;
                        endforeach;
                        ?>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_potong; ?>
                                <label class='checkbox-fa' for='Ptg_Pts'> Ptg Putus </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_potong_gantung; ?>
                                <label class='checkbox-fa' for='Ptg_Gantung'> Ptg Gantung </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_pon; ?>
                                <label class='checkbox-fa' for='Pon_Garis'> Pon Garis </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_perporasi; ?>
                                <label class='checkbox-fa' for='Perporasi'> Perporasi </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="contact100-form-checkbox">
                                <?= $check_CuttingSticker; ?>
                                <label class='checkbox-fa' for='CuttingSticker'> Cutting Sticker </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Hekter_Tengah; ?>
                                <label class='checkbox-fa' for='Hekter_Tengah'> Hekter Tengah </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Blok; ?>
                                <label class='checkbox-fa' for='Blok'> Blok </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?= $check_Spiral; ?>
                                <label class='checkbox-fa' for='Spiral'> Ring Spiral </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Status Order</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_urgent; ?>
                                <label class='checkbox-fa' for='Ptg_Pts'> Urgent </label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_Ditunggu; ?>
                                <label class='checkbox-fa' for='CuttingSticker'> Ditunggu </label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?= $check_Proffing; ?>
                                <label class='checkbox-fa' for='CuttingSticker'> Proffing </label>
                            </div>
                        </td>
                    </tr>
                    <?php if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "CS" || $_SESSION['level'] == "accounting") : ?>
                        <?php
                        if ($row['kode'] == "Digital Printing" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                        ?>
                            <tr>
                                <td style='width:150px'>Biaya Laminating</td>
                                <td><?= "Rp. " . number_format($row['b_laminate']) . ""; ?></td>
                            </tr>
                        <?php
                        } elseif ($row['kode'] == "Large Format" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                        ?>
                            <tr>
                                <td style='width:150px'>Biaya Laminating</td>
                                <td><?= "Rp. " . number_format($row['b_laminate']) . ""; ?></td>
                            </tr>
                        <?php
                        } elseif ($row['kode'] == "Indoor HP Latex" or $row['kode'] == "Indoor Xuli" and ($_SESSION['level'] == "admin" or $_SESSION['level'] == "CS" or $_SESSION['level'] == "accounting")) {
                        ?>
                            <tr>
                                <td style='width:150px'>Biaya Laminating</td>
                                <td><?= "Rp. " . number_format($row['b_laminate']) . ""; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>Harga Satuan @</td>
                            <td><?= "Rp. " . number_format($row['harga_satuan']) . ""; ?></td>
                        </tr>
                        <tr>
                            <td>Total Biaya</td>
                            <td><?= "Rp. " . number_format($row['total']) . ""; ?></td>
                        </tr>
                    <?php else : endif; ?>
                </table>
            </div>


    <?php }

endif; ?>

    <div id='result'></div>