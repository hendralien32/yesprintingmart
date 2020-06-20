<?php

session_start();
require_once "../../function.php";

$sql =
    "SELECT
        penjualan.oid,
        penjualan.id_yes,
        (CASE
            WHEN penjualan.client_yes = '1' THEN penjualan.client_yes
            ELSE customer.nama_client
        END) as nama_client,
        (CASE
            WHEN penjualan.client_yes != '' THEN penjualan.client_yes
            ELSE '- - -'
        END) as client_yes,
        penjualan.description,
        penjualan.keterangan,
        (CASE
            WHEN barang.id_barang > 0 THEN barang.nama_barang
            ELSE penjualan.bahan
        END) as bahan,
        (CASE
            WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            ELSE ''
        END) as ukuran,
        CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
        (CASE
            WHEN penjualan.so_yes != '' THEN penjualan.so_yes
            ELSE '-'
        END) as so_yes,
        (CASE
            WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
            WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
            WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
            WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
            WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
            WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
            WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
            WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
            ELSE '- - -'
        END) as Laminating,
        penjualan.date_create
    FROM
        penjualan
    LEFT JOIN 
        (
            SELECT 
                customer.cid, 
                customer.nama_client 
            FROM 
                customer
        ) customer
    ON
        penjualan.client = customer.cid  
    LEFT JOIN 
        (
            SELECT 
                barang.id_barang, 
                barang.nama_barang 
            FROM 
                barang
        ) barang
    ON
        penjualan.ID_Bahan = barang.id_barang 
    WHERE
        penjualan.oid = '$_POST[ID_Order]'
";
$result = $conn_OOP->query($sql);
if ($result->num_rows > 0) :
    $row = $result->fetch_assoc();
endif;

?>

<style type='text/css'>
    .table-form {
        font-size: 1.1em;
    }
</style>

<h3 class='title_form'><?= 'Preview Detail Order ID No. ' . $row['oid'] ?></h3>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <?php
            if ($row['id_yes'] != "0") :
            ?>
                <tr>
                    <td style='width:120px'>ID YES</td>
                    <td><?= $row['id_yes'] ?></td>
                </tr>
            <?php
            endif;
            ?>
            <tr>
                <td style='width:120px'>Client</td>
                <td><?= $row['nama_client'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Project</td>
                <td><?= $row['description'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Bahan</td>
                <td><?= $row['bahan'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Keterangan</td>
                <td><?= $row['keterangan'] ?></td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <?php
            if ($row['id_yes'] != "0") :
            ?>
                <tr>
                    <td style='width:120px'>SO YES</td>
                    <td><?= $row['so_yes'] ?></td>
                </tr>
            <?php
            endif;
            ?>
            <tr>
                <td style='width:120px'>Client YES</td>
                <td><?= $row['client_yes'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Ukuran</td>
                <td><?= $row['ukuran'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Qty</td>
                <td><?= $row['qty'] ?></td>
            </tr>
            <tr>
                <td style='width:120px'>Lamanating</td>
                <td><?= $row['Laminating'] ?></td>
            </tr>
        </table>
    </div>
</div>