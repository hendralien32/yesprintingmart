<?php
    session_start();
    require_once "../../function.php";

    $sql=
    "SELECT
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
        ) AS total_tagihan,
        pelunasan.total_bayar,
        pelunasan.tgl_bayar,
        pelunasan.adj_pay
    FROM
        penjualan
    LEFT JOIN 
        (select pelunasan.type_pem, sum(pelunasan.adj_pay) as adj_pay, pelunasan.no_invoice, MAX(LEFT( pelunasan.pay_date, 10 )) as tgl_bayar, sum(pelunasan.tot_pay) as total_bayar from pelunasan group by pelunasan.no_invoice order by pelunasan.pay_date DESC) pelunasan
    ON
        pelunasan.no_invoice = penjualan.no_invoice
    WHERE
        penjualan.no_invoice =  '$_POST[ID_Order]' and
        penjualan.cancel!='Y'
    GROUP BY
        penjualan.no_invoice
    ";

    // Perform query
    $result = $conn_OOP -> query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();

        $sisa_bayar=$row['total_tagihan']-$row['total_bayar'];

    endif;

if( $sisa_bayar != 0 ) :
    echo "<h3 class='title_form'>$_POST[judul_form] #$_POST[ID_Order]</h3>";
?>

    <div class="row">
        <div class="col-4">
            <table class='table-pelunasan'>
                <tr>
                    <td>Tanggal Bayar</td>
                    <td><input type="date" id="tanggal_bayar" data-placeholder="Tanggal" class='form md' value="<?= $date; ?>" disabled style='width:96%'></td>
                </tr>
                <tr>
                    <td>Jumlah Bayar</td>
                    <td><input type="number" id="jumlah_bayar" class='form md'></td>
                </tr>
                <tr>
                    <td>Adjust / Disc</td>
                    <td><input type="number" id="adjust" class='form md'></td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <table class='table-pelunasan'>
                <tr>
                    <td>Nomor ATM</td>
                    <td><input type="text" id="nomor_atm" class='form ld' style='width:96%'></td>
                </tr>
                <tr>
                    <td>Nama Bank</td>
                    <td>
                        <select class="myselect" id="bank">
                            <option value=''>Daftar Bank</option>
                            <option value='ANZ'>ANZ</option>
                            <option value='Bank Aceh'>Bank Aceh</option>
                            <option value='BCA'>BCA</option>
                            <option value='BII'>BII</option>
                            <option value='BNI'>BNI</option>
                            <option value='BRI'>BRI</option>
                            <option value='BTN'>BTN</option>
                            <option value='Bukopin'>Bukopin</option>
                            <option value='Danamon'>Danamon</option>
                            <option value='DBS'>DBS</option>
                            <option value='Mayapada'>Mayapada</option>
                            <option value='Mega'>Mega</option> 
                            <option value='Mandiri'>Mandiri</option>
                            <option value='Mestika'>Mestika</option>
                            <option value='OCBC'>OCBC</option>
                            <option value='Permata'>Permata</option>
                            <option value='QNB'>QNB</option>
                            <option value='UOB'>UOB</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tujuan Penerima</td>
                    <td>
                        <select class="myselect" id="rekening_tujuan">
                            <option value=''>Daftar Bank</option>
                            <option value='BCA'>BCA</option>
                            <option value='Mandiri'>Bank Mandiri</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <table class='table-detail'>
                <tr>
                    <td>Jumlah Tagihan</td>
                    <td><?= number_format($row['total_tagihan']); ?></td>
                </tr>
                <tr> 
                    <td>Total Bayar</td>
                    <td><?= number_format($row['total_bayar']); ?></td>
                </tr>
                <tr class='pointer' onclick="Copy_SisaByr('<?= $sisa_bayar ?>')"> 
                    <td>Sisa Bayar</td>
                    <td>
                        <?= number_format($sisa_bayar); ?> <i class="fas fa-copy" style='margin-left:10px'></i>
                        <input type="hidden" id="sisa_bayar" value="<?= $sisa_bayar ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div id="submit_menu">
            <hr>
            <button onclick="submit('Payment','<?= $_POST['ID_Order'] ?>')" id="submitBtn">Bayar Invoice</button>
        </div>
        <div id="Result">
            
        </div>
    </div>
<?php 

else :

    echo "
        <div class='status_lunas'>
            <a href='print.php?type=sales_invoice&no_invoice=$_POST[ID_Order]' target='_blank' class='pointer'>
                <span style='font-size:16px;'>Invoice #$_POST[ID_Order] <i class='fad fa-print' style='margin-left:10px;'></i></span>
                <span>LUNAS</span>
                <span style='font-size:16px;'>". date("d M Y",strtotime($row['tgl_bayar']))."</span>
            </a>
        </div>
    ";

