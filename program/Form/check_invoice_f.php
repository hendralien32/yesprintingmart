<?php
    session_start();
    require_once "../../function.php";

    $sql_query = 
    "SELECT
        GROUP_CONCAT(penjualan.oid) as oid,
        customer.nama_client,
        GROUP_CONCAT(penjualan.description SEPARATOR '◘') as description,
        GROUP_CONCAT((CASE
            WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
            ELSE ''
        END)) as ukuran
    FROM
        penjualan
    LEFT JOIN 
        (select customer.cid, customer.nama_client from customer) customer
    ON
        penjualan.client = customer.cid  
    WHERE
        penjualan.no_invoice = $_POST[data]
    GROUP BY
        penjualan.no_invoice
    ORDER BY
        penjualan.oid
    DESC
    ";

    $result = mysqli_query($conn, $sql_query);
            
    if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);

        $oid = explode("," , "$row[oid]");
        $count_oid = count($oid);
    }

?>

<h3 class='title_form'>Check Invoice Penjualan No. Invoice #<?= $_POST['data'] ?></h3>

<table class='table_checkInv'>
    <thead>
        <tr>
            <th>#</th>
            <th>OID</th>
            <th>Client</th>
            <th>Deskripsi</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i=0;$i<$count_oid ;$i++){
                $n = $i+1;
                
                echo "
                <tr>
                    <td>$n</td>
                    <td>$row[oid]</td>
                    <td>$row[nama_client]</td>
                    <td>Deskripsi : $row[description] <br> Bahan : TIC 260gr<br>Sisi : 1<br>Qty : 10 Lembar</td>
                    <td>Harga @ : 2.500</td>
                </tr>
                ";
            }
        ?>
    </tbody>
</table>
