<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

$AksesEdit = isset($_POST['AksesEdit']) ? $_POST['AksesEdit'] : "";

if ($AksesEdit == "Y" or $AksesEdit == "") {
    if (isset($_POST['ID_Order'])) {
        $ID_Order = $_POST['ID_Order'];
        $status_submit = "Update_WO_List";
        $nama_submit = "Update Order";
        $sql =
            "SELECT
                wo_list.kode,
                wo_list.wo_color,
                wo_list.marketing,
                wo_list.id,
                wo_list.so,
                wo_list.client,
                wo_list.project,
                wo_list.ID_Bahan,
                wo_list.ukuran,
                wo_list.ukuran_jadi,
                wo_list.panjang,
                wo_list.lebar,
                wo_list.cetak,
                wo_list.potong,
                wo_list.potong_gantung,
                wo_list.pon,
                wo_list.perporasi,
                wo_list.CuttingSticker,
                wo_list.Hekter_Tengah,
                wo_list.Blok,
                wo_list.Spiral,
                wo_list.leminate,
                wo_list.finishing,
                wo_list.qty,
                wo_list.qty_jadi,
                wo_list.satuan,
                wo_list.urgent,
                wo_list.Proffing,
                wo_list.Ditunggu,
                wo_list.send_by,
                wo_list.warna,
                wo_list.alat_tambahan,
                wo_list.akses_edit,
                wo_list.cetak,
                barang.nama_barang,
                wo_list.bahan_sendiri
            FROM
                wo_list
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) barang
            ON
                wo_list.ID_Bahan = barang.id_barang  
            WHERE
                wo_list.wio = '$_POST[ID_Order]'
            ";
        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        endif;
    } else {
        $ID_Order = "";
        $status_submit = "Insert_WO_List";
        $nama_submit = "Buka Order";
    }

    if (isset($row)) {
        $kode_barang = $row['kode'];
        $wo_color = $row['wo_color'];
        $marketing = $row['marketing'];
        $id = $row['id'];
        $so = $row['so'];
        $client = $row['client'];
        $project = $row['project'];
        $ID_Bahan = $row['ID_Bahan'];
        $nama_barang = $row['nama_barang'];
        $ukuran = $row['ukuran'];
        $ukuran_jadi = $row['ukuran_jadi'];
        $panjang = $row['panjang'];
        $lebar = $row['lebar'];
        $cetak = $row['cetak'];
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
        if ($row['Ditunggu'] == "Y") {
            $ditunggu = "checked";
        } else {
            $ditunggu = "";
        }
        $leminate = $row['leminate'];
        $finishing = $row['finishing'];
        $qty = $row['qty'];
        $qty_jadi = $row['qty_jadi'];
        $satuan = $row['satuan'];
        if ($row['urgent'] == "Y") {
            $urgent = "checked";
        } else {
            $urgent = "";
        }
        $send_by = $row['send_by'];
        $warna = $row['warna'];
        $alat_tambahan = $row['alat_tambahan'];
        $akses_edit = $row['akses_edit'];
        $cetak = $row['cetak'];
        $validasi_bahan = "1";
        $bahan_sendiri = $row['bahan_sendiri'];
    } else {
        $kode_barang = "";
        $wo_color = "";
        $marketing = "";
        $id = "";
        $so = "";
        $client = "";
        $project = "";
        $ID_Bahan = "";
        $nama_barang = "";
        $ukuran = "";
        $ukuran_jadi = "";
        $panjang = "";
        $lebar = "";
        $cetak = "";
        $potong = "";
        $potong_gantung = "";
        $pon = "";
        $perporasi = "";
        $CuttingSticker = "";
        $Hekter_Tengah = "";
        $Blok = "";
        $Spiral = "";
        $leminate = "";
        $finishing = "";
        $qty = "";
        $qty_jadi = "";
        $satuan = "";
        $urgent = "";
        $send_by = "";
        $warna = "";
        $alat_tambahan = "";
        $akses_edit = "";
        $cetak = "";
        $validasi_bahan = "";
        $proffing = "";
        $ditunggu = "";
        $bahan_sendiri = "";
    }

    echo "<div style='background-color:#f9dedd; border-left:10px solid #e31d3f; text-align:left; padding:5px 5px 5px 15px; margin-bottom:10px; font-weight:bold; letter-spacing:0.005em'>Segala Informasi / Data yang terisi didalam form ini sudah di jamin kebenarannya, dimana Informasi ini sudah siap dijadikan patokan untuk melakukan proses cetak. Apabila terjadi kesalahan informasi dalam form ini maka kesalahan tersebut sepenuhnya ditanggung oleh yang mengisi Form tersebut. Oleh sebab itu disarankan untuk lebih teliti dalam mengisi Form</div>";