endif; 

?>

    <div class="container-fluid">
        <table class='form_table'>
            <tbody>
                <tr>
                    <th width="2%">#</th>
                    <th width="10%">Tanggal</th>
                    <th width="50%">Description</th>
                    <th width="10%">Qty</th>
                    <th width="10%">@ harga</th>
                    <th width="8%">Disc</th>
                    <th width="10%">Total Harga</th>
                </tr>
                <tr>
                    <?php
                        $sql = 
                        "SELECT
                            penjualan.oid,
                            penjualan.description,
                            REPLACE( penjualan.kode, ' ', '_' ) as kode_barang,
                            LEFT( penjualan.waktu, 10 ) as tanggal,
                            CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                            (CASE
                                WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                                ELSE ''
                            END) as ukuran,
                            ((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount) as harga_satuan,
                            penjualan.discount as discount,
                            (((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as total
                        FROM
                            penjualan
                        WHERE
                            penjualan.no_invoice = '$_POST[ID_Order]'
                        ";
                        $n = 0;
                        $result = $conn_OOP->query($sql);
                        if ($result->num_rows > 0) :
                            while($d = $result->fetch_assoc()) :
                                $n++;
                                echo "
                                <tr>
                                    <td>$n</td>
                                    <td>". date("d M Y",strtotime($d['tanggal']))."</td>
                                    <td><b class='tanda_$d[kode_barang]'>▐</b> $d[oid] - $d[description] $d[ukuran]</td>
                                    <td>$d[qty]</td>
                                    <td style='text-align:right'>". number_format($d['harga_satuan']) ."</td>
                                    <td style='text-align:right'>". number_format($d['discount']) ."</td>
                                    <td style='text-align:right'>". number_format($d['total']) ."</td>
                                </tr>
                                ";

                                $total_penjualan[]   = $d['total'];
                                $Nilai_Total = number_format(array_sum($total_penjualan));
                            endwhile;
                        else :

                        endif;
                    ?>
                </tr>
                <tr>
                    <th colspan="6" style='text-align:left; padding-left:10px'>Total Penjualan #<?= $_POST['ID_Order'] ?></th>
                    <th style='text-align:right; padding-right:10px'><?= $Nilai_Total; ?></th>
                </tr>
            </tbody>
        </table>

        <?php echo "<br><br><h3 class='title_form'>History Payment</h3>"; ?>

        <table class="form_table">
            <tr>
                <th width="16%">Tanggal</th>
                <th width="20%">Tipe Pembayaran</th>
                <th width="20%">Jumlah Pembayaran</th>
                <th width="22%">Jumlah Adjustment</th>
                <th width="22%">Total Terima</th>
            </tr>
            <?php
                $sql = 
                "SELECT
                    pid, 
                    no_invoice,
                    tot_pay, 
                    adj_pay, 
                    type_pem, 
                    tipe_gesek, 
                    jenis_kartu, 
                    nomor_kartu,
                    rekening_tujuan,
                    pay_date, pay_date as tanggal, 
                    (tot_pay-adj_pay) as total_terima
                from
                    pelunasan
                where
                    no_invoice = '$_POST[ID_Order]'
                order by
                    pay_date desc
                ";
            
                $result = $conn_OOP->query($sql);
                if ($result->num_rows > 0) :
                    while($d = $result->fetch_assoc()) :

                        if($d['type_pem']==='cash' or $d['type_pem']==='Cash') {
                            $type_pem="$d[type_pem]"; 
                        } elseif($d['type_pem']=="DP") {
                            $type_pem="Down Payment";
                        } elseif($d['type_pem']=="Kartu Kredit" or $d['type_pem']=="DP Kartu Kredit") {
                            $type_pem="$d[jenis_kartu] - $d[nomor_kartu] <i class='fas fa-chevron-double-right'></i> $d[rekening_tujuan]"; 
                        } else { 
                            $type_pem="- - -"; 
                        }
                        
                        echo "
                            <tr>
                                <td><center><b>". date("d M Y H:i A",strtotime($d['tanggal'])) ."</b></center></td>
                                <td><center><b>$type_pem</b></center></td>
                                <td style='text-align:right; padding-right:20px'>".number_format($d['tot_pay'])."</td>
                                <td style='text-align:right; padding-right:20px'>".number_format($d['adj_pay'])."</td>
                                <td style='text-align:right; padding-right:20px'>". number_format($d['total_terima'])."</td>
                                <td class='pointer' onclick='LaodSubForm(\"pelunasan_InvEdit\", \"". $d['pid'].'*'.$d['no_invoice'] ."\")'><i class='fas fa-edit'></i></td>
                            </tr>
                        ";
                    endwhile;
                endif;
            ?>
        </table>
    </div>

<?php $conn -> close(); ?>