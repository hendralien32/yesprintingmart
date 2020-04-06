<?php
    session_start();
    require_once "../../function.php";
?>

<h3 class='title_form'><?php echo $_POST['judul_form']; ?></h3>
    <?php
        if(isset($_POST['ID_Order']) && $_SESSION['level']=="setter" && $_POST['AksesEdit']=="N") {
            $ID_Order = "$_POST[ID_Order]";

            $sql = 
                "SELECT 
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
                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                        ELSE penjualan.bahan
                    END) as bahan,
                    customer.nama_client,
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
                    CONCAT(format(penjualan.qty,'de_DE'), ' ' ,penjualan.satuan) as qty,
                    penjualan.potong,
                    penjualan.potong_gantung,
                    penjualan.pon,
                    penjualan.perporasi,
                    penjualan.CuttingSticker,
                    penjualan.Hekter_Tengah,
                    penjualan.Blok,
                    penjualan.Spiral,
                    penjualan.Proffing,
                    penjualan.ditunggu,
                    penjualan.Design,
                    penjualan.b_digital,
                    penjualan.b_xbanner,
                    penjualan.b_lain,
                    penjualan.b_offset,
                    penjualan.b_large,
                    penjualan.b_kotak,
                    penjualan.b_laminate,
                    penjualan.b_potong,
                    penjualan.b_design,
                    penjualan.b_indoor,
                    penjualan.b_delivery,
                    penjualan.discount
                FROM 
                    penjualan
                LEFT JOIN 
                    (select customer.cid, customer.nama_client from customer) customer
                ON
                    penjualan.client = customer.cid   
                LEFT JOIN 
                    (select barang.id_barang, barang.nama_barang from barang) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang  
                WHERE
                    penjualan.oid = '$ID_Order'
            ";

            $result = mysqli_query($conn, $sql);
                
            if( mysqli_num_rows($result) === 1 ) {
                $row = mysqli_fetch_assoc($result);
            }
        ?>

        <div class="row">
            <div class="col-6">
                <table class='table-form'>
                    <tr><td style='width:55%'>Kode Barang</td><td><?php echo $row['kode']; ?></td> </tr>
                    <tr><td style='width:55%'>Client</td><td><?php echo ucwords($row['nama_client']); ?></td> </tr>
                    <tr><td style='width:55%'>Deskripsi</td><td><?php echo ucfirst($row['description']); ?></td> </tr>
                    <tr><td style='width:55%'>Ukuran</td><td><?php echo $row['ukuran']; ?></td> </tr>
                    <tr><td style='width:55%'>sisi</td><td><?php echo $row['sisi']; ?></td> </tr>
                    <tr><td style='width:55%'>Bahan</td><td><?php echo $row['bahan']; ?></td> </tr>
                    <tr><td style='width:55%'>Notes / Finishing LF</td><td><?php echo ucfirst($row['keterangan']); ?></td> </tr>
                    <?php
                        if($row['kode']=="Digital Printing") {
                    ?>
                        <tr><td style='width:55%'>Biaya Digital</td><td><?php echo "Rp. ". number_format($row['b_digital']) .""; ?></td> </tr>
                        <tr><td style='width:55%'>Biaya Kotak</td><td><?php echo "Rp. ". number_format($row['b_kotak']) .""; ?></td> </tr>
                        <tr><td style='width:55%'>Biaya Finishing</td><td><?php echo "Rp. ". number_format($row['b_potong']) .""; ?></td> </tr>    
                        <tr><td style='width:55%'>Biaya Laminating</td><td><?php echo "Rp. ". number_format($row['b_laminate']) .""; ?></td> </tr>
                    <?php
                        } elseif($row['kode']=="Large Format") { 
                    ?>
                        <tr><td style='width:55%'>Biaya Large Format</td><td><?php echo "Rp. ". number_format($row['b_large']) .""; ?></td> </tr> 
                        <tr><td style='width:55%'>Biaya Xbanner</td><td><?php echo "Rp. ". number_format($row['b_xbanner']) .""; ?></td> </tr> 
                        <tr><td style='width:55%'>Biaya Laminating</td><td><?php echo "Rp. ". number_format($row['b_laminate']) .""; ?></td> </tr>
                    <?php
                        } elseif($row['kode']=="Indoor HP Latex" or $row['kode']=="Indoor Xuli") { 
                    ?>
                        <tr><td style='width:55%'>Biaya Indoor</td><td><?php echo "Rp. ". number_format($row['b_indoor']) .""; ?></td> </tr>  
                        <tr><td style='width:55%'>Biaya Xbanner</td><td><?php echo "Rp. ". number_format($row['b_xbanner']) .""; ?></td> </tr>
                        <tr><td style='width:55%'>Biaya Laminating</td><td><?php echo "Rp. ". number_format($row['b_laminate']) .""; ?></td> </tr>
                    <?php        
                        } elseif($row['kode']=="Offset Printing") { 
                    ?>
                        <tr><td style='width:55%'>Biaya Offset</td><td><?php echo "Rp. ". number_format($row['b_offset']) .""; ?></td> </tr>
                    <?php
                        } elseif($row['kode']=="ETC") { 
                    ?>
                        <tr><td style='width:55%'>Biaya Lain</td><td><?php echo "Rp. ". number_format($row['b_lain']) .""; ?></td> </tr>
                        <tr><td style='width:55%'>Biaya Finishing</td><td><?php echo "Rp. ". number_format($row['b_potong']) .""; ?></td> </tr>    
                    <?php
                        }
                    ?>
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
                    <tr><td>Laminating</td><td colspan="3"><?php echo $row['laminating']; ?></td> </tr>
                    <tr><td>Alat Tambahan</td><td colspan="3"><?php echo $row['alat_tambahan']; ?></td> </tr>
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
                                "Proffing",
                                "ditunggu",
                                "Design"
                            );
                            foreach($array_kode as $kode) {
                                if($row[$kode]=="Y") {
                                    ${'check_'.$kode} = "<i class='fad fa-check-square'></i>";
                                } else {
                                    ${'check_'.$kode} = "<i class='fad fa-times-square'></i>";
                                }
                            }
                        ?>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?php echo $check_potong; ?>
                                <label class='checkbox-fa' for='Ptg_Pts'> Ptg Putus </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_potong_gantung; ?>
                                <label class='checkbox-fa' for='Ptg_Gantung'> Ptg Gantung </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_pon; ?>
                                <label class='checkbox-fa' for='Pon_Garis'> Pon Garis </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_perporasi; ?>
                                <label class='checkbox-fa' for='Perporasi'> Perporasi </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="contact100-form-checkbox">
                                <?php echo $check_CuttingSticker; ?>
                                <label class='checkbox-fa' for='CuttingSticker'> Cutting Sticker </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_Hekter_Tengah; ?>
                                <label class='checkbox-fa' for='Hekter_Tengah'> Hekter Tengah </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_Blok; ?>
                                <label class='checkbox-fa' for='Blok'> Blok </label>
                            </div>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_Spiral; ?>
                                <label class='checkbox-fa' for='Spiral'> Ring Spiral </label>
                            </div>
                        </td>
                    </tr>
                    <tr> <td>Qty</td><td colspan="3"><?php echo $row['qty']; ?></td> </tr>
                    <tr>
                        <td>Permintaan Order</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <?php echo $check_Proffing; ?>
                                <label class='checkbox-fa' for='proffing'> Proffing</label>
                            </div>
                        </td>
                        <td>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_ditunggu; ?>
                                <label class='checkbox-fa' for='Ditunggu'> Ditunggu </label>
                            </div>
                        </td>
                        <td>
                            <div class='contact100-form-checkbox'>
                                <?php echo $check_Design; ?>
                                <label class='checkbox-fa' for='Design'> Design </label>
                            </div>
                        </td>
                    </tr>
                    <tr><td>Biaya Design</td><td><?php echo "Rp. ". number_format($row['b_design']) .""; ?></td> </tr>
                    <tr><td>Biaya Delivery</td><td><?php echo "Rp. ". number_format($row['b_delivery']) .""; ?></td> </tr>
                    <tr><td>Biaya Discounts</td><td style="color:red; font-weight:bold"><?php echo "Rp. ". number_format($row['discount']) .""; ?></td> </tr>
                </table>
            </div>
        </div>
    <?php
        } elseif(isset($_POST['ID_Order']) && ( $_SESSION['level']=="admin" || $_SESSION['level']=="setter" ) && $_POST['AksesEdit']=="Y") { // Update

        if(isset($_POST['ID_Order'])!="") {
            $ID_Order = "$_POST[ID_Order]";
            $sql = 
            "SELECT
                penjualan.no_invoice,
                penjualan.description,
                penjualan.kode,
                customer.nama_client,
                customer.cid as ID_Client,
                penjualan.ukuran,
                penjualan.panjang,
                penjualan.lebar,
                penjualan.sisi,
                penjualan.ID_Bahan,
                Bahan.nama_barang,
                penjualan.keterangan,
                penjualan.qty,
                penjualan.satuan,
                penjualan.laminate,
                penjualan.alat_tambahan,
                penjualan.potong,
                penjualan.potong_gantung,
                penjualan.pon,
                penjualan.perporasi,
                penjualan.CuttingSticker,
                penjualan.Hekter_Tengah,
                penjualan.Blok,
                penjualan.Spiral,
                penjualan.ditunggu,
                penjualan.Proffing,
                penjualan.Design,
                penjualan.img_design,
                penjualan.file_design,
                penjualan.b_digital,
                penjualan.b_xbanner,
                penjualan.b_lain,
                penjualan.b_offset,
                penjualan.b_large,
                penjualan.b_kotak,
                penjualan.b_laminate,
                penjualan.b_potong,
                penjualan.b_design,
                penjualan.b_indoor,
                penjualan.b_delivery,
                penjualan.discount,
                (CASE
                    WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                    WHEN penjualan.akses_edit = 'N' THEN 'N'
                    ELSE 'N'
                END) as akses_edit
            FROM
                penjualan
            LEFT JOIN 
                (select customer.cid, customer.nama_client from customer) customer
            ON
                penjualan.client = customer.cid  
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) Bahan
            ON
                penjualan.ID_Bahan = Bahan.id_barang  
            WHERE
                penjualan.oid = '$ID_Order'
            ";

            $result = mysqli_query($conn, $sql);
                
            if( mysqli_num_rows($result) === 1 ) {
                $row = mysqli_fetch_assoc($result);

                if($row['sisi']=="1") { $satu = "checked"; } else { $satu = ""; }
                if($row['sisi']=="2") { $dua = "checked"; } else { $dua = ""; }
                if($row['potong']=="Y") { $potong = "checked"; } else { $potong = ""; }
                if($row['potong_gantung']=="Y") { $potong_gantung = "checked"; } else { $potong_gantung = ""; }
                if($row['pon']=="Y") { $pon = "checked"; } else { $pon = ""; }
                if($row['perporasi']=="Y") { $perporasi = "checked"; } else { $perporasi = ""; }
                if($row['CuttingSticker']=="Y") { $CuttingSticker = "checked"; } else { $CuttingSticker = ""; }
                if($row['Hekter_Tengah']=="Y") { $Hekter_Tengah = "checked"; } else { $Hekter_Tengah = ""; }
                if($row['Blok']=="Y") { $Blok = "checked"; } else { $Blok = ""; }
                if($row['Spiral']=="Y") { $Spiral = "checked"; } else { $Spiral = ""; }
                if($row['ditunggu']=="Y") { $ditunggu = "checked"; } else { $ditunggu = ""; }
                if($row['Proffing']=="Y") { $Proffing = "checked"; } else { $Proffing = ""; }
                if($row['Design']=="Y") { $Design = "checked"; } else { $Design = ""; }
                if($row['akses_edit']=="Y") { $akses_edit = "checked"; } else { $akses_edit = ""; }
            }
        }
    ?>
        <div class="row">
            <div class="col-6">
                <input type="hidden" id="id_user" value="<?php echo $_SESSION['uid']; ?>">
                <input type="hidden" id="id_order" value="<?php echo $_POST['ID_Order']; ?>">
                <input type="hidden" id="no_invoice" value="<?php echo $row['no_invoice']; ?>">
                <table class='table-form'>
                    <tr>
                        <td>Kode Barang</td>
                        <td>
                        <select class="myselect" id="kode_barng" onchange="ChangeKodeBrg()">
                            <option value="">Pilih Kode Barang</option>
                            <?php
                                $array_kode = array(
                                    "digital" => "Digital Printing",
                                    "large format" => "Large Format",
                                    "indoor" => "Indoor HP Latex",
                                    "Xuli" => "Indoor Xuli",
                                    "offset" => "Offset",
                                    "etc" => "ETC"
                                );
                                foreach($array_kode as $kode => $kd) {
                                    if($kode=="$row[kode]") {$pilih = "selected";} else {$pilih = "";}
                                    echo "<option value='$kode.$kd' $pilih>$kd</option>";
                                }
                            ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Client</td>
                        <td>
                            <input type='text' class='form md' id="client" autocomplete="off" onkeyup="test('client')" onchange="validasi('client')" value="<?php echo $row['nama_client']; ?>">
                            <input type='text' id='id_client' class='form sd' value="<?php echo $row['ID_Client']; ?>" readonly disabled style="display:none;">
                            <input type='text' id='validasi_client' class='form sd' readonly disabled style="display:none;">
                            <span id="Alert_Valclient"></span>
                        </td>
                    </tr>
                    <tr><td>Deskripsi</td><td><input type='text' id="deskripsi" autocomplete="off" class='form ld' value="<?php echo $row['description']; ?>"></td></tr>
                    <tr><td>Ukuran</td><td><input type='text' class='form' id='ukuran' value="<?php echo $row['ukuran']; ?>"> <span id="ukuran_LF"><input type='number' class='form sd' id='panjang' value="<?php echo $row['panjang']; ?>" onkeyup="calc_meter()"> x <input type='number' class='form sd' id='lebar' onkeyup="calc_meter()" value="<?php echo $row['lebar']; ?>"></span><span id="perhitungan_meter"></span></td></tr>
                    <tr>
                        <td>sisi</td>
                        <td>
                            <label class="sisi_radio">1 Sisi
                                <input type="radio" name="radio" id="satu_sisi" value="1" <?php echo $satu; ?>>
                                <span class="checkmark"></span>
                            </label>
                            <label class="sisi_radio">2 Sisi
                                <input type="radio" name="radio" id="dua_sisi" value="2" <?php echo $dua; ?>>
                                <span class="checkmark"></span>
                            </label>
                        </td>
                    </tr>
                    <tr><td>Bahan</td><td>
                        <input type='text' class='form md' id="bahan" value="<?php echo $row['nama_barang']; ?>" autocomplete="off" onkeyup="test('bahan')" onchange="validasi('bahan')">
                            <input type='text' id='id_bahan' value="<?php echo $row['ID_Bahan']; ?>" class='form sd' readonly disabled  style="display:none">
                            <input type='text' id='validasi_bahan' class='form sd' readonly disabled
                            style="display:none">
                            <span id="Alert_Valbahan"></span>
                    </td></tr>
                    <tr><td>Notes / Finishing LF</td><td><textarea id='notes' class='form ld' style="height:50px;"><?php echo $row['keterangan']; ?></textarea></td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
                    <tr>
                        <td>Qty</td>
                        <td colspan="3">
                            <input type='number' class='form sd' id="qty" value="<?php echo $row['qty']; ?>">
                            <input type='text' class='form' list="list_satuan" id="satuan" autocomplete="off" onkeyup="satuan_val()" value="<?php echo $row['satuan']; ?>">
                            <datalist id="list_satuan">
                                <?php
                                        $array_kode = array( "Kotak", "Lembar", "Rim", "Blok", "Pcs" );
                                        foreach($array_kode as $kode) { echo "<option value='$kode'>"; }
                                ?>
                            </datalist>
                        </td>
                    </tr>
                    <tr>
                        <td>Laminating</td>
                        <td colspan="3">
                            <select class="myselect" id="laminating">
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
                                    foreach($array_kode as $kode => $kd) {
                                        if($kode=="$row[laminate]") {$pilih = "selected";} else {$pilih = "";}
                                        echo "<option value='$kode.$kd' $pilih class='$kode'>$kd</option>";
                                    }
                                ?>
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <td>Alat Tambahan</td>
                        <td colspan="3">
                            <select class="myselect" id="alat_tambahan">
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
                                    foreach($array_kode as $kode => $kd) {
                                        if($kode=="$row[alat_tambahan]") {$pilih = "selected";} else {$pilih = "";}
                                        echo "<option value='$kode.$kd' $pilih class='$kode'>$kd</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="finishing">
                        <td>Finishing</td>
                        <td style='vertical-align:top'>
                            <div class="contact100-form-checkbox Ptg_Pts">
                                <input class="input-checkbox100" id="Ptg_Pts" type="checkbox" name="remember" <?php echo $potong; ?>>
                                <label class="label-checkbox100" for="Ptg_Pts"> Ptg Putus </label>
                            </div>
                            <div class="contact100-form-checkbox Ptg_Gantung">
                                <input class="input-checkbox100" id="Ptg_Gantung" type="checkbox" name="remember" <?php echo $potong_gantung; ?>>
                                <label class="label-checkbox100" for="Ptg_Gantung"> Ptg Gantung </label>
                            </div>
                            <div class="contact100-form-checkbox CuttingSticker">
                                <input class="input-checkbox100" id="CuttingSticker" type="checkbox" name="remember" <?php echo $CuttingSticker; ?>>
                                <label class="label-checkbox100" for="CuttingSticker"> Cutting Sticker </label>
                            </div>
                            <div class="contact100-form-checkbox Hekter_Tengah">
                                <input class="input-checkbox100" id="Hekter_Tengah" type="checkbox" name="remember" <?php echo $Hekter_Tengah; ?>>
                                <label class="label-checkbox100" for="Hekter_Tengah"> Hekter Tengah </label>
                            </div>
                        </td>
                        <td colspan="2" style='vertical-align:top'>
                            <div class="contact100-form-checkbox Pon_Garis">
                                <input class="input-checkbox100" id="Pon_Garis" type="checkbox" name="remember" <?php echo $pon; ?>>
                                <label class="label-checkbox100" for="Pon_Garis"> Pon Garis</label>
                            </div>
                            <div class="contact100-form-checkbox Perporasi">
                                <input class="input-checkbox100" id="Perporasi" type="checkbox" name="remember" <?php echo $perporasi; ?>>
                                <label class="label-checkbox100" for="Perporasi"> Perporasi </label>
                            </div>
                            <div class="contact100-form-checkbox Blok">
                                <input class="input-checkbox100" id="Blok" type="checkbox" name="remember" <?php echo $Blok; ?>>
                                <label class="label-checkbox100" for="Blok"> Blok </label>
                            </div>
                            <div class="contact100-form-checkbox Ring_Spiral">
                                <input class="input-checkbox100" id="Spiral" type="checkbox" name="remember" <?php echo $Spiral; ?>>
                                <label class="label-checkbox100" for="Spiral"> Ring Spiral </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Permintaan Order</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="proffing" type="checkbox" name="remember" <?php echo $Proffing; ?>>
                                <label class="label-checkbox100" for="proffing"> Proffing </label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ditunggu" type="checkbox" name="remember" <?php echo $ditunggu; ?>>
                                <label class="label-checkbox100" for="ditunggu"> Ditunggu </label>
                            </div>
                        </td>
                        <td>
                        <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="Design" type="checkbox" name="remember" onclick="upload_design();" <?php echo $Design; ?>>
                                <label class="label-checkbox100" for="Design"> Design</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="upload_design" style="display:none;">
                        <td valign="top">File Design <i class="fas fa-archive"></i></td>
                        <td colspan="3">
                        <input type="hidden" name="Val_FileDesign" id="Val_FileDesign" value="1">
                            <input type="file" name="file_design" id="file_design" onchange="file_validasi('FileDesign')">
                            <span id="Alert_Val_FileDesign"></span>
                            <?php if($row['file_design']!="") { ?>
                            <br>
                            <b><a href="../program/design/<?php echo $row['file_design']; ?>"><?php echo $row['file_design']; ?> <i class="fas fa-download"></i></a></b>
                            <?php } else { } ?>
                        </td>
                    </tr>
                    <tr class="upload_design" style="display:none;">
                        <td valign="top">File Image Preview <i class="fas fa-image"></i></td>
                        <td colspan="3">
                            <input type="hidden" name="Val_FileImage" id="Val_FileImage" value="1">
                            <input type="file" name="file_Image" id="file_Image" onchange="file_validasi('FileImage')">
                            <span id="Alert_Val_FileImage"></span>
                            <?php if($row['img_design']!="") { ?>
                            <br>
                            <b onclick="LaodSubForm('setter_penjualan_preview', '<?php echo $ID_Order; ?>');" style='cursor:pointer;'><?php echo $row['img_design']; ?> <i class="fas fa-search-plus"></i></b>
                            <?php } else { } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Admin Menu</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="Auto_Calc" type="checkbox" name="remember" onclick="AksesEdit()" <?php if($_SESSION["level"] != "admin") { echo "Disabled"; } else { }?> >
                                <label class="label-checkbox100" for="Auto_Calc"> Auto Calc </label>
                            </div>
                        </td>
                        <td colspan="2" >
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="akses_edit" type="checkbox" name="remember" onclick="AksesEdit()" <?php if($_SESSION["level"] != "admin") { echo "Disabled "; } else { }  echo "$akses_edit"; ?> >
                                <label class="label-checkbox100" for="akses_edit"> Akses Edit</label>
                            </div>
                        </td>
                    </tr>
                    <tr class='progress_table' style="display:none;">
                        <td>Progress Uplaod</td>
                        <td colspan="3">
                            <div class="progress" style="height: 25px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">0%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <table class='table-form'>
                    <tr class='b_digital'>
                        <td>Biaya Digital</td>
                        <td><input id="b_digital" type='number' class='form ld' value="<?php echo $row['b_digital']; ?>"></td>
                    </tr>

                    <tr class='b_kotak'>
                        <td>Biaya Kotak</td>
                        <td><input id="b_kotak" type='number' class='form ld' value="<?php echo $row['b_kotak']; ?>"></td>
                    </tr>

                    <tr class='b_lain'>
                        <td>Biaya Lain</td>
                        <td><input id="b_lain" type='number' class='form ld' value="<?php echo $row['b_lain']; ?>"></td>
                    </tr>

                    <tr class='b_finishing'>
                        <td>Biaya Finishing</td>
                        <td><input id="b_finishing" type='number' class='form ld' value="<?php echo $row['b_potong']; ?>"></td>
                    </tr>

                    <tr class='b_lf'>
                        <td>Biaya Large Format</td>
                        <td><input id="b_large" type='number' class='form ld' value="<?php echo $row['b_large']; ?>"></td>
                    </tr>

                    <tr class='b_indoor'>
                        <td>Biaya Indoor</td>
                        <td><input id="b_indoor" type='number' class='form ld' value="<?php echo $row['b_indoor']; ?>"></td>
                    </tr>

                    <tr class='b_xbanner'>
                        <td>Biaya Xbanner</td>
                        <td><input id="b_xbanner" type='number' class='form ld' value="<?php echo $row['b_xbanner']; ?>"></td>
                    </tr>
                    
                    <tr class='b_offset'>
                        <td>Biaya Offset</td>
                        <td><input id="b_offset" type='number' class='form ld' value="<?php echo $row['b_offset']; ?>"></td>
                    </tr>

                    <tr class='b_laminating'>
                        <td>Biaya Laminating</td>
                        <td><input id="b_laminate" type='number' class='form ld' value="<?php echo $row['b_laminate']; ?>"></td>
                    </tr>
        
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
                    <tr class='b_design'>
                        <td>Biaya Design</td>
                        <td><input id="b_design" type='number' class='form ld' value="<?php echo $row['b_design']; ?>"></td>
                    </tr>

                    <tr class='b_delivery'>
                        <td>Biaya Delivery</td>
                        <td><input id="b_delivery" type='number' class='form ld' value="<?php echo $row['b_delivery']; ?>"></td>
                    </tr>    

                    <tr class='b_discount'>
                        <td>Biaya Discounts</td>
                        <td><input id="discount" type='number' class='form ld' value="<?php echo $row['discount']; ?>"></td>
                    </tr>
                </table>
            </div>
            <div id="submit_menu">
                <?php if($row['no_invoice']!="0") {?>
                    <button onclick="submit('Update_SO_Invoice')">Update Order Invoice</button>
                <?php } else { ?>
                    <button onclick="submit('Update')">Update Order</button>
                <?php } ?>
            </div>
            <div id="Result">
            
            </div> 
        </div>

    <?php
        } else { // Insert
    ?>
        
        <div class="row">
            <div class="col-6">
                <input type="hidden" id="id_user" value="<?php echo $_SESSION['uid']; ?>">
                <table class='table-form'>
                    <tr>
                        <td>Kode Barang</td>
                        <td>
                        <select class="myselect" id="kode_barng" onchange="ChangeKodeBrg()">
                            <option value="">Pilih Kode Barang</option>
                            <?php
                                $array_kode = array(
                                    "digital" => "Digital Printing",
                                    "large format" => "Large Format",
                                    "indoor" => "Indoor HP Latex",
                                    "Xuli" => "Indoor Xuli",
                                    "offset" => "Offset",
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
                        <td>Client</td>
                        <td>
                            <input type='text' class='form md' id="client" autocomplete="off" onkeyup="test('client')" onchange="validasi('client')">
                            <input type='text' id='id_client' class='form sd' readonly disabled style="display:none">
                            <input type='text' id='validasi_client' class='form sd' readonly disabled style="display:none">
                            <span id="Alert_Valclient"></span>
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
                    <tr><td>Bahan</td><td>
                        <input type='text' class='form md' id="bahan" autocomplete="off" onkeyup="test('bahan')" onchange="validasi('bahan')">
                            <input type='text' id='id_bahan' class='form sd' readonly disabled style="display:none">
                            <input type='text' id='validasi_bahan' class='form sd' readonly disabled style="display:none">
                            <span id="Alert_Valbahan"></span>
                    </td></tr>
                    <tr><td>Notes / Finishing LF</td><td><textarea id='notes' class='form ld' style="height:50px;"></textarea></td></tr>
                    <tr>
                        <td>Qty</td>
                        <td colspan="3">
                            <input type='number' class='form sd' id="qty">
                            <input type='text' class='form' list="list_satuan" id="satuan" autocomplete="off" onkeyup="satuan_val()">
                            <datalist id="list_satuan">
                                <?php
                                        $array_kode = array(
                                            "Kotak", "Lembar", "Rim", "Blok", "Pcs"
                                        );
                                        foreach($array_kode as $kode) {
                                            echo "<option value='$kode'>";
                                        }
                                ?>
                            </datalist>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class='table-form'>
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
                                    foreach($array_kode as $kode => $kd) {
                                        if($kode=="$d[kode]") {$pilih = "selected";} else {$pilih = "";}
                                        echo "<option value='$kode.$kd' $pilih class='$kode'>$kd</option>";
                                    }
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
                                    foreach($array_kode as $kode => $kd) {
                                        if($kode=="$d[kode]") {$pilih = "selected";} else {$pilih = "";}
                                        echo "<option value='$kode.$kd' $pilih class='$kode'>$kd</option>";
                                    }
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
                        <td colspan="2" style='vertical-align:top'>
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
                        <td>Permintaan Order</td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="proffing" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="proffing"> Proffing </label>
                            </div>
                        </td>
                        <td>
                            <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="ditunggu" type="checkbox" name="remember">
                                <label class="label-checkbox100" for="ditunggu"> Ditunggu </label>
                            </div>
                        </td>
                        <td>
                        <div class="contact100-form-checkbox">
                                <input class="input-checkbox100" id="Design" type="checkbox" name="remember" onclick="upload_design();">
                                <label class="label-checkbox100" for="Design"> Design</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="upload_design" style="display:none;">
                        <td valign="top">File Design <i class="fas fa-archive"></i></td>
                        <td colspan="3">
                        <input type="hidden" name="Val_FileDesign" id="Val_FileDesign" value="1">
                            <input type="file" name="file_design" id="file_design" onchange="file_validasi('FileDesign')">
                            <span id="Alert_Val_FileDesign"></span>
                        </td>
                    </tr>
                    <tr class="upload_design" style="display:none;">
                        <td valign="top">File Image Preview <i class="fas fa-image"></i></td>
                        <td colspan="3">
                            <input type="hidden" name="Val_FileImage" id="Val_FileImage" value="1">
                            <input type="file" name="file_Image" id="file_Image" onchange="file_validasi('FileImage')">
                            <span id="Alert_Val_FileImage"></span>
                        </td>
                    </tr>
                    <tr class='progress_table' style="display:none;">
                        <td>Progress Uplaod</td>
                        <td colspan="3">
                            <div class="progress" style="height: 25px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">0%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="submit_menu">
                <button onclick="submit('Insert')" id="submitBtn">Buka Order</button>
            </div>
            <div id="Result">
            
            </div>    
               
        </div>
    <?php        
        }
    ?>