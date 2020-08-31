<?php
session_start();
require_once "../../function.php";

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

$ID_Order = "$_POST[ID_Order]";
    $sql =
        "SELECT 
                penjualan.description,
                (CASE
                    WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    ELSE penjualan.ukuran
                END) as ukuran,
                CONCAT(penjualan.sisi, ' Sisi') as sisi,
                penjualan.sisi as form_sisi,
                (CASE
                    WHEN barang.id_barang > 0 THEN barang.nama_barang
                    ELSE penjualan.bahan
                END) as bahan,
                customer.nama_client,
                penjualan.keterangan,
                penjualan.client_yes,
                penjualan.id_yes,
                penjualan.so_yes,
                penjualan.warna_cetak as Warna_Cetak,
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
                    WHEN penjualan.alat_tambahan = 'KotakNC' THEN '31'
                    ELSE '0'
                END) as ID_AlatTambahan,
                CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
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
                penjualan.satuan,
                penjualan.qty as Qty_Order
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

    // Perform query
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        // output data of each row
        $d = $result->fetch_assoc();

        if($d['satuan']=="Kotak" || $d['satuan']=="KOTAK" || $d['satuan']=="kotak") {
            $Qty_Val = $d['Qty_Order']*4;
        } else {
            $Qty_Val = $d['Qty_Order'];
        }

        if($d['client_yes'] != "") {
            $client_yes = " - <strong style='color:#f1592a'>$d[client_yes]</strong>";
        } else {
            $client_yes = "";
        }
        if($d['id_yes'] != "0") {
            $id_yes = " - <strong style='color:#f1592a'>$d[id_yes]</strong>";
        } else {
            $id_yes = "";
        }
        if($d['so_yes'] != "0") {
            $id_yes .= "<strong style='color:#f1592a'> / $d[so_yes]</strong>";
        } else {
            $id_yes .= "";
        }
    else : 
        $client_yes = "";
        $id_yes = "";
    endif;
?>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <input type='hidden' id='id_order' value="<?= $ID_Order ?>">
            <tr>
                <td style='width:145px'>Operator</td>
                <td><?= $_SESSION["username"] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>ID</td>
                <td><?= $ID_Order . $id_yes ?> <?php echo "<a href='print.php?type=print_oid&oid=$ID_Order' target='_blank' class='pointer' style='padding-left:10px'><i class='fad fa-print'></i></a>"; ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Client</td>
                <td><?= $d['nama_client'] . $client_yes ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Desciption</td>
                <td><?= $d['description'] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Notes</td>
                <td><strong style='color:#ff7200;'><?= $d['keterangan'] ?></strong></td>
            </tr>
            <tr>
                <td style='width:145px'>Laminating</td>
                <td><?= $d['laminating'] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Alat Tambahan</td>
                <td><?= $d['alat_tambahan'] ?></td>
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
                            echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='width:96%'>";
                        else :
                            echo "<input type='date' id='tanggal_bayar' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='display:none'> ". date("d M Y", strtotime($date)) ."";
                        endif;
                    ?>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Finishing</td>
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
                        "ditunggu"
                    );
                    foreach ($array_kode as $kode) :
                        if ($d[$kode] == "Y") : ${'check_' . $kode} = "<i class='fad fa-check-square'></i>";
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
                <td style='width:145px'>Permintaan Order</td>
                <td>
                        <div class="contact100-form-checkbox">
                            <?= $check_Proffing; ?>
                            <label class='checkbox-fa' for='proffing'> Proffing</label>
                        </div>
                    </td>
                    <td>
                        <div class='contact100-form-checkbox'>
                            <?= $check_ditunggu; ?>
                            <label class='checkbox-fa' for='Ditunggu'> Ditunggu </label>
                        </div>
                    </td>
            </tr>
        </table>
    </div>
</div>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Kertas</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value=''>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_BahanDigital" class="form sd" readonly disabled>
                    <span id="Alert_ValBahanDigital"></span>
                    <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect'><i class='fas fa-info-square'></i> $d[bahan]</strong>"; ?>
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
                            if ($kode == "$d[form_sisi]") : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
                        }
                        ?>
                    </select> <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect'><i class='fas fa-info-square'></i> $d[sisi]</strong>"; ?>
                    </td>
            </tr>
            <tr>
                <td style='width:145px'>Qty</td>
                <td>
                    <input id="Qty" type='number' class='form md' value="">
                    <input id="Val_Qty" type='hidden' class='form md' value="<?= $Qty_Val; ?>">
                    <div class="contact100-form-checkbox" style='float:right; margin-top:4px; margin-left:10px'>
                            <input class="input-checkbox100" id="jumlah_click" type="checkbox" name="remember">
                            <label class="label-checkbox100" for="jumlah_click"> 1 Click <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect'><i class='fas fa-info-square'></i> $d[qty]</strong>"; ?> </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Alat Tambahan</td>
                <td><input id="Qty_AlatTambahan" type='number' class='form md' value=""> <input id="id_tambahan" type='hidden' class='form md' value="<?php $d['ID_AlatTambahan'] ?>"></td>
            </tr>
            <tr>
                <td style='width:145px'>Jammed</td>
                <td><input id="Jammed" type='number' class='form md' value=""></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
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
                            if ($kode == "$d[Warna_Cetak]") : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
                        }
                        ?> <?= $d['Warna_Cetak'] ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Error</td>
                <td><input id="Error" type='number' class='form md' value=""></td>
            </tr>
            <tr>
                <td style='width:145px'>Kesalahan</td>
                <td><input id="Kesalahan" type='text' class='form md' value=""></td>
            </tr>
            <tr>
                <td style='width:145px'>Alasan Error</td>
                <td><textarea id='alasan_error' class='form ld' style="height:50px; min-height:50px;"></textarea></td>
            </tr>
            <tr>
                <td style='width:145px'>Status</td>
                <td>
                    <select class="myselect" id="status_Cetak">
                        <?php
                        $array_kode = array(
                            "" => "OnProgress",
                            "proff" => "Proffing",
                            "selesai" => "Finished"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            echo "<option value='$kode'>$kd</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="submit_menu">
    <button onclick="submit('submit_dp')" id="submitBtn">Submit Data</button>
</div>
<div id="Result">

</div>