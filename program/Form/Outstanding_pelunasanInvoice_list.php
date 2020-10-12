<?php
    session_start();
    require_once "../../function.php";

?>
    <div class="container-fluid">
        <table class='form_table'>
            <?php
                $sql = 
                "SELECT
                    list_pelunasan.nama_client,
                    list_pelunasan.tanggal,
                    list_pelunasan.No_Invoice,
                    list_pelunasan.id_OID,
                    list_pelunasan.description,
                    list_pelunasan.kode_barang,
                    list_pelunasan.Total_keseluruhan,
                    list_pelunasan.Total_Bayar
                FROM
                    (
                        SELECT
                            penjualan.id_OID,
                            penjualan.description,
                            penjualan.kode_barang,
                            penjualan.nama_client,
                            penjualan.tanggal,
                            penjualan.No_invoice,
                            penjualan.Total_keseluruhan,
                            penjualan.total_bayar
                        FROM
                            (
                                SELECT
                                    penjualan.no_invoice,
                                    GROUP_CONCAT(penjualan.oid SEPARATOR '*_*') as id_OID,
                                    GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
                                    GROUP_CONCAT(REPLACE( penjualan.kode, ' ', '_' ) SEPARATOR ',') as kode_barang,
                                    penjualan.client,
                                    customer.nama_client,
                                    LEFT( penjualan.invoice_date, 10 ) as tanggal,
                                    sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                                    COALESCE(pelunasan.total_bayar,0) as total_bayar
                                FROM
                                    penjualan
                            LEFT JOIN 
                                (
                                    select 
                                        pelunasan.no_invoice, 
                                        sum(pelunasan.tot_pay) as total_bayar
                                    from 
                                        pelunasan 
                                    group by 
                                        pelunasan.no_invoice
                                ) pelunasan
                            ON
                                penjualan.no_invoice = pelunasan.no_invoice
                            LEFT JOIN 
                                (select customer.cid, customer.nama_client from customer) customer
                            ON
                                penjualan.client = customer.cid    
                            WHERE
                                penjualan.no_invoice != '' and
                                penjualan.client ='$_POST[client]' and
                                penjualan.cancel!='Y' and
                                penjualan.pembayaran != 'lunas'
                            GROUP BY
                                penjualan.no_invoice
                            ) penjualan
                            WHERE 
                                penjualan.Total_keseluruhan != penjualan.total_bayar
                    ) list_pelunasan
                ORDER BY
                    list_pelunasan.No_Invoice
                ASC
                ";

                // Perform query
                $result = $conn_OOP -> query($sql);

                if ($result->num_rows > 0) :
                    echo "
                    <tr>
                        <th width='2%'>#</th>
                        <th width='10%'>Tanggal</th>
                        <th width='8%'>No Invoice</th>
                        <th width='43%'>Deskription</th>
                        <th width='9%'>Total Invoice</th>
                        <th width='9%'>Total Bayar</th>
                        <th width='9%'>Sisa Bayar</th>
                        <th width='10%' onclick='copy_all()' class='pointer'>Jumlah Bayar <i class='fas fa-ballot-check'></i></th>
                    </tr>
                    ";

                    $no = 0;
                    // output data of each row
                    while($d = $result->fetch_assoc()) :
                        $no++;

                        $count              = count(explode("*_*" , "$d[id_OID]"));
                        $oid                = explode("*_*" , "$d[id_OID]");
                        $kode_barang        = explode("," , $d['kode_barang']);
                        $description        = explode("*_*" , "$d[description]");
                        $sisa_bayar         = $d['Total_keseluruhan'] - $d['total_bayar'];

                        echo "
                        <tr>
                            <td rowspan='$count'>$no</td>
                            <td rowspan='$count'>". date("d M Y",strtotime($d['tanggal'] ))."</td>
                            <td rowspan='$count'>#$d[No_invoice]</td>
                            <td><b class='tanda_$kode_barang[0]'>▐</b> $oid[0] - $description[0]</td>
                            <td rowspan='$count' class='t-right'>". number_format($d['Total_keseluruhan']) ."</td>
                            <td rowspan='$count' class='t-right'>". number_format($d['total_bayar']) ."</td>
                            <td rowspan='$count' name='invoice' class='pointer t-right' onclick='copy_sisa($sisa_bayar,$no)'>
                                ". number_format($sisa_bayar) ."
                                <input type='hidden' id='test_copy_$no' value='$sisa_bayar'>
                                <input type='hidden' id='ID_$no' value='$d[No_invoice]'>
                            </td>
                            <td rowspan='$count'><input type='number' class='form' id='FormByr_$no' style='width:100%'></td>
                        </tr>
                        ";

                        for($i=1;$i<$count;$i++) :
                            echo "
                            <tr>
                                <td><b class='tanda_$kode_barang[$i]'>▐</b> $oid[$i] - $description[$i]</td>
                            </tr>
                            ";
                        endfor;

                        $total_penjualan[]   = $d['Total_keseluruhan'];
                        $total_pembayaran[]   = $d['total_bayar'];
                        $Nilai_Total = array_sum($total_penjualan);
                        $Nilai_Total_bayar = array_sum($total_pembayaran);
                    endwhile;
                else :
                    $Nilai_Total = "0";
                    $Nilai_Total_bayar = "0";
                endif;

                $detail_sisa = $Nilai_Total - $Nilai_Total_bayar;
            ?>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class="col-4">
            <table class='table-pelunasan'>
                <tr>
                    <td>Tanggal Bayar</td>
                    <td><input type="date" id="tanggal_bayar" data-placeholder="Tanggal" class='form md' value="<?= $date; ?>" disabled style='width:96%'></td>
                </tr>
                <tr>
                    <td>Client</td>
                    <td>
                        <SELECT style='width:100%' id='form_client' onchange="change_client()">
                            <option value="">Nama Client</option>
                            <?php
                                $sql = 
                                "SELECT
                                    test.ID_Client,
                                    test.nama_client,
                                    test.No_Invoice
                                FROM
                                    (
                                        SELECT
                                            list_pelunasan.client as ID_Client,
                                            list_pelunasan.nama_client,
                                            GROUP_CONCAT(list_pelunasan.No_invoice SEPARATOR '*_*') as No_Invoice,
                                            sum(COALESCE(list_pelunasan.Total_keseluruhan,0)) as Total_keseluruhan,
                                            sum(COALESCE(list_pelunasan.total_bayar,0)) as Total_Bayar
                                        FROM
                                            (
                                                SELECT                                                 
                                                    penjualan.client,
                                                    penjualan.nama_client,
                                                    penjualan.No_invoice,
                                                    penjualan.Total_keseluruhan,
                                                    penjualan.total_bayar
                                                FROM
                                                    (
                                                        SELECT  
                                                            penjualan.no_invoice,                                                      
                                                            penjualan.client,
                                                            customer.nama_client,
                                                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                                                            COALESCE(pelunasan.total_bayar,0) as total_bayar
                                                        FROM
                                                            penjualan
                                                        LEFT JOIN 
                                                            (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar, LEFT(pelunasan.pay_date,10) as pay_date from pelunasan group by pelunasan.no_invoice) pelunasan
                                                        ON
                                                            penjualan.no_invoice = pelunasan.no_invoice
                                                        LEFT JOIN 
                                                            (select customer.cid, customer.nama_client from customer) customer
                                                        ON
                                                            penjualan.client = customer.cid    
                                                        WHERE
                                                            penjualan.no_invoice != '' and
                                                            penjualan.client !='1' and
                                                            penjualan.cancel!='Y' and
                                                            penjualan.pembayaran != 'lunas'
                                                        GROUP BY
                                                            penjualan.no_invoice
                                                    ) penjualan
                                                WHERE 
                                                    penjualan.Total_keseluruhan != penjualan.total_bayar
                                            ) list_pelunasan
                                            GROUP BY
                                                list_pelunasan.nama_client
                                        ) test
                                ORDER BY
                                    test.nama_client
                                ASC
                                ";

                                $result = $conn_OOP -> query($sql);

                                if ($result->num_rows > 0) :
                                    while($d = $result->fetch_assoc()) :
                                        $Count_No_Invoice           = count(explode("*_*" , "$d[No_Invoice]"));
                                        if($_POST['client'] == $d['ID_Client']) { $pilih = "selected"; } else { $pilih ="";}
                                        echo "<option value='$d[ID_Client]' $pilih>$d[nama_client] [$Count_No_Invoice]</option>";

                                    endwhile;
                                endif;
                            ?>
                            
                        </SELECT>
                    </td>
                </tr>
                <tr>
            </table>
        </div>
        <div class="col-4">
            <table class='table-pelunasan'>
                <tr>
                    <td>Nomor ATM</td>
                    <td><input type="text" id="nomor_atm" autocomplete="off" class='form ld' style='width:96%'></td>
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
                    <td>
                        <?= number_format($Nilai_Total) ?>
                    </td>
                </tr>
                <tr> 
                    <td>Total Bayar</td>
                    <td>
                        <?= number_format($Nilai_Total_bayar) ?>
                    </td>
                </tr>
                <tr> 
                    <td>Sisa Bayar</td>
                    <td onclick='copy_all()' class='pointer'>
                        <?= number_format($detail_sisa) ?> <i class="fas fa-copy" style='margin-left:10px'></i>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    

    <div id="submit_menu">
        <hr>
        <button onclick="multipayment('multipayment')" id="submitBtn">Multi Payment Invoice</button>
    </div>
    <div id="Result">
            
    </div>