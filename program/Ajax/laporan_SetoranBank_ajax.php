<?php
session_start();
require_once "../../function.php";

$jenis_laporan = ($_POST['jenis_laporan'] != "") ? $_POST['jenis_laporan'] : "";
$dari_bulan = ($_POST['dari_bulan'] != "") ? $_POST['dari_bulan'] : $monts;
$ke_bulan = ($_POST['ke_bulan'] != "") ? $_POST['ke_bulan'] : $_POST['dari_bulan'];

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
        <h2>Laporan Pelunasan</h2>
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
                <th width="15%">Tipe Pembayaran</th>
                <th width="9%">No Invoice</th>
                <th width="15%">Client</th>
                <th width="17%">Total Bayar</th>
                <th width="17%">Adjust</th>
                <th width="17%">Total Terima</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql =
                "SELECT
                    penjualan.tanggal_INV as Tanggal,
                    GROUP_CONCAT(penjualan.no_invoice) as no_invoice,
                    GROUP_CONCAT(penjualan.nama_client) as nama_client,
                    (CASE
                        WHEN pelunasan.type_pem = 'Cash' THEN 'Cash'
                        WHEN pelunasan.type_pem = 'DP' THEN 'Down Payment'
                        WHEN pelunasan.type_pem = 'Kartu Kredit' THEN 'Debit / Kredit / Transfer'
                        WHEN pelunasan.type_pem = 'DP Kartu Kredit' THEN 'DP Debit / Kredit / Transfer'
                        ELSE '- - - -'
                    END) as type_pem,
                    GROUP_CONCAT(pelunasan.tot_pay) as tot_pay,
                    GROUP_CONCAT(pelunasan.adj_pay) as adj_pay,
                    GROUP_CONCAT(penjualan.total_bayar) as total_bayar,
                    GROUP_CONCAT(penjualan.total_adj_pay) as total_adj_pay
                FROM
                    pelunasan
                    LEFT JOIN
                    (
                        SELECT
                            customer.nama_client,
                            penjualan.no_invoice,
                            LEFT(penjualan.invoice_date,10) as tanggal_INV,
                            sum(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) as Total_keseluruhan,
                            pelunasan.total_bayar,
                            pelunasan.total_adj_pay
                        FROM
                            penjualan
                        LEFT JOIN 
                            (select customer.cid, customer.nama_client from customer) customer
                        ON
                            penjualan.client = customer.cid
                        LEFT JOIN 
                            (select pelunasan.no_invoice, sum(pelunasan.tot_pay) as total_bayar, sum(pelunasan.adj_pay) as total_adj_pay from pelunasan group by pelunasan.no_invoice) pelunasan
                        ON
                            penjualan.no_invoice = pelunasan.no_invoice  
                        GROUP BY
                            penjualan.no_invoice
                    ) penjualan
                ON
                    pelunasan.no_invoice = penjualan.no_invoice
                WHERE
                    LEFT(pelunasan.pay_date,10) = '2020-07-02' and 
                    ( pelunasan.type_pem = 'Cash' or pelunasan.type_pem = 'DP' or pelunasan.type_pem = '' )
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
                    $count = count($no_invoice);

                    echo "
                        <tr>
                            <td rowspan='$count'><strong>" . date("d F Y", strtotime($d['Tanggal'])) . "</strong></td> 
                            <td rowspan='$count'><strong>$d[type_pem]</strong></td>        
                            <td class='a-center'><strong>$no_invoice[0]</strong></td>         
                            <td><strong>$nama_client[0]</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_bayar[0]) . "</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_adj_pay[0]) . "</strong></td>         
                            <td class='a-right'><strong>" . number_format($total_bayar[0] + $total_adj_pay[0]) . "</strong></td>         
                        </tr>          
                    ";

                    for ($i = 1; $i < $count; $i++) :
                        echo
                            "<tr>
                                <td class='a-center'><strong>$no_invoice[$i]</strong></td>  
                                <td><strong>$nama_client[$i]</strong></td>  
                                <td class='a-right'><strong>" . number_format($total_bayar[$i]) . "</strong></td>     
                                <td class='a-right'><strong>" . number_format($total_adj_pay[$i]) . "</strong></td>  
                                <td class='a-right'><strong>" . number_format($total_bayar[$i] + $total_adj_pay[$i]) . "</strong></td>    
                            </tr>
                            ";
                    endfor;
                endwhile;
            else :

            endif;
            ?>
        </tbody>
    </table>
</div>