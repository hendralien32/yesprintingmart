<?php
session_start();
require_once "../../function.php";

$ID_Order = "$_POST[ID_Order]";
$sql =
    "SELECT
        LEFT(digital_printing.tgl_cetak,10) as tanggal,
        RIGHT(digital_printing.tgl_cetak,8) as Jam,
        digital_printing.did,
        digital_printing.mesin,
        digital_printing.kesalahan,
        digital_printing.alasan_kesalahan,
        digital_printing.sisi as sisi_digital,
        digital_printing.color,
        (CASE
            WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
            WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
            ELSE ROUND(digital_printing.qty_cetak / 2)
        END) as qty_cetak,
        (CASE
            WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
            WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
            ELSE ROUND(digital_printing.error / 2)
        END) as error,
        digital_printing.qty_etc,
        digital_printing.jam,
        digital_printing.hitungan_click,
        digital_printing.kode_bahan,
        digital_printing.id_bahan,
        (CASE
            WHEN barang.nama_barang != '' THEN barang.nama_barang
            WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
            ELSE '- - -'
        END) as nama_barang,
        penjualan.oid,
        penjualan.description,
        penjualan.ukuran,
        penjualan.sisi,
        penjualan.form_sisi,
        penjualan.bahan,
        penjualan.nama_client,
        penjualan.keterangan,
        penjualan.client_yes,
        penjualan.id_yes,
        penjualan.so_yes,
        penjualan.Warna_Cetak,
        penjualan.laminating,
        penjualan.alat_tambahan,
        penjualan.ID_AlatTambahan,
        penjualan.qty,
        penjualan.potong,
        penjualan.potong_gantung,
        penjualan.pon,
        penjualan.perporasi,
        penjualan.CuttingSticker,
        penjualan.Hekter_Tengah,
        penjualan.Blok,
        penjualan.Spiral,
        penjualan.Proffing,
        penjualan.status,
        penjualan.ditunggu,
        penjualan.satuan,
        penjualan.Qty_Order,
        penjualan.nama,
        nama_salah.nama as nama_salah
    FROM
        digital_printing
    LEFT JOIN (
        SELECT 
            penjualan.oid,
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
            penjualan.status,
            penjualan.ditunggu,
            penjualan.satuan,
            penjualan.qty as Qty_Order,
            setter.nama
        FROM 
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid   
        LEFT JOIN 
            (select pm_user.uid, pm_user.nama from pm_user) setter
        ON
            penjualan.setter = setter.uid   
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) barang
        ON
            penjualan.ID_Bahan = barang.id_barang
    ) penjualan
    ON 
        penjualan.oid = digital_printing.oid
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
    LEFT JOIN 
        (select pm_user.uid, pm_user.nama from pm_user) nama_salah
    ON
        digital_printing.kesalahan = nama_salah.uid   
    WHERE
        digital_printing.did = $ID_Order
    LIMIT
        1
";

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    // output data of each row
    $d = $result->fetch_assoc();

    if ($d['status'] == 'selesai') {
        $display_form = "none";
    } else {
        $display_form = "";
    }

    if ($d['satuan'] == "Kotak" || $d['satuan'] == "KOTAK" || $d['satuan'] == "kotak") {
        $Qty_Val = $d['Qty_Order'] * 4;
    } else {
        $Qty_Val = $d['Qty_Order'];
    }

    if ($d['client_yes'] != "") {
        $client_yes = " - <strong style='color:#f1592a'>$d[client_yes]</strong>";
    } else {
        $client_yes = "";
    }
    if ($d['id_yes'] != "0") {
        $id_yes = " - <strong style='color:#f1592a'>$d[id_yes]</strong>";
    } else {
        $id_yes = "";
    }
    if ($d['so_yes'] != "0") {
        $id_yes .= "<strong style='color:#f1592a'> / $d[so_yes]</strong>";
    } else {
        $id_yes .= "";
    }
    $oid = $d['oid'];
    $tanggal_Update = $d['tanggal'];
    $Jam_Update = $d['Jam'];
    $sisi = $d['sisi_digital'];
    $color = $d['color'];
    $qty_cetak = $d['qty_cetak'];
    $error = $d['error'];
    $qty_etc = $d['qty_etc'];
    $jam = $d['jam'];
    $hitungan_click = $d['hitungan_click'];
    $id_bahan = $d['id_bahan'];
    $nama_barang = $d['nama_barang'];
    $kesalahan = $d['kesalahan'];
    $nama_salah = $d['nama_salah'];
    $alasan_kesalahan = $d['alasan_kesalahan'];
    $status = $d['status'];
    $mesin = $d['mesin'];
