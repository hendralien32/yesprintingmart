<?php
require_once '../function.php';
?>

<div class='plugin-top'>
    <div class='item'>
        <div class='left_title'>Sales Order</div>
        <div class='right_title'>15 Order</div>
    </div>
    <div class='item'>
        <button><i class="fal fa-plus"></i> Add Order</button>
        <button><i class="fal fa-receipt"></i> Create Invoice</button>
    </div>
</div>


<div class='content-table'>
    <table class='table-list'>
        <tr>
            <th width="2%">#</th>
            <th width="8%">Tanggal</th>
            <th width="6%">Order ID</th>
            <th width="43%">Description</th>
            <th width="8%">Icon</th>
            <th width="2%">Sisi</th>
            <th width="15%">Bahan</th>
            <th width="8%">Qty</th>
            <th width="6%">Setter</th>
            <th width="3%"> </th>
        </tr>
        <?php
        $n = 0;

        $sql =
            "SELECT
                penjualan.oid,
                penjualan.waktu as tanggal,
                UPPER(LEFT(penjualan.kode, 1)) as code,
                (CASE
                    WHEN penjualan.kode = 'large format' THEN 'lf'
                    WHEN penjualan.kode = 'digital' THEN 'dp'
                    WHEN penjualan.kode = 'indoor' THEN 'il'
                    WHEN penjualan.kode = 'Xuli' THEN 'ix'
                    WHEN penjualan.kode = 'offset' THEN 'o'
                    WHEN penjualan.kode = 'etc' THEN 'e'
                    ELSE 'e'
                END) as kode_barang,
                penjualan.sisi,
                (CASE
                    WHEN penjualan.status = 'selesai' THEN 'Y'
                    WHEN penjualan.status = '' THEN 'N'
                    ELSE ''
                END) as Finished,
                penjualan.acc, 
                penjualan.no_invoice,
                (CASE
                    WHEN barang.id_barang > 0 THEN barang.nama_barang
                    ELSE penjualan.bahan
                END) as bahan,
                CONCAT('<b>', penjualan.qty, '</b> ' ,penjualan.satuan) as qty,
                (CASE
                    WHEN penjualan.panjang > 0 || penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    ELSE ''
                END) as ukuran,
                penjualan.ditunggu,
                (CASE
                    WHEN penjualan.acc = 'Y' THEN 'Y'
                    WHEN penjualan.acc = 'N' THEN 'N'
                    ELSE 'N'
                END) as acc,
                penjualan.Design,
                penjualan.description,
                customer.nama_client,
                customer.no_telp,
                setter.nama as Nama_Setter,
                penjualan.cancel,
                (CASE
                    WHEN penjualan.pembayaran = 'lunas' THEN 'Y'
                    ELSE 'N'
                END) as pembayaran,
                (CASE
                    WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                    WHEN penjualan.akses_edit = 'N' THEN 'N'
                    ELSE 'N'
                END) as akses_edit
            from
                penjualan
            LEFT JOIN 
                (select customer.cid, customer.nama_client, customer.no_telp from customer) customer
            ON
                penjualan.client = customer.cid  
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) barang
            ON
                penjualan.ID_Bahan = barang.id_barang  
            LEFT JOIN 
                (select pm_user.uid, pm_user.nama from pm_user) setter
            ON
                penjualan.setter = setter.uid  
            where
                penjualan.oid != '' and
                penjualan.client !='1'
            order by
                penjualan.oid
            desc
            LIMIT
                50
        ";

        // Perform query
        $result = $conn_OOP->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($d = $result->fetch_assoc()) :
                $n++;
                $tanggal = date_format(date_create($d['tanggal']), 'j M Y');
                $Waktu = date_format(date_create($d['tanggal']), 'h:i A');
                $No_telp = "$d[no_telp]";

                if (strlen($d['no_telp']) == 12) :
                    $telp = sprintf(
                        "- %s-%s-%s",
                        substr($No_telp, 0, 4),
                        substr($No_telp, 4, 4),
                        substr($No_telp, 8)
                    );
                elseif (strlen($d['no_telp']) == 10) :
                    $telp = sprintf(
                        "- %s-%s-%s",
                        substr($No_telp, 0, 4),
                        substr($No_telp, 4, 3),
                        substr($No_telp, 7)
                    );
                elseif (strlen($d['no_telp']) == 7) :
                    $telp = sprintf(
                        "- (061) %s-%s",
                        substr($No_telp, 0, 3),
                        substr($No_telp, 3, 4)
                    );
                else :
                    $telp = "";
                endif;
                echo "
                <tr>
                    <td><center>$n</center></td>
                    <td>
                        <div class='test'>
                            <span class='client_name'>
                                <center>$tanggal</center>
                            </span>
                            <span>
                                <center>$Waktu</center>
                            </span>
                        </div>
                    </td>
                    <td><center>$d[oid]</center></td>
                    <td class='deskripsi'>
                        <div class='$d[kode_barang]'>
                            <span>
                                $d[code]
                            </span>
                        </div>
                        <div class='test'>
                            <span class='client_name'>
                                $d[nama_client] $telp
                            </span>
                            <span>
                                $d[description] $d[ukuran]
                            </span>
                        </div>
                    </td>
                    <td class='icon>
                        <div>
                            <div class='icon_A>
                                <i class='fas fa-cash-register'></i>
                                <i class='fas fa-cash-register'></i>
                                <i class='fas fa-cash-register'></i>
                            </div>
                        </div>
                        <div>
                            <div class='icon_B'>
                                <i class='fas fa-cash-register'></i>
                                <i class='fas fa-cash-register'></i>
                                <i class='fas fa-cash-register'></i>
                            </div>
                        </div>
                    </td>
                    <td><center><b>$d[sisi]</b></center></td>
                    <td>$d[bahan]</td>
                    <td>$d[qty]</td>
                    <td>$d[Nama_Setter]</td>
                    <td><i class='fas fa-cogs'></i></td>
                </tr>
                ";
            endwhile;
        } else {
        }
        ?>
    </table>
</div>