?>

    <div class="row">
        <div class="col-6">
            <input type="hidden" id="id_Order" value="<?= $ID_Order ?>">
            <table class='table-form'>
                <tr>
                    <td>Kode Barang</td>
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
                            foreach ($array_kode as $kode => $kd) {
                                if ($kode == $kode_barang) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                                echo "<option value='$kode.$kd' $selected>$kd</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><input type="text" id="id_yescom" value="<?= $id ?>" class="form md" onkeyup="SearchID_YES()" autocomplete="off"></td>
                </tr>
                <tr>
                    <td>Client</td>
                    <td>
                        <input type='text' class='form md' id="client" value='<?= $client ?>' autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td><input type='text' id="deskripsi" class='form ld' value='<?= $project ?>' autocomplete="off"></td>
                </tr>
                <tr>
                    <td>Ukuran</td>
                    <td>
                        <input type='text' class='form' id='ukuran' value='<?= $ukuran ?>'>
                        <span id="ukuran_LF"><input type='number' class='form sd' id='panjang' onkeyup="calc_meter()" value='<?= $panjang ?>'> x <input type='number' class='form sd' id='lebar' onkeyup="calc_meter()" value='<?= $lebar ?>'></span><span id="perhitungan_meter"></span>
                    </td>
                </tr>
                <tr>
                    <td>sisi</td>
                    <td>
                        <?php
                        if ($cetak == "1") {
                            $satu = "checked";
                            $dua = "";
                        } else if ($cetak == "2") {
                            $satu = "";
                            $dua = "checked";
                        } else {
                            $satu = "checked";
                            $dua = "";
                        }
                        ?>
                        <label class="sisi_radio">1 Sisi
                            <input type="radio" name="radio" id="satu_sisi" value="1" <?= $satu ?>>
                            <span class="checkmark"></span>
                        </label>
                        <label class="sisi_radio">2 Sisi
                            <input type="radio" name="radio" id="dua_sisi" value="2" <?= $dua ?>>
                            <span class="checkmark"></span>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2">Bahan</td>
                    <td>
                        <input type='text' class='form md' id="bahan" autocomplete="off" onkeyup="test('bahan')" value='<?= $nama_barang ?>' onChange="validasi('bahan'); Check_KertasSendiri();">
                        <input type='text' id='id_bahan' class='form sd' value='<?= $ID_Bahan ?>' readonly disabled style="display:none">
                        <input type='text' id='validasi_bahan' class='form sd' value='<?= $validasi_bahan ?>' readonly disabled style="display:none">
                        <span id="Alert_Valbahan"></span>
                    </td>
                </tr>
                <tr>
                    <td><input type='text' class='form md' style="width:150px; display:none" id="bahan_sendiri" value="<?= $bahan_sendiri ?>" autocomplete="off" placeholder="Kertas / bahan Sendiri"> <span id="YES_bahan"></span></td>
                </tr>
                <tr>
                    <td>Notes / Finishing LF</td>
                    <td><textarea id='notes' class='form ld' style="height:50px;"><?= $finishing ?></textarea></td>
                </tr>
                <tr>
                    <td>Qty</td>
                    <td colspan="3">
                        <input type='number' class='form sd' id="qty" placeholder='Qty' value='<?= $qty ?>'>
                        <input type='text' class='form' list="list_satuan" id="satuan" placeholder='Satuan' autocomplete="off" onkeyup="satuan_val()" value='<?= $satuan ?>'>
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
                    <td>WO</td>
                    <td colspan="3">
                        <select class="myselect" id="wo_yescom">
                            <?php
                            $array_kode = array(
                                "Kuning"    => "Kuning",
                                "Hijau"     => "Hijau"
                            );
                            foreach ($array_kode as $kode => $kd) {
                                if ($kode == $wo_color) {
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
                        <input type='text' class='form md' style='width:150px' readonly disabled id="so_yescom" value='<?= $so ?>'> -
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
                    <td>Warna</td>
                    <td colspan="3">
                        <select class="myselect" id="warna_cetakan">
                            <?php
                            $array_kode = array(
                                "FC" => "Fullcolor",
                                "BW" => "Grayscale"
                            );
                            foreach ($array_kode as $kode => $kd) {
                                if ($kode == $warna) {
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
                    <td>Laminating</td>
                    <td colspan="3">
                        <select class="myselect" id="laminating">
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
                                if ($kode == $leminate) {
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
                        <select class="myselect" id="alat_tambahan">
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
                            <input class="input-checkbox100" id="Ptg_Pts" type="checkbox" name="remember" <?= $potong ?>>
                            <label class="label-checkbox100" for="Ptg_Pts"> Ptg Putus</label>
                        </div>
                        <div class="contact100-form-checkbox Ptg_Gantung">
                            <input class="input-checkbox100" id="Ptg_Gantung" type="checkbox" name="remember" <?= $potong_gantung ?>>
                            <label class="label-checkbox100" for="Ptg_Gantung"> Ptg Gantung</label>
                        </div>
                        <div class="contact100-form-checkbox CuttingSticker">
                            <input class="input-checkbox100" id="CuttingSticker" type="checkbox" name="remember" <?= $CuttingSticker ?>>
                            <label class="label-checkbox100" for="CuttingSticker"> Cutting Sticker</label>
                        </div>
                        <div class="contact100-form-checkbox Hekter_Tengah">
                            <input class="input-checkbox100" id="Hekter_Tengah" type="checkbox" name="remember" <?= $Hekter_Tengah ?>>
                            <label class="label-checkbox100" for="Hekter_Tengah"> Hekter Tengah</label>
                        </div>
                    </td>
                    <td style='vertical-align:top' colspan="2">
                        <div class="contact100-form-checkbox Pon_Garis">
                            <input class="input-checkbox100" id="Pon_Garis" type="checkbox" name="remember" <?= $pon ?>>
                            <label class="label-checkbox100" for="Pon_Garis"> Pon Garis</label>
                        </div>
                        <div class="contact100-form-checkbox Perporasi">
                            <input class="input-checkbox100" id="Perporasi" type="checkbox" name="remember" <?= $perporasi ?>>
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
            </table>
        </div>
        <div id="submit_menu">
            <button onclick="submit('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
        </div>
        <div id="Result">

        </div>
    </div>
<?php } else {
    $ID_Order = "$_POST[ID_Order]";
    $sql =
        "SELECT 
                wo_list.wio,
                wo_list.client,
                wo_list.id,
                wo_list.marketing,
                wo_list.so,
                wo_list.wo_color,
                wo_list.warna,
                wo_list.ukuran_jadi,
                wo_list.qty_jadi,
                wo_list.project,
                (CASE
                    WHEN wo_list.kode = 'large format' THEN 'Large Format'
                    WHEN wo_list.kode = 'digital' THEN 'Digital Printing'
                    WHEN wo_list.kode = 'indoor' THEN 'Indoor HP Latex'
                    WHEN wo_list.kode = 'Xuli' THEN 'Indoor Xuli'
                    WHEN wo_list.kode = 'offset' THEN 'Offset Printing'
                    WHEN wo_list.kode = 'etc' THEN 'ETC'
                    ELSE '- - -'
                END) as kode,
                (CASE
                    WHEN wo_list.panjang > 0 THEN CONCAT(wo_list.panjang, ' X ', wo_list.lebar, ' Cm')
                    WHEN wo_list.lebar > 0 THEN CONCAT(wo_list.panjang, ' X ', wo_list.lebar, ' Cm')
                    ELSE wo_list.ukuran
                END) as ukuran,
                CONCAT(wo_list.cetak, ' Sisi') as sisi,
                (CASE
                    WHEN barang.id_barang > 0 THEN barang.nama_barang
                    ELSE wo_list.bahan
                END) as bahan,
                wo_list.finishing,
                (CASE
                    WHEN wo_list.leminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                    WHEN wo_list.leminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                    WHEN wo_list.leminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                    WHEN wo_list.leminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                    WHEN wo_list.leminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                    WHEN wo_list.leminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                    WHEN wo_list.leminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                    WHEN wo_list.leminate = 'laminating_floor' THEN 'Laminating Floor'
                    ELSE '- - -'
                END) as laminating,
                (CASE
                    WHEN wo_list.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                    WHEN wo_list.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                    WHEN wo_list.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                    WHEN wo_list.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                    WHEN wo_list.alat_tambahan = 'Tripod' THEN 'Tripod'
                    WHEN wo_list.alat_tambahan = 'Softboard' THEN 'Softboard'
                    WHEN wo_list.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                    ELSE '- - -'
                END) as alat_tambahan,
                CONCAT(wo_list.qty, ' ' ,wo_list.satuan) as qty,
                wo_list.potong,
                wo_list.potong_gantung,
                wo_list.pon,
                wo_list.perporasi,
                wo_list.CuttingSticker,
                wo_list.Hekter_Tengah,
                wo_list.Blok,
                wo_list.Spiral,
                wo_list.urgent,
                wo_list.Ditunggu,
                wo_list.Proffing,
                wo_list.generate,
                (CASE
                    WHEN wo_list.bahan_sendiri != '' THEN CONCAT(' - ' ,wo_list.bahan_sendiri)
                    ELSE ''
                END) as bahan_sendiri
            FROM 
                wo_list
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) barang
            ON
                wo_list.ID_Bahan = barang.id_barang  
            WHERE
                wo_list.wio = '$ID_Order'
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
                    <td><?= ucwords($row['id']); ?></td>
                </tr>
                <tr>
                    <td style='width:150px'>Client</td>
                    <td><?= ucwords($row['client']); ?></td>
                </tr>
                <tr>
                    <td style='width:150px'>Deskripsi</td>
                    <td><?= ucfirst($row['project']); ?></td>
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
                    <td><?= $row['bahan'] . $row['bahan_sendiri']; ?></td>
                </tr>
                <tr>
                    <td style='width:150px'>Notes / Finishing LF</td>
                    <td><?= ucfirst($row['finishing']); ?></td>
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
            </table>
        </div>
        <div class="col-6">
            <table class='table-form'>
                <tr>
                    <td>WO</td>
                    <td colspan="2"><?= $row['wo_color']; ?></td>
                </tr>
                <tr>
                    <td>SO</td>
                    <td colspan="2"><?= $row['so']; ?></td>
                </tr>
                <tr>
                    <td>Marketing</td>
                    <td colspan="2"><?= $row['marketing']; ?></td>
                </tr>
                <tr>
                    <td>Ukuran WO</td>
                    <td colspan="2"><?= $row['ukuran_jadi']; ?></td>
                </tr>
                <tr>
                    <td>Qty WO</td>
                    <td colspan="2"><?= $row['qty_jadi']; ?></td>
                </tr>
                <tr>
                    <td>Warna</td>
                    <td colspan="2"><?= $row['warna']; ?></td>
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
                    <td>
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
            </table>
        </div>
        <div id="submit_menu">
            <?php
            echo "<input type='button' class='generate_button' value='Generate - $row[generate]' onclick='LaodSubForm(\"generator_WoList\", \"" . $row['wio'] . "\", \"$row[id]\");'>";

            ?>
        </div>
    </div>
<?php } ?>