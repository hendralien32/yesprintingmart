<?php
session_start();
require_once "../../function.php";

$jenis_laporan = ($_POST['jenis_laporan'] != "") ? $_POST['jenis_laporan'] : "";
$dari_tanggal = ($_POST['dari_tanggal'] != "") ? $_POST['dari_tanggal'] : $date;

if($jenis_laporan == "Cash") :
    $title = "Cash";
    $add_where = "and (pelunasan.type_pem ='cash' or pelunasan.type_pem ='Cash' or pelunasan.type_pem ='DP' or pelunasan.type_pem ='dp' or pelunasan.type_pem ='Dp' or pelunasan.type_pem ='')";
elseif($jenis_laporan == "CC") :
    $title = "Debit / Kredit / TRF";
    $add_where = "and (pelunasan.type_pem ='Kartu Kredit' or pelunasan.type_pem ='kartu kredit' or pelunasan.type_pem ='DP Kartu Kredit')";
else :
    $title = "";
    $add_where = "";
endif;

?>

<script>
    $(function() {
        $("td").hover(function() {
            $el = $(this);
            $el.parent().addClass("hover");
            var tdIndex = $('tr').index($el.parent());
            if ($el.parent().has('td[rowspan]').length == 0) {
                $el.parent().prevAll('tr:has(td[rowspan]):first')
                    .find('td[rowspan]').filter(function() {
                        return checkRowSpan(this, tdIndex);
                    }).addClass("hover");
            }
        }, function() {
            $el.parent()
                .removeClass("hover")
                .prevAll('tr:has(td[rowspan]):first')
                .find('td[rowspan]')
                .removeClass("hover");
        });
    });

    function checkRowSpan(element, pIndex) {
        var rowSpan = parseInt($(element).attr('rowspan'));
        var cIndex = $('tr').index($(element).parent());
        return rowSpan >= pIndex + 1 || (cIndex + rowSpan) > pIndex;
    }
</script>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<div id='laporan_header'>
    <div class='judul_laporan'>
        <h2>Laporan Pelunasan <?= $title ?></h2>
    </div>
    <div class='plugin_icon'>
        <span onclick='export_xls()'><i class="fas fa-file-excel"></i> Export</span>
        <!-- <span><i class="fas fa-print"></i> Print</span> -->
    </div>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th width="10%">Tanggal</th>
                <th width="22%">Tipe Pembayaran</th>
                <th width="9%">No Invoice</th>
                <th width="15%">Client</th>
                <th width="11%">Total Bayar</th>
                <th width="11%">Adjust</th>
                <th width="11%">Total Terima</th>
                <th width="11%">total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql =
                "SELECT
                    penjualan.tanggal_INV as Tanggal,
                    GROUP_CONCAT(penjualan.no_invoice) as no_invoice,
                    GROUP_CONCAT(penjualan.nama_client) as nama_client,
                    pelunasan.type_pem,
                    pelunasan.jenis_kartu, 
                    pelunasan.nomor_kartu,
                    pelunasan.rekening_tujuan,
                    GROUP_CONCAT(pelunasan.tot_pay) as tot_pay,
                    GROUP_CONCAT(pelunasan.adj_pay) as adj_pay
                FROM
                    pelunasan
                    LEFT JOIN
                    (
                        SELECT
                            customer.nama_client,
                            penjualan.no_invoice,
                            LEFT(penjualan.invoice_date,10) as tanggal_INV,
                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan
                        FROM
                            penjualan
                        LEFT JOIN 
                            (select customer.cid, customer.nama_client from customer) customer
                        ON
                            penjualan.client = customer.cid 
                        GROUP BY
                            penjualan.no_invoice
                    ) penjualan
                ON
                    pelunasan.no_invoice = penjualan.no_invoice
                WHERE
                    LEFT(pelunasan.pay_date,10) = '$dari_tanggal' 
                    $add_where
                GROUP BY
                    penjualan.tanggal_INV
                ORDER BY
                    penjualan.tanggal_INV
            ";
            $result = $conn_OOP->query($sql);
            if ($result->num_rows > 0) :
                while ($d = $result->fetch_assoc()) :
                    $no_invoice   = explode(",", "$d[no_invoice]");
                    $nama_client   = explode(",", "$d[nama_client]");
                    $total_bayar   = explode(",", "$d[tot_pay]");
                    $total_adj_pay   = explode(",", "$d[adj_pay]");
                    $x = array_sum($total_bayar);
                    $y = array_sum($total_adj_pay);
                    $count = count($no_invoice);

                    if($d['type_pem']==='cash' or $d['type_pem']==='Cash') {
                        $type_pem="$d[type_pem]"; 
                    } elseif($d['type_pem']=="DP") {
                        $type_pem="Down Payment";
                    } elseif($d['type_pem']=="Kartu Kredit" or $d['type_pem']=="DP Kartu Kredit") {
                        $type_pem = "Debit / Kredit / TRF";
                        if($d['jenis_kartu'] != "") :
                            $type_pem .=  " - $d[jenis_kartu]";
                        else : 
                            $type_pem .=  "";
                        endif;

                        if($d['nomor_kartu'] != "") :
                            $type_pem .=  " ( $d[nomor_kartu] )";
                        else : 
                            $type_pem .=  "";
                        endif;

                        if($d['rekening_tujuan'] != "") :
                            $type_pem .=  " --> $d[rekening_tujuan] ( A/N Muliadi )";
                        else : 
                            $type_pem .=  "";
                        endif;
                    } else { 
                        $type_pem="- - -"; 
                    }

                    echo "
                        <tr>
                            <td rowspan='$count'><strong>" . date("d F Y", strtotime($d['Tanggal'])) . "</strong></td> 
                            <td rowspan='$count'><strong>$type_pem</strong></td>        
                            <td class='a-center'><strong>$no_invoice[0]</strong></td>         
                            <td><strong>$nama_client[0]</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_bayar[0]) . "</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_adj_pay[0]) . "</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_bayar[0] - $total_adj_pay[0]) . "</strong></td>   
                            <td rowspan='$count' class='a-right'><strong>" . number_format($x - $y) . "</strong></td>          
                        </tr>          
                    ";

                    for ($i = 1; $i < $count; $i++) :
                        echo
                            "<tr>
                                <td class='a-center'><strong>$no_invoice[$i]</strong></td>  
                                <td><strong>$nama_client[$i]</strong></td>  
                                <td class='a-right'><strong>" . number_format($total_bayar[$i]) . "</strong></td>     
                                <td class='a-right'><strong>" . number_format($total_adj_pay[$i]) . "</strong></td>  
                                <td class='a-right'><strong>" . number_format($total_bayar[$i] - $total_adj_pay[$i]) . "</strong></td>    
                            </tr>
                            ";
                    endfor;
                    
                    $Xtotal_bayar[]   = array_sum($total_bayar);
                    $Xtotal_adj_pay[]   = array_sum($total_adj_pay);

                    $a = array_sum($Xtotal_bayar);
                    $b = array_sum($Xtotal_adj_pay);
                endwhile;
            else :
                $a = 0;
                $b = 0;

                echo "
                    <tr>
                        <td colspan='8'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                    </tr>
                ";
            endif;
            ?>
            <tr>
                <th colspan="4">Total Pelunasan</th>
                <th style='text-align:right'><?= number_format($a); ?></th>
                <th style='text-align:right'><?= number_format($b); ?></th>
                <th style='text-align:right'><?= number_format($a - $b); ?></th>
                <th style='text-align:right'><?= number_format($a - $b); ?></th>
            </tr>
        </tbody>
    </table>
</div>