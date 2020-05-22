<?php
    session_start();
    require_once "../../function.php";

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

    echo "<div style='background-color:#f9dedd; border-left:10px solid #e31d3f; text-align:left; padding:5px 5px 5px 15px; margin-bottom:10px; font-weight:bold; letter-spacing:0.005em'>Segala Informasi / Data yang terisi didalam form ini sudah di jamin kebenarannya, dimana Informasi ini sudah siap dijadikan patokan untuk melakukan proses cetak. Apabila terjadi kesalahan informasi dalam form ini maka kesalahan tersebut sepenuhnya ditanggung oleh yang mengisi Form tersebut. Oleh sebab itu disarankan untuk lebih teliti dalam mengisi Form</div>";
?>

    <div class="row">
            <div class="col-6">
                <input type="hidden" id="id_Order" value="">
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
                                    foreach($array_kode as $kode => $kd) {
                                        echo "<option value='$kode.$kd'>$kd</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ID</td>
                        <td><input type="text" id="id_yescom" class="form md" onkeyup="SearchID_YES()"></td>
                    </tr>
                    <tr>
                        <td>Client</td>
                        <td>
                            <input type='text' class='form md' id="client" autocomplete="off">
                        </td>
                    </tr>
                    <tr><td>Deskripsi</td><td><input type='text' id="deskripsi" class='form ld' autocomplete="off"></td></tr>
                    <tr><td>Ukuran</td><td><input type='text' class='form' id='ukuran'> <span id="ukuran_LF"><input type='number' class='form sd' id='panjang' onkeyup="calc_meter()"> x <input type='number' class='form sd' id='lebar' onkeyup="calc_meter()"></span><span id="perhitungan_meter"></span></td></tr>
                    <tr>
                        <td>sisi</td>
                        <td>
                            <label class="sisi_radio">1 Sisi
                                <input type="radio" name="radio" id="satu_sisi" value="1" checked>
                                <span class="checkmark"></span>
                            </label>
                            <label class="sisi_radio">2 Sisi
                                <input type="radio" name="radio" id="dua_sisi" value="2">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr><td rowspan="2">Bahan</td><td>
                        <input type='text' class='form md'id="bahan" autocomplete="off" onkeyup="test('bahan')" onChange="validasi('bahan'); Check_KertasSendiri();" >
                            <input type='text' id='id_bahan' class='form sd' readonly disabled style="display:none">
                            <input type='text' id='validasi_bahan' class='form sd' readonly disabled style="display:none">
                            <span id="Alert_Valbahan"></span>
                    </td></tr>
                    <tr><td><input type='text' class='form md' style="width:150px; display:none" id="bahan_sendiri" autocomplete="off" placeholder="Kertas / bahan Sendiri"> <span id="YES_bahan"></span></td></tr>
                    <tr><td>Notes / Finishing LF</td><td><textarea id='notes' class='form ld' style="height:50px;"></textarea></td></tr>
                    <tr>
                        <td>Qty</td>
                        <td colspan="3">
                            <input type='number' class='form sd' id="qty">
                            <input type='text' class='form' list="list_satuan" id="satuan" autocomplete="off" onkeyup="satuan_val()">
                            <datalist id="list_satuan">
                                <?php
                                    $array_kode = array( "Kotak", "Lembar", "Rim", "Blok", "Pcs" );
                                    foreach($array_kode as $kode) :
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
                        <td colspan="2">
                            <select class="myselect" id="wo_yescom">
                                <?php
                                    $array_kode = array(
                                        "Kuning"    => "Kuning",
                                        "Hijau"     => "Hijau"
                                    );
                                    foreach($array_kode as $kode => $kd) {
                                        echo "<option value='$kode'>$kd</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>SO - Marketing</td>
                        <td colspan="2">
                            <input type='text' class='form md' style='width:150px' readonly disabled value="" id="so_yescom"> - 
                            <input type='text' class='form md' style='width:150px' readonly disabled value="" id="marketing_yescom">
                        </td>
                    </tr>
                    <tr>
                        <td>Ukuran WO</td>
                        <td colspan="2"><input type='text' class='form ld' readonly disabled value="" id="ukuran_yescom"></td>
                    </tr>
                    <tr>
                        <td>Qty WO</td>
                        <td colspan="2"><input type='text' class='form md' readonly disabled value="" id="qty_yescom"></td>
                    </tr>
                    <tr>
                        <td>Warna</td>
                        <td colspan="2">
                            <select class="myselect" id="warna_cetakan">
                                <?php
                                    $array_kode = array(
                                        "FC" => "Fullcolor",
                                        "BW" => "Grayscale"
                                    );
                                    foreach($array_kode as $kode => $kd) {
                                        echo "<option value='$kode'>$kd</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Laminating</td>
                        <td colspan="2">
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
                                    foreach($array_kode as $kode => $kd) :
                                        echo "<option value='$kode.$kd' class='$kode'>$kd</option>";
                                    endforeach;
                                ?>
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <td>Alat Tambahan</td>
                        <td colspan="2">
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
                                    foreach($array_kode as $kode => $kd) :
                                        echo "<option value='$kode.$kd' class='$kode'>$kd</option>";
                                    endforeach;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Finishing</td>
                        <td style='vertical-align:top'>
                            <div class="contact100-form-checkbox Ptg_Pts">
                                <input class="input-checkbox100" id="Ptg_Pts" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Ptg_Pts"> Ptg Putus </label>
                            </div>
                            <div class="contact100-form-checkbox Ptg_Gantung">
                                <input class="input-checkbox100" id="Ptg_Gantung" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Ptg_Gantung"> Ptg Gantung </label>
                            </div>
                            <div class="contact100-form-checkbox CuttingSticker">
                                <input class="input-checkbox100" id="CuttingSticker" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="CuttingSticker"> Cutting Sticker </label>
                            </div>
                            <div class="contact100-form-checkbox Hekter_Tengah">
                                <input class="input-checkbox100" id="Hekter_Tengah" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Hekter_Tengah"> Hekter Tengah </label>
                            </div>
                        </td>
                        <td style='vertical-align:top'>
                            <div class="contact100-form-checkbox Pon_Garis">
                                <input class="input-checkbox100" id="Pon_Garis" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Pon_Garis"> Pon Garis</label>
                            </div>
                            <div class="contact100-form-checkbox Perporasi">
                                <input class="input-checkbox100" id="Perporasi" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Perporasi"> Perporasi </label>
                            </div>
                            <div class="contact100-form-checkbox Blok">
                                <input class="input-checkbox100" id="Blok" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Blok"> Blok </label>
                            </div>
                            <div class="contact100-form-checkbox Ring_Spiral">
                                <input class="input-checkbox100" id="Spiral" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="Spiral"> Ring Spiral </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Status Order</td>
                        <td>
                            <div class="contact100-form-checkbox urgent">
                                <input class="input-checkbox100" id="urgent" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="urgent"> Urgent </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="submit_menu">
                <button onclick="submit('Insert_WO_List')" id="submitBtn">Buka Order</button>
            </div>
            <div id="Result">
            
            </div>    
        </div>