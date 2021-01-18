<?php
session_start();
require_once "../../function.php";

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
        (CASE
            WHEN penjualan.bahan_sendiri != '' THEN CONCAT(' ( ', penjualan.bahan_sendiri, ' )')
            ELSE ''
        END) as bahan_sendiri,
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
        setter.nama,
        ((CASE
            WHEN penjualan.satuan LIKE '%Kotak%' THEN penjualan.qty*4
            WHEN penjualan.satuan LIKE '%pcs%' THEN penjualan.qty
            WHEN penjualan.satuan LIKE '%lembar%' THEN penjualan.qty
            ELSE 99999999999
            END) - IFNULL(sisa_qty.qty_cetak,0) ) as qty_cetak
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
        (
            select 
                digital_printing.oid, 
                sum((CASE
                    WHEN digital_printing.hitungan_click != '0' THEN ROUND(digital_printing.qty_cetak / digital_printing.hitungan_click)
                    ELSE ROUND(digital_printing.qty_cetak / 2)
                END)) as qty_cetak
            from 
                digital_printing
            WHERE
            	digital_printing.oid = '$ID_Order'
        ) sisa_qty
    ON
        penjualan.oid = sisa_qty.oid  
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

    if ($d['status'] == 'selesai') {
        $display_form = "none";
    } else {
        $display_form = "";
    }

    if ($d['satuan'] == "Kotak" || $d['satuan'] == "KOTAK" || $d['satuan'] == "kotak") {
        $Qty_Val = (int)$d['Qty_Order'] * 4;
    } else {
        $Qty_Val = (int)$d['Qty_Order'];
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
else :
    $client_yes = "";
    $id_yes = "";
endif;

echo "
    <h3 class='title_form'>$_POST[judul_form]</h3>";
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
                <td style='width:145px'>Nama Setter</td>
                <td><?= $d['nama'] ?></td>
            </tr>
            <tr>
                <td style='width:145px'>ID</td>
                <td><?= $ID_Order . $id_yes ?> <?php echo "<a href='WO_print.php?type=print_oid&oid=$ID_Order' target='_blank' class='pointer' style='padding-left:10px'><i class='fad fa-print'></i></a>"; ?></td>
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
                        echo "<input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='width:96%'>";
                    else :
                        echo "<input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='$date' max='$date' style='display:none'> " . date("d M Y", strtotime($date)) . "";
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

<div class='row' style='display:<?= $display_form ?>'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Kertas</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value=''>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_BahanDigital" class="form sd" readonly disabled>
                    <span id="Alert_ValBahanDigital"></span>
                    <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect'><i class='fas fa-info-square'></i> $d[bahan] $d[bahan_sendiri]</strong>"; ?>
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
                    <input id="Qty" type='number' class='form sd' value="">
                    <input id="Val_Qty" type='hidden' class='form sd' value="<?= $d['qty_cetak']; ?>">
                    <?php echo "<strong style='padding-left:10px; color:#ff7200;' class='noselect pointer' onclick='copy_sisa($d[qty_cetak])'><i class='fas fa-info-square'></i> " . number_format($d['Qty_Order']) . " $d[satuan] <span style='color:red'>( - " . number_format($d['qty_cetak']) . " Lembar )</span></strong>"; ?>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Click</td>
                <td>
                    <label class="sisi_radio"> 1 ( <= 35.5 Cm ) <input type="radio" name="radio" id="satu_click" value="1">
                            <span class="checkmark"></span>
                    </label>
                    <label class="sisi_radio"> 2 ( <= 48,77 Cm) <input type="radio" name="radio" id="dua_click" value="2" checked>
                            <span class="checkmark"></span>
                    </label>
                    <label class="sisi_radio"> 4 ( >= 70 Cm)
                        <input type="radio" name="radio" id="empat_click" value="4">
                        <span class="checkmark"></span>
                    </label>
                    <label class="sisi_radio"> 6 ( >= 90 Cm )
                        <input type="radio" name="radio" id="enam_click" value="6">
                        <span class="checkmark"></span>
                    </label>
                    <div class='clear'></div>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Jammed</td>
                <td><input id="Jammed" type='number' class='form md' value=""></td>
            </tr>
            <tr>
                <td style='width:145px'>Alat Tambahan</td>
                <td><input id="Qty_AlatTambahan" type='number' class='form md' value=""> <input id="id_tambahan" type='hidden' class='form md' value="<?= $d['ID_AlatTambahan'] ?>"></td>
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
                <td>
                    <input type="text" class="form md" style="width:205px" id="Kesalahan" autocomplete="off" onkeyup="Kesalahan_Search('Kesalahan')" onChange="validasi('Kesalahan')" value=''>
                    <input type="hidden" name="nama_kesalahan" id="id_Kesalahan" value='' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_kesalahan" id="validasi_Kesalahan" class="form sd" readonly disabled>
                    <span id="Alert_ValKesalahan"></span>
                </td>
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
                            "selesai" => "Finished",
                            "proff" => "Proffing"
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
<div id="submit_menu" style='display:<?= $display_form ?>'>
    <button onclick="submit('submit_dp')" id="submitBtn">Submit Data</button>
</div>
<div id="Result" style='display:<?= $display_form ?>'>

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
                    WHEN digital_printing.hitungan_click != '0' THEN ROUND(digital_printing.qty_cetak / digital_printing.hitungan_click)
                    ELSE ROUND(digital_printing.qty_cetak / 2)
                END) as qty_cetak,
                (CASE
                    WHEN digital_printing.hitungan_click != '0' THEN ROUND(digital_printing.error / digital_printing.hitungan_click)
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
                digital_printing.oid = '$ID_Order'
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