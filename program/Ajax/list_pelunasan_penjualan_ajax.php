<?php
    require_once "../../function.php";
?>

<script>
    $(function () {
        $("td").hover(function () {
            $el = $(this);
            $el.parent().addClass("hover");
            var tdIndex = $('tr').index($el.parent());
            if ($el.parent().has('td[rowspan]').length == 0) {
                $el.parent().prevAll('tr:has(td[rowspan]):first')
                .find('td[rowspan]').filter(function () {
                    return checkRowSpan(this, tdIndex);
                }).addClass("hover");
            }
        }, function () {
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
        <h2>Laporan <?= $sub_table ?></h2>
    </div>
    <div class='plugin_icon'>
        <span onclick='export_xls()'><i class="fas fa-file-excel"></i> Export</span>
        <!-- <span><i class="fas fa-print"></i> Print</span> -->
    </div>
</div>
<div>
    <table>
        <tbody>
            <tr>
                <th width="1%">#</th>
                <th width="12%">Client</th>
                <th width="8%">Tanggal Invoice</th>
                <th width="8%">No Invoice</th>
                <th width="37%">Deskripsion</th>
                <th width="12%">Tipe Pembayaran</th>
                <th width="7%">Total Bayar</th>
                <th width="6%">Adjust</th>
                <th width="8%">Total Terima</th>
                <th width="1%"></th>
            </tr>
            <?php
                if($_POST['type_bayar']=="A") {
                    $add_where = "and (pelunasan.type_pem ='cash' or pelunasan.type_pem ='Cash' or pelunasan.type_pem ='DP' or pelunasan.type_pem ='dp' or pelunasan.type_pem ='Dp' or pelunasan.type_pem ='')";
                } elseif($_POST['type_bayar']=="B") {
                    $add_where = "and (pelunasan.type_pem ='Kartu Kredit' or pelunasan.type_pem ='kartu kredit' or pelunasan.type_pem ='DP Kartu Kredit')";
                } else {
                    $add_where = "";
                }

                $sql=
                "SELECT
                    penjualan.nama_client,
                    penjualan.tanggal_INV,
                    penjualan.oid,
                    penjualan.description,
                    penjualan.kode_barang,
                    pelunasan.pid,
                    pelunasan.no_invoice,
                    (CASE
                        WHEN pelunasan.type_pem = 'Cash' THEN 'Cash'
                        WHEN pelunasan.type_pem = 'DP' THEN 'Down Payment'
                        WHEN pelunasan.type_pem = 'Kartu Kredit' THEN 'Debit / Kredit / Transfer'
                        WHEN pelunasan.type_pem = 'DP Kartu Kredit' THEN 'DP Debit / Kredit / Transfer'
                        ELSE '- - - -'
                    END) as type_pem,
                    pelunasan.tot_pay,
                    pelunasan.adj_pay,
                    penjualan.pembayaran,
                    penjualan.Total_keseluruhan,
                    penjualan.total_bayar                    
                FROM
                    pelunasan
                LEFT JOIN
                    (
                        SELECT
                            customer.nama_client,
                            penjualan.no_invoice,
                            GROUP_CONCAT(penjualan.oid) as oid,
                            GROUP_CONCAT(penjualan.description SEPARATOR '*_*') as description,
                            GROUP_CONCAT(REPLACE( penjualan.kode, ' ', '_' ) SEPARATOR ',') as kode_barang,
                            LEFT(penjualan.invoice_date,10) as tanggal_INV,
                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                            pelunasan.total_bayar,
                            penjualan.pembayaran
                        FROM
                            penjualan
                        LEFT JOIN 
                            (select customer.cid, customer.nama_client from customer) customer
                        ON
                            penjualan.client = customer.cid
                        LEFT JOIN 
                            (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar from pelunasan group by pelunasan.no_invoice) pelunasan
                        ON
                            penjualan.no_invoice = pelunasan.no_invoice  
                        GROUP BY
                            penjualan.no_invoice
                    ) penjualan
                ON
                    pelunasan.no_invoice = penjualan.no_invoice
                WHERE
                    left(pelunasan.pay_date,10) = '$_POST[date]'
                    $add_where
                ORDER BY
                    pelunasan.pay_date
                DESC 
                ";

                $no = 0;

                $result = $conn_OOP -> query($sql);

                if ($result->num_rows > 0) :
                    while ($row = $result->fetch_assoc()) :
                        $no++;
                        $total_terima = $row['tot_pay'] - $row['adj_pay'];
                        $oid   = explode("," , "$row[oid]");
                        $description   = explode("*_*" , "$row[description]");
                        $kode_barang   = explode("," , "$row[kode_barang]");
                        $count = count($oid);

                        if($row['pembayaran']=="lunas") : 
                            $print = "<a href='print.php?type=sales_invoice&no_invoice=". $row['no_invoice'] ."' target='_blank' class='pointer'><i class='fad fa-print'></i>";
                        elseif($row['Total_keseluruhan'] == $row['total_bayar']) : 
                            $print = "<a href='print.php?type=sales_invoice&no_invoice=". $row['no_invoice'] ."' target='_blank' class='pointer'><i class='fad fa-print'></i>";
                        else : 
                            $print = "";
                        endif;

                        echo "
                        <tr>
                            <td rowspan='$count'>$no</td>
                            <td rowspan='$count'>$row[nama_client]</td>
                            <td rowspan='$count'>". date("d M Y",strtotime($row['tanggal_INV'] ))."</td>
                            <td rowspan='$count' class='t-center pointer' onclick='LaodForm(\"pelunasan_invoice\", \"". $row['no_invoice'] ."\")'>#$row[no_invoice]</td>
                            <td><b class='tanda_$kode_barang[0]'>▐</b> $oid[0] - $description[0]</td>
                            <td class='t-center' rowspan='$count'>$row[type_pem]</td>
                            <td class='t-right' rowspan='$count'>". number_format($row['tot_pay']) ."</td>
                            <td class='t-right' rowspan='$count'>". number_format($row['adj_pay']) ."</td>  
                            <td class='t-right' rowspan='$count' onclick='LaodSubForm(\"pelunasan_InvEdit\", \"". $row['pid'].'*'.$row['no_invoice'] ."\")'>". number_format($total_terima) ."</td>     
                            <td rowspan='$count'>$print</td>                       
                        </tr>
                        ";

                        for($i=1; $i<$count ;$i++) :
                            echo 
                            "<tr>
                                <td><b class='tanda_$kode_barang[$i]'>▐</b> $oid[$i] - $description[$i]</td>
                            </tr>
                            ";
                        endfor;

                        $total_penjualan[]   = $total_terima;
                        $Nilai_Total = number_format(array_sum($total_penjualan));
                    endwhile;
                else :
                    echo "
                        <tr>
                            <td colspan='9'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
                    $Nilai_Total = "0";
                endif;
            ?>
            <tr><th colspan="8">Total Pembayaran <?= date("d M Y",strtotime($_POST['date'] )) ?></th><th style="text-align:right; padding-right:10px"><?= $Nilai_Total ?></th></tr>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?>