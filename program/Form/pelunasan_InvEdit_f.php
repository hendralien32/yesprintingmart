<?php
    session_start();
    require_once "../../function.php";

    $PID_Inv = explode("*" , "$_POST[ID_Order]");

    $ID_Order = $PID_Inv[0];
    $Inv_Order = $PID_Inv[1];

    $sql =
    "SELECT
        LEFT (pelunasan.pay_date, 10 ) as Tanggal,
        pelunasan.tot_pay,
        pelunasan.adj_pay,
        pelunasan.jenis_kartu,
        pelunasan.nomor_kartu,
        pelunasan.rekening_tujuan,
        Pembayaran.total_bayar,
        total_tagihan.Jumlah_tagihan,
        round(( total_tagihan.Jumlah_tagihan - Pembayaran.total_bayar )) as Sisa_bayar
    FROM
        pelunasan
    LEFT JOIN
        (select
            pelunasan.no_invoice,
            sum(pelunasan.tot_pay) total_bayar
        FROM
            pelunasan
        WHERE
            pelunasan.no_invoice = '$Inv_Order' and
            pelunasan.pid != '$ID_Order'
        ) Pembayaran
    ON
        pelunasan.no_invoice = Pembayaran.no_invoice
    LEFT JOIN
        (SELECT
            penjualan.no_invoice,
            sum(
                penjualan.qty*(
                    penjualan.margin*((
                        penjualan.b_digital + 
                        penjualan.b_large + 
                        penjualan.b_kotak + 
                        penjualan.b_laminate + 
                        penjualan.b_indoor + 
                        penjualan.b_potong + 
                        penjualan.b_design + 
                        penjualan.b_lain +
                        penjualan.b_offset +
                        penjualan.b_xbanner +
                        penjualan.b_delivery
                        )/100) - penjualan.discount
                    ) 
            ) + sum(
                    penjualan.qty*(
                        (penjualan.b_digital +
                        penjualan.b_xbanner +
                        penjualan.b_lain +
                        penjualan.b_offset +
                        penjualan.b_large +
                        penjualan.b_kotak +
                        penjualan.b_laminate +
                        penjualan.b_potong +
                        penjualan.b_design +
                        penjualan.b_indoor +
                        penjualan.b_delivery
                        ) - penjualan.discount
                    ) 
            ) AS Jumlah_tagihan
        FROM
            penjualan
        WHERE
            penjualan.no_invoice =  '$Inv_Order'
        ) total_tagihan
    ON
        pelunasan.no_invoice = total_tagihan.no_invoice
    WHERE
        pelunasan.pid = '$ID_Order'
    ";

    $result = $conn_OOP -> query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    endif;

?>

    <div class="row">
        <div class="col-5">
            <table class='table-pelunasan'>
                <tr>
                    <td>Tanggal Bayar</td>
                    <td><input type="date" id="sub_tanggal_bayar" data-placeholder="Tanggal" class='form md' value="<?= $row['Tanggal']; ?>" style='width:96%'></td>
                </tr>
                <tr>
                    <td>Jumlah Bayar</td>
                    <td><input type="number" id="sub_jumlah_bayar" class='form md' value="<?= $row['tot_pay']; ?>" autocomplete="off"></td>
                </tr>
                <tr>
                    <td>Adjust / Disc</td>
                    <td><input type="number" id="sub_adjust" class='form md' value="<?= $row['adj_pay']; ?>" autocomplete="off"></td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <table class='table-pelunasan'>
                <tr>
                    <td>Nomor ATM</td>
                    <td><input type="text" id="sub_nomor_atm" class='form ld' style='width:96%' autocomplete="off" value="<?= $row['nomor_kartu']; ?>"></td>
                </tr>
                <tr>
                    <td>Nama Bank</td>
                    <td>
                        <select class="myselect" id="sub_bank">
                            <option value="">Daftar Bank</option>
                            <?php
                                $array_kode = array(
                                    "ANZ" => "ANZ",
                                    "Bank Aceh" => "Bank Aceh",
                                    "BCA" => "BCA",
                                    "BII" => "BII",
                                    "BNI" => "BNI",
                                    "BRI" => "BRI",
                                    "BTN" => "BTN",
                                    "Bukopin" => "Bukopin",
                                    "Danamon" => "Danamon",
                                    "DBS" => "DBS",
                                    "Mayapada" => "Mayapada",
                                    "Mega" => "Mega",
                                    "Mandiri" => "Mandiri",
                                    "Mestika" => "Mestika",
                                    "OCBC" => "OCBC",
                                    "Permata" => "Permata",
                                    "QNB" => "QNB",
                                    "UOB" => "UOB"
                                );
                                foreach($array_kode as $kode => $kd) :
                                    if(( $kode == $row['jenis_kartu'] )) { $pilih = "selected"; } else { $pilih = ""; }
                                    echo "<option value='$kode' $pilih>$kd</option>";
                                endforeach;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tujuan Penerima</td>
                    <td>
                        <select class="myselect" id="sub_rekening_tujuan">
                            <option value=''>Daftar Bank</option>
                            <?php
                                $array_kode = array(
                                    "BCA" => "BCA",
                                    "Mandiri" => "Mandiri"
                                );
                                foreach($array_kode as $kode => $kd) :
                                    if(( $kode == $row['rekening_tujuan'] )) { $pilih = "selected"; } else { $pilih = ""; }
                                    echo "<option value='$kode' $pilih>$kd</option>";
                                endforeach;
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-3">
            <table class='table-detail'>
                <tr>
                    <td>Jlh Tagihan</td>
                    <td><?= number_format($row['Jumlah_tagihan']); ?></td>
                </tr>
                <tr> 
                    <td>Total Bayar</td>
                    <td><?= number_format($row['total_bayar']); ?></td>
                </tr>
                <tr class='pointer' onclick="CopySub_SisaByr('<?= $row['Sisa_bayar'] ?>')"> 
                    <td>Sisa Bayar</td>
                    <td>
                        <?= number_format($row['Sisa_bayar']); ?>
                        <input type="hidden" id="sub_sisa_bayar" value="<?= $row['Sisa_bayar'] ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div id="submit_menu">
            <hr>
            <button onclick="Update('edit_Payment','<?= $_POST['ID_Order'] ?>')" id="submitBtn">Update Pembayaran</button>
        </div>
        <div id="Result">
            
        </div>
    </div>