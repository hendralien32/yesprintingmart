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
                <th width="5%">Tanggal</th>
                <th width="12%">No Invoice</th>
                <th width="36%">No ID</th>
            </tr>

            <?php
                $sql = 
                "SELECT
                    test.nama_client,
                    test.tanggal,
                    test.No_Invoice,
                    test.id_OID,
                    test.description,
                    test.id_OID_KOMA,
                    test.description_KOMA,
                    test.Total,
                    test.Total_keseluruhan,
                    test.Total_Bayar
                FROM
                    (
                        SELECT
                            list_pelunasan.nama_client,
                            GROUP_CONCAT(list_pelunasan.tanggal SEPARATOR '*_*') as tanggal,
                            GROUP_CONCAT(list_pelunasan.No_invoice SEPARATOR '*_*') as No_Invoice,
                            GROUP_CONCAT(list_pelunasan.id_OID_KOMA) as id_OID_KOMA,
                            GROUP_CONCAT(list_pelunasan.description_KOMA) as description_KOMA,
                            GROUP_CONCAT(list_pelunasan.id_OID) as id_OID,
                            GROUP_CONCAT(list_pelunasan.description) as description,
                            GROUP_CONCAT(list_pelunasan.Total_keseluruhan) as Total,
                            sum(COALESCE(list_pelunasan.Total_keseluruhan,0)) as Total_keseluruhan,
                            sum(COALESCE(list_pelunasan.total_bayar,0)) as Total_Bayar
                        FROM
                            (
                                SELECT
                                    penjualan.id_OID,
                                    penjualan.description,
                                    penjualan.id_OID_KOMA,
                                    penjualan.description_KOMA,
                                    penjualan.nama_client,
                                    penjualan.tanggal,
                                    penjualan.No_invoice,
                                    penjualan.Total_keseluruhan,
                                    penjualan.total_bayar
                                FROM
                                    (
                                        SELECT
                                            GROUP_CONCAT(penjualan.oid SEPARATOR '*_*') as id_OID,
                                            GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
                                            GROUP_CONCAT(penjualan.oid SEPARATOR ',') as id_OID_KOMA,
                                            GROUP_CONCAT(penjualan.description SEPARATOR ',') as description_KOMA,
                                            penjualan.client,
                                            customer.nama_client,
                                            LEFT( penjualan.invoice_date, 10 ) as tanggal,
                                            penjualan.no_invoice,
                                            (((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as total,
                                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                                            pelunasan.total_bayar
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
                                            -- penjualan.client ='1102' and
                                            penjualan.pembayaran != 'lunas'
                                        GROUP BY
                                            penjualan.no_invoice
                                    ) penjualan
                            ) list_pelunasan
                            GROUP BY
                                list_pelunasan.nama_client
                        ) test
                        WHERE
                            Total_keseluruhan != Total_Bayar or
                            Total_keseluruhan < Total_Bayar
                        ORDER BY
                            test.nama_client
                        ASC
                        LIMIT 1
                ";

                // Perform query
                $result = $conn_OOP -> query($sql);

                if ($result->num_rows > 0) :
                    $no = 0;
                    // output data of each row
                    while($d = $result->fetch_assoc()) :
                        $no++;
                        $tanggal_Inv = explode("*_*" , "$d[tanggal]");

                        $Count_No_Invoice = count(explode("*_*" , "$d[No_Invoice]"));
                        $Explode_Invoice = explode("*_*" , "$d[No_Invoice]");
                        $Explode_ID = explode("*_*" , "$d[id_OID]");
                        $Count_ID_Koma = count(explode("," , "$d[id_OID_KOMA]"));
                        $Count_ID = count($Explode_ID);

                        for($i=0; $i<$Count_No_Invoice ;$i++) :
                            $Count_JlhID[$i] = count(explode("," , $Explode_ID[$i]));
                            $Explode_ID_i[$i] = explode("," , $Explode_ID[$i]);
                        endfor;

                        echo "
                            <tr>
                                <td rowspan='$Count_ID_Koma'>$Count_JlhID[0] - $no</td>
                                <td rowspan='$Count_ID_Koma'>$d[nama_client]</td>
                                <td rowspan='$Count_JlhID[0]'>$tanggal_Inv[0]</td>
                                <td rowspan='$Count_JlhID[0]'>$Explode_Invoice[0]</td>
                                <td>$Count_JlhID[0] - $Explode_ID[0] - // $d[id_OID]</td>
                            </tr>
                        ";

                        for($i=1; $i<$Count_No_Invoice ;$i++) :
                            echo "
                                <tr>
                                    <td rowspan='$Count_JlhID[$i]'>$tanggal_Inv[$i]</td>
                                    <td rowspan='$Count_JlhID[$i]'>$Explode_Invoice[$i]</td>
                                    <td>$Count_JlhID[$i] - $Explode_ID[$i]</td>
                                </tr>
                            ";

                            if($Count_JlhID[$i]>1) {
                                for($j=1; $j<$Count_JlhID[$i] ; $j++) :
                                    echo "
                                        <tr>
                                            <td>$Explode_ID_i[$j]</td>
                                        </tr>
                                    ";
                                endfor;  
                            }
                        endfor;

                        
                        // for($i=1; $i<$Count_No_Invoice ;$i++) :
                        //     $Explode_ID = explode("," , $Explode_ID[$i]);
                        //     echo "
                        //         <tr>
                        //             <td rowspan='$Count_JlhID[$i]'>$tanggal_Inv[$i]</td>
                        //             <td rowspan='$Count_JlhID[$i]'>$Explode_Invoice[$i]</td>
                        //             <td>$Explode_ID[$i]</td>
                        //         </tr>
                        //     ";
                        //     for($j=1; $j<$Count_JlhID[$i] ; $j++) :
                        //         echo "
                        //             <tr>
                        //                 <td>$Explode_ID[$j]</td>
                        //             </tr>
                        //         ";
                        //     endfor;
                        // endfor;
                    endwhile;
                else :

                endif;

            ?>

        </tbody>
    </table>