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
            <th width="1%">#</th>
            <th width="6%">Order ID</th>
            <th width="50%">Description</th>
            <th width="10%">Icon</th>
            <th width="2%">Sisi</th>
            <th width="15%">Bahan</th>
            <th width="8%">Qty</th>
            <th width="6%">Setter</th>
            <th width="3%"> </th>
        </tr>
        <?php
        $n = 1;

        $sql =
            "SELECT
                penjualan.oid,
                penjualan.sisi,
                (CASE
                    WHEN penjualan.sisi = '1' THEN 'satu'
                    WHEN penjualan.sisi = '2' THEN 'dua'
                    ELSE ''
                END) as css_sisi,
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
                CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                (CASE
                    WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
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
                LEFT( penjualan.waktu, 10 ) as tanggal,
                LEFT(penjualan.kode, 1) as code,
                penjualan.kode as kode_barang,
                customer.nama_client,
                setter.nama as Nama_Setter,
                penjualan.cancel,
                penjualan.posisi_file,
                penjualan.img_design,
                penjualan.file_design,
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
                (select customer.cid, customer.nama_client from customer) customer
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
                echo "
                <tr>
                    <td>$n</td>
                    <td>
                        <span>
                            $d[oid]
                        </span>
                    </td>
                    <td class='deskripsi'>
                        <div class='lf'>
                            <span>
                                X
                            </span>
                        </div>
                        <div class='test'>
                            <span class='client_name'>
                                $d[nama_client]
                            </span>
                            <span>
                                $d[description]
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class='icon_detail'>
                            <i class='fas fa-cash-register'></i>
                            <i class='fas fa-cash-register'></i>
                            <i class='fas fa-cash-register'></i>
                            <i class='fas fa-cash-register'></i>
                        </div>
                    </td>
                    <td>1</td>
                    <td>TIC 260gr</td>
                    <td>1 Lembar</td>
                    <td>Kristy</td>
                    <td><i class='fas fa-cogs'></i></td>
                </tr>
                ";
            endwhile;
        } else {
        }
        ?>
    </table>
</div>