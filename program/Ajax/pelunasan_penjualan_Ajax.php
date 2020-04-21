<?php
    session_start();

    require_once '../../function.php';
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
    <table>
        <tbody>
            <tr>
                <th width="1%">#</th>
                <th width="10%">Client</th>
                <th width="8%">Date</th>
                <th width="8%">No Invoice</th>
                <th width="36%">No ID</th>
                <th width="8%">Total Invoice</th>
                <th width="8%">Total Pay</th>
                <th width="5%">Payment</th>
            </tr>

            <?php
                $sql = 
                "SELECT
                    test.nama_client,
                    test.tanggal,
                    test.No_Invoice,
                    test.id_OID_KOMA,
                    test.id_OID,
                    test.description,
                    test.kode_barang,
                    test.Total,
                    test.Total_pembayaran,
                    test.Total_keseluruhan,
                    test.Total_Bayar
                FROM
                    (
                        SELECT
                            list_pelunasan.nama_client,
                            GROUP_CONCAT(list_pelunasan.tanggal SEPARATOR '*_*') as tanggal,
                            GROUP_CONCAT(list_pelunasan.No_invoice SEPARATOR '*_*') as No_Invoice,
                            GROUP_CONCAT(list_pelunasan.id_OID) as id_OID_KOMA,
                            GROUP_CONCAT(list_pelunasan.id_OID SEPARATOR '*_*') as id_OID,
                            GROUP_CONCAT(list_pelunasan.description SEPARATOR '*_*') as description,
                            GROUP_CONCAT(list_pelunasan.kode_barang SEPARATOR '*_*') as kode_barang,
                            GROUP_CONCAT(list_pelunasan.Total_keseluruhan SEPARATOR '*_*') as Total,
                            GROUP_CONCAT(COALESCE(list_pelunasan.total_bayar,0) SEPARATOR '*_*') as Total_pembayaran,
                            sum(COALESCE(list_pelunasan.Total_keseluruhan,0)) as Total_keseluruhan,
                            sum(COALESCE(list_pelunasan.total_bayar,0)) as Total_Bayar
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
                                            GROUP_CONCAT(penjualan.oid SEPARATOR ',') as id_OID,
                                            GROUP_CONCAT(penjualan.description SEPARATOR ',') as description,
                                            GROUP_CONCAT(REPLACE( penjualan.kode, ' ', '_' ) SEPARATOR ',') as kode_barang,
                                            penjualan.client,
                                            customer.nama_client,
                                            LEFT( penjualan.invoice_date, 10 ) as tanggal,
                                            penjualan.no_invoice,
                                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                                            COALESCE(pelunasan.total_bayar,0) as total_bayar
                                        FROM
                                            penjualan
                                        LEFT JOIN 
                                            (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar from pelunasan group by pelunasan.no_invoice) pelunasan
                                        ON
                                            penjualan.no_invoice = pelunasan.no_invoice
                                        LEFT JOIN 
                                            (select customer.cid, customer.nama_client from customer) customer
                                        ON
                                            penjualan.client = customer.cid    
                                        WHERE
                                            penjualan.no_invoice != '' and
                                            penjualan.client !='1' and
                                            -- LEFT( penjualan.invoice_date, 10 ) = '2020-04-18' and
                                            -- penjualan.client ='1102' and
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

                // Perform query
                $result = $conn_OOP -> query($sql);

                if ($result->num_rows > 0) :
                    $no = 0;
                    // output data of each row
                    while($d = $result->fetch_assoc()) :
                        $no++;
                        $tanggal_Inv                = explode("*_*" , "$d[tanggal]");
                        $Count_No_Invoice           = count(explode("*_*" , "$d[No_Invoice]"));
                        $Count_ID_Koma              = count(explode("," , "$d[id_OID_KOMA]"));
                        $Explode_ID                 = explode("*_*" , "$d[id_OID]");
                        $Count_ID                   = count($Explode_ID);

                        $Explode_Invoice            = explode("*_*" , "$d[No_Invoice]");
                        $Explode_Total              = explode("*_*" , "$d[Total]");
                        $Explode_Total_pembayaran   = explode("*_*" , "$d[Total_pembayaran]");
                        $Explode_description        = explode("*_*" , "$d[description]");
                        $Explode_kode_barang        = explode("*_*" , "$d[kode_barang]");

                        $ArraySum_Total             = array_sum($Explode_Total);
                        $ArraySum_Total_Pembayaran  = array_sum($Explode_Total_pembayaran);

                        // echo " [ 1. $d[No_Invoice]<br>2. $d[id_OID]<br>3. $d[id_OID_KOMA] <br>4. $d[Total_pembayaran] ]<br> ";

                        $Count_JlhID                = count(explode("," , $Explode_ID[0]));
                        $OID                        = explode("," , $Explode_ID[0]);
                        $description                = explode("," , $Explode_description[0]);
                        $kode_barang                = explode("," , $Explode_kode_barang[0]);
                        
                        echo "
                            <tr>
                                <td rowspan='$Count_ID_Koma' style='vertical-align: top; padding-top: 13px'>$no</td>
                                <td rowspan='$Count_ID_Koma' style='vertical-align: top; padding-top: 13px'>$d[nama_client]</td>
                                <td rowspan='$Count_JlhID' style='vertical-align: top; padding-top: 13px'>$tanggal_Inv[0]</td>
                                <td rowspan='$Count_JlhID' style='vertical-align: top; padding-top: 13px'>#$Explode_Invoice[0]</td>
                                <td><b class='tanda_$kode_barang[0]'>▐</b> $OID[0] - $description[0]</td>
                                <td rowspan='$Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:right'>". number_format($Explode_Total[0]) ."</td>
                                <td rowspan='$Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:right'>". number_format($Explode_Total_pembayaran[0]) ."</td>
                                <td rowspan='$Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:center'>
                                    <span class='icon_status pointer'><i class='fad fa-cash-register'></i></span>
                                    <span class='icon_status pointer'><i class='fad fa-credit-card'></i></span>
                                </td>
                            </tr>
                        ";

                        if( $Count_JlhID > 1 ) {
                            for($i=1;$i<$Count_JlhID ;$i++) :
                            echo "
                                <tr>
                                    <td><b class='tanda_$kode_barang[$i]'>▐</b> $OID[$i] - $description[$i]</td>
                                </tr>
                            ";
                            endfor;
                        }

                        for($i=1; $i<$Count_No_Invoice ;$i++) :
                            $X_Count_JlhID = count(explode("," , $Explode_ID[$i]));
                            $X_OID = explode("," , $Explode_ID[$i]);
                            $X_description = explode("," , $Explode_description[$i]);
                            $X_kode_barang = explode("," , $Explode_kode_barang[$i]);
                            echo "
                                <tr>
                                    <td rowspan='$X_Count_JlhID' style='vertical-align: top; padding-top: 13px'>$tanggal_Inv[$i]</td>
                                    <td rowspan='$X_Count_JlhID' style='vertical-align: top; padding-top: 13px'>#$Explode_Invoice[$i]</td>
                                    <td><b class='tanda_$X_kode_barang[0]'>▐</b> $X_OID[0] - $X_description[0]</td>
                                    <td rowspan='$X_Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:right'>". number_format($Explode_Total[$i]) ."</td>
                                    <td rowspan='$X_Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:right'>". number_format($Explode_Total_pembayaran[$i]) ."</td>
                                    <td rowspan='$X_Count_JlhID' style='vertical-align: top; padding-top: 13px; text-align:center'>
                                        <span class='icon_status pointer'><i class='fad fa-cash-register'></i></span>
                                        <span class='icon_status pointer'><i class='fad fa-credit-card'></i></span>
                                    </td>
                                </tr>
                            ";

                            if( $X_Count_JlhID > 1 ) {
                                for($j=1; $j<$X_Count_JlhID ;$j++) :
                                    echo "
                                        <tr>
                                            <td><b class='tanda_$X_kode_barang[$j]'>▐</b> $X_OID[$j] - $X_description[$j]</td>
                                        </tr>
                                    ";
                                endfor;
                            }
                        endfor;
                        $total_penjualan[]   = $ArraySum_Total;
                        $total_pembayaran[]   = $ArraySum_Total_Pembayaran;
                        $Nilai_Total = number_format(array_sum($total_penjualan));
                        $Nilai_Total_bayar = number_format(array_sum($total_pembayaran));

                        echo "
                        <tr id='total_invoice' style='font-weight:bold; background-color:#4389e8;'>
                            <td colspan='5'>Total Invoice $d[nama_client] [$Count_No_Invoice]</td>
                            <td style='text-align:right'>". number_format($ArraySum_Total) ."</td>
                            <td style='text-align:right'>". number_format($ArraySum_Total_Pembayaran) ."</td>
                        </tr>
                        ";
                    endwhile;
                endif;

            ?>
            <tr>
                <th colspan="5">Total Penjualan</th>
                <th style='text-align:right'><?= $Nilai_Total; ?></th>
                <th style='text-align:right'><?= $Nilai_Total_bayar; ?></th>
            </tr>
        </tbody>
    </table>