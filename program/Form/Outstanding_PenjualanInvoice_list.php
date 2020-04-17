<?php
    session_start();
    include '../../function.php';
?>

<div class="row">
    <div class="col-6">
        <table class='table-form'>
            <tr><td>Client</td><td>
    		<select class="myinput" id="form_client" onchange="invoice_outstanding();">
                <option value=''>Pilih nama client</option>
                <?php
                    $sql=
                    "SELECT 
                        penjualan.client,
                        penjualan.no_invoice,
                        customer.nama_client,
                        count(penjualan.client) as Qty_OID
                    FROM
                        penjualan
                    LEFT JOIN
                        (select customer.cid, customer.nama_client from customer) customer
                    ON
                        penjualan.client = customer.cid  
                    WHERE
                        penjualan.no_invoice = '0' and 
                        customer.cid !='1' and
                        penjualan.cancel!='Y' and
                        penjualan.setter = '$_POST[InvoiceList_setter_check]'
                    GROUP BY
                        penjualan.client
                    ORDER BY
                        customer.nama_client
                    DESC
                    ";

                    // Perform query
                    $result = $conn_OOP -> query($sql);

                    if ($result->num_rows > 0) :
                        // output data of each row
                        while($d = $result->fetch_assoc()) {
                            if($_POST['InvoiceList_client_check']=="") : $check = "0"; 
                            else : $check = "$_POST[InvoiceList_client_check]"; 
                            endif;

                            if($d['client']=="$check") : $pilih = "selected"; 
                            else : $pilih = "";
                            endif;

                            echo "<option value='$d[client],$d[Qty_OID]' $pilih>$d[nama_client] [$d[Qty_OID]]</option>";
                        }
                    else :

                    endif;
                ?>
            </select>
            </td> </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr> 
                <td>Setter</td>
                <td>
                <select class="myinput" id="form_setter" onchange="invoice_outstanding()">
                    <option value=''>Pilih nama Setter</option>
                    <?php
                        $sql=
                        "SELECT 
                            pm_user.uid,
                            pm_user.nama,
                            pm_user.status,
                            IFNULL(penjualan.Qty_OID, 0) as Qty_OID
                        FROM 
                            pm_user
                        LEFT JOIN
                            ( select penjualan.setter, penjualan.oid, count(penjualan.setter) as Qty_OID from penjualan where penjualan.no_invoice='0' and penjualan.cancel!='Y' GROUP BY penjualan.setter ) penjualan
                        ON
                            pm_user.uid = penjualan.setter
                        WHERE
                            pm_user.status = 'a' and
                            ( pm_user.level = 'admin' or pm_user.level = 'CS' or pm_user.level = 'Setter' )
                        ORDER BY 
                            pm_user.nama
                        DESC
                        ";

                        // Perform query
                        $result = $conn_OOP -> query($sql);

                        if ($result->num_rows > 0) :
                            // output data of each row
                            while($d = $result->fetch_assoc()) {
                                if($_POST['InvoiceList_setter_check']=="") : $check = "$_SESSION[uid]"; 
                                else : $check = "$_POST[InvoiceList_setter_check]"; 
                                endif;

                                if($d['uid']=="$check") : $pilih = "selected"; 
                                else : $pilih = ""; 
                                endif;
                                
                                echo "<option value='$d[uid]' $pilih>$d[nama] [$d[Qty_OID]]</option>";
                            }
                        else :

                        endif;
                    ?>
                </select>
                </td> 
            </tr>
        </table>
    </div>
</div>
<div class="container-fluid">
    <table class='form_table'>
        <tbody>
            <tr>
                <th width="2%"><input type='checkbox' onclick='toggle(this)'/></th>
                <th width="10%">No. Order</th>
                <th width="3%">K</th>
                <th width="35%">Description</th>
                <th width="3%">S</th>
                <th width="13%">Bahan</th>
                <th width="12%">Ukuran</th>
                <th width="10%">Qty</th>
                <th width="10%">Setter</th>
            </tr>
            <tr>
            <?php
                $sql=
                "SELECT
                    penjualan.oid,
                    penjualan.sisi,
                    penjualan.no_invoice,
                    (CASE
                        WHEN penjualan.sisi = '1' THEN 'satu'
                        WHEN penjualan.sisi = '2' THEN 'dua'
                        ELSE ''
                    END) as css_sisi,
                    (CASE
                        WHEN barang.id_barang > 0 THEN barang.nama_barang
                        ELSE penjualan.bahan
                    END) as bahan,
                    CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                    (CASE
                        WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                        WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                        ELSE penjualan.ukuran
                    END) as ukuran,
                    penjualan.description,
                    LEFT( penjualan.waktu, 10 ) as tanggal,
                    LEFT(penjualan.kode, 1) as code,
                    penjualan.kode as kode_barang,
                    setter.nama as Nama_Setter,
                    penjualan.cancel
                from
                    penjualan
                LEFT JOIN 
                    (select barang.id_barang, barang.nama_barang from barang) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang  
                LEFT JOIN 
                    (select pm_user.uid, pm_user.nama from pm_user) setter
                ON
                    penjualan.setter = setter.uid  
                where
                    penjualan.no_invoice = '0' and 
                    penjualan.client !='1' and 
                    penjualan.setter = '$_POST[InvoiceList_setter_check]' and
                    penjualan.client = '$_POST[InvoiceList_client_check]' and
                    penjualan.cancel != 'Y'
                order by
                    penjualan.oid
                desc
                ";
                $n = 0;

                // Perform query
                $result = $conn_OOP->query($sql);

                if ($result->num_rows > 0) :
                    // output data of each row
                    while($d = $result->fetch_assoc()) :
                        $n++;
                        $kode_class=str_replace(" ","_",$d['kode_barang']);
                        echo "
                            <tr>
                                <td><input type='checkbox' id='cek_$n' name='option' value='$d[oid]'></td>
                                <td><center>". $d['oid'] ."</Center></td>
                                <td><span class='KodeProject ".$kode_class."'>". strtoupper($d['code']) ."</span></td>
                                <td>". $d['description'] ."</td>
                                <td><center><span class='".$d['css_sisi']." KodeProject'>". $d['sisi'] ."</span></center></td>
                                <td>". $d['bahan'] ."</td>
                                <td>". $d['ukuran'] ."</td>
                                <td>". $d['qty'] ."</td>
                                <td>". $d['Nama_Setter'] ."</td>
                            </tr>
                        ";
                    endwhile;
                else :

                endif;
            ?>
            </tr>
        </tbody>
    </table>
    <center><input type="button" class="myinput" value="Create Invoice" onclick="submitInvoice('create_invoice')"></center>
</div>

<?php $conn -> close(); ?>