else :
    $oid = "";
    $tanggal_Update = $date;
    $Jam_Update = "";
    $client_yes = "";
    $id_yes = "";
    $sisi = "1";
    $color = "FC";
    $qty_cetak = "0";
    $error = "0";
    $qty_etc = "0";
    $jam = "0";
    $hitungan_click = "2";
    $id_bahan = "";
    $nama_barang = "";
    $kesalahan = "";
    $nama_salah = "";
    $alasan_kesalahan = "";
    $status = "";
    $mesin = "";
endif;

echo "
<h3 class='title_form'>$_POST[judul_form] $ID_Order</h3>";
?>
<input type='hidden' id='qty_cetak_OLD' value='<?= $qty_cetak ?>'>
<input type='hidden' id='error_OLD' value='<?= $error ?>'>
<input type='hidden' id='qty_etc_OLD' value='<?= $qty_etc ?>'>
<input type='hidden' id='jam_OLD' value='<?= $jam ?>'>
<input type='hidden' id='sisi_OLD' value='<?= $sisi ?>'>
<input type='hidden' id='color_OLD' value='<?= $color ?>'>
<input type='hidden' id='tanggal_Update_OLD' value='<?= $tanggal_Update ?>'>
<input type='hidden' id='hitungan_click_OLD' value='<?= $hitungan_click ?>'>
<input type='hidden' id='nama_barang_OLD' value='<?= $nama_barang ?>'>
<input type='hidden' id='oid' value='<?= $oid ?>'>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <input type='hidden' id='id_order' value="<?= $ID_Order ?>">
            <tr>
                <td style='width:145px'>Operator</td>
                <td><?= $_SESSION["username"] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>Nama Setter</td>
                <td><?= $d['nama'] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>ID</td>
                <td><?= $oid . $id_yes ?> <?php echo "<a href='WO_print.php?type=print_oid&oid=$oid' target='_blank' class='pointer' style='padding-left:10px'><i class='fad fa-print'></i></a>"; ?></td>
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
                    if ($_SESSION['level'] == "admin") :
                        if ($ID_Order != "0") :
                            echo "<input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='$tanggal_Update' max='$date' style='width:96%'>";
                        else :
                            echo "<input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='width:96%'>";
                        endif;
                    else :
                        echo "<input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='display:none'> " . date("d F Y", strtotime($date)) . "";
                    endif;
                    ?>
                    <input type='hidden' id="jam" class='form md' value="<?= $Jam_Update ?>">
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
            <tr>
                <td style='width:145px'>Mesin</td>
                <td>
                    <select class="myselect" id="mesin">
                        <?php
                        $array_kode = array(
                            "Konika_C-1085" => "Konika C-1085",
                            "Konika_C7000" => "Konika C-7000"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            if ($kode == $mesin) : $pilih = "selected";
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
</div>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Kertas</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value='<?= $nama_barang ?>'>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='<?= $id_bahan ?>' class="form sd" readonly disabled>
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
                            if ($kode == $sisi) : $pilih = "selected";
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
                    <input id="Qty" type='number' class='form md' value="<?= $qty_cetak ?>">
                    <div class="contact100-form-checkbox" style='float:right; margin-top:4px; margin-left:10px'>
                        <input class="input-checkbox100" id="jumlah_click" type="checkbox" name="remember" <?php if ($hitungan_click == "1") {
                                                                                                                echo "checked";
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            }; ?>>
                        <label class="label-checkbox100" for="jumlah_click"> 1 Click <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect'><i class='fas fa-info-square'></i> $d[qty]</strong>"; ?> </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Alat Tambahan</td>
                <td><input id="Qty_AlatTambahan" type='number' class='form md' value="<?= $qty_etc ?>"> <input id="id_tambahan" type='hidden' class='form md' value="<?= $d['ID_AlatTambahan'] ?>"></td>
            </tr>
            <tr>
                <td style='width:145px'>Jammed</td>
                <td><input id="Jammed" type='number' class='form md' value="<?= $jam ?>"></td>
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
                            if ($kode == $color) : $pilih = "selected";
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
                <td><input id="Error" type='number' class='form md' value="<?= $error ?>"></td>
            </tr>
            <tr>
                <td style='width:145px'>Kesalahan</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="Kesalahan" autocomplete="off" onkeyup="Kesalahan_Search('Kesalahan')" onChange="validasi('Kesalahan')" value='<?= $nama_salah ?>'>
                    <input type="hidden" name="nama_kesalahan" id="id_Kesalahan" value='<?= $kesalahan ?>' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_kesalahan" id="validasi_Kesalahan" class="form sd" readonly disabled>
                    <span id="Alert_ValKesalahan"></span>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Alasan Error</td>
                <td><textarea id='alasan_error' class='form ld' style="height:50px; min-height:50px;"><?= $alasan_kesalahan ?></textarea></td>
            </tr>
            <tr>
                <td style='width:145px'>Status</td>
                <td>
                    <select class="myselect" id="status_Cetak">
                        <?php
                        $array_kode = array(
                            "selesai" => "Finished",
                            "proff" => "Proffing"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            if ($kode == $status) : $pilih = "selected";
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
</div>
<div id="submit_menu">
    <button onclick="submit('update_dp')" id="submitBtn">Update Data</button>
</div>
<div id="Result">

</div>

<div class="container-fluid">
    <?php echo "<h3 class='title_form'>Histori Cetak</h3>"; ?>

    <table class='form_table'>
        <tr>
            <th width='1%'>No.</th>
            <th width='12%'>Tanggal / Waktu</th>
            <th width='30%'>kertas</th>
            <th width='6%'>Sisi</th>
            <th width='3%'>W</th>
            <th width='12%'>Qty ETC</th>
            <th width='12%'>jammed</th>
            <th width='12%'>Error</th>
            <th width='12%'>Qty Cetak</th>
        </tr>
        <?php
        $sql =
            "SELECT 
                digital_printing.tgl_cetak,
                (CASE
                    WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.qty_cetak * 1)
                    WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.qty_cetak / 2)
                    ELSE ROUND(digital_printing.qty_cetak / 2)
                END) as qty_cetak,
                (CASE
                    WHEN digital_printing.hitungan_click = '1' THEN ROUND(digital_printing.error * 1)
                    WHEN digital_printing.hitungan_click = '2' THEN ROUND(digital_printing.error / 2)
                    ELSE ROUND(digital_printing.error / 2)
                END) as error,
                digital_printing.qty_etc,
                digital_printing.color,
                digital_printing.jam,
                digital_printing.sisi,
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
                digital_printing.oid = '$oid'
        ";

        // Perform query
        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $n = 0;
            while ($d = $result->fetch_assoc()) :
                $n++;
                echo "
                    <tr>
                        <td>$n</td>
                        <td>$d[tgl_cetak]</td>
                        <td>$d[nama_barang]</td>
                        <td>$d[sisi] Sisi</td>
                        <td>$d[color]</td>
                        <td class='a-center'>$d[qty_etc] Pcs</td>
                        <td class='a-center'>$d[jam] Lembar</td>
                        <td class='a-center'>$d[error] Lembar</td>
                        <td class='a-center'>$d[qty_cetak] Lembar</td>
                    </tr>
                ";

                $total_etc[]   = $d['qty_etc'];
                $total_jam[]   = $d['jam'];
                $total_error[]   = $d['error'];
                $total_qty_cetak[]   = $d['qty_cetak'];
                $Nilai_total_etc = number_format(array_sum($total_etc));
                $Nilai_total_jam = number_format(array_sum($total_jam));
                $Nilai_total_error = number_format(array_sum($total_error));
                $Nilai_total_qty_cetak = number_format(array_sum($total_qty_cetak));
            endwhile;

            echo "
                <tr>
                    <th colspan='5'> Total </th>
                    <th class='a-right'>$Nilai_total_etc Pcs</th>
                    <th class='a-right'>$Nilai_total_jam Lembar</th>
                    <th class='a-right'>$Nilai_total_error Lembar</th>
                    <th class='a-right'>$Nilai_total_qty_cetak Lembar</th>
                </tr>
            ";
        } else {
            echo "
                <tr>
                    <td colspan='9'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                </tr>
            ";
        }
        ?>
    </table>
</div>