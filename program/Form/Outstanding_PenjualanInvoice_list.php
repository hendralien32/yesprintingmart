<?php
session_start();
include '../../function.php';

$status = isset($_POST['status']) ? $_POST['status'] : "";
$Invoice_Number = isset($_POST['Invoice_Number']) ? $_POST['Invoice_Number'] : "";

if ($status == 'penjualan_invoice_yescom') : ?>
    <div class="row">
        <div class="col-6">
            <table class='table-form'>
                <tr>
                    <td style="width:140px">Jenis WO</td>
                    <td>
                        <?php if ($_POST['Invoice_Number'] == "") : ?>
                            <select id="form_jenisWO" onchange="invoice_outstanding('penjualan_invoice_yescom');">
                                <option value=''>Pilih Jenis WO</option>
                                <?php
                                $sql =
                                    "SELECT 
                                            penjualan.jenis_wo,
                                            count(penjualan.jenis_wo) as Qty_JenisWO
                                        FROM
                                            penjualan
                                        WHERE
                                            penjualan.no_invoice = '0' and
                                            ( penjualan.jenis_wo = 'Kuning' or penjualan.jenis_wo = 'Hijau' ) and
                                            penjualan.client = '1' and
                                            penjualan.cancel!='Y'
                                        GROUP BY
                                            penjualan.jenis_wo
                                        ORDER BY
                                            penjualan.jenis_wo
                                        DESC
                                        ";
                                // Perform query
                                $result = $conn_OOP->query($sql);

                                if ($result->num_rows > 0) :
                                    // output data of each row
                                    while ($d = $result->fetch_assoc()) {
                                        if ($_POST['InvoiceList_JenisWO_check'] == "") : $check = "0";
                                        else : $check = "$_POST[InvoiceList_JenisWO_check]";
                                        endif;

                                        if ($d['jenis_wo'] == "$check") : $pilih = "selected";
                                        else : $pilih = "";
                                        endif;

                                        echo "<option value='$d[jenis_wo]' $pilih>$d[jenis_wo] [$d[Qty_JenisWO]]</option>";
                                    }
                                endif;
                                ?>
                            </select>
                        <?php else :
                            echo "$_POST[InvoiceList_JenisWO_check]";
                        endif;
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class='table-form'>
                <tr>
                    <td style="width:140px">Kode</td>
                    <td>
                        <?php if ($_POST['Invoice_Number'] == "") : ?>
                            <select id="form_Kode" onchange="invoice_outstanding();">
                                <option value=''>Pilih Kode</option>
                                <?php
                                $sql =
                                    "SELECT 
                                            penjualan.kode,
                                            (CASE
                                                WHEN penjualan.kode = 'large format' THEN 'Large Format'
                                                WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                                                WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                                                WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                                                WHEN penjualan.kode = 'etc' THEN 'ETC'
                                                ELSE penjualan.kode
                                            END) as Kode_barang,
                                            count(penjualan.kode) as Qty_kode
                                        FROM
                                            penjualan
                                        WHERE
                                            penjualan.no_invoice = '0' and
                                            ( penjualan.kode = 'large format' or penjualan.kode = 'digital' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' or penjualan.kode = 'etc' ) and
                                            penjualan.client = '1' and
                                            penjualan.cancel!='Y' and
                                            penjualan.jenis_wo = '$_POST[InvoiceList_JenisWO_check]'
                                        GROUP BY
                                            penjualan.kode
                                        ORDER BY
                                            penjualan.kode
                                        ASC
                                        ";
                                // Perform query
                                $result = $conn_OOP->query($sql);

                                if ($result->num_rows > 0) :
                                    // output data of each row
                                    while ($d = $result->fetch_assoc()) {
                                        if ($_POST['InvoiceList_Kode_check'] == "") : $check = "0";
                                        else : $check = "$_POST[InvoiceList_Kode_check]";
                                        endif;

                                        if ($d['kode'] == "$check") : $pilih = "selected";
                                        else : $pilih = "";
                                        endif;

                                        echo "<option value='$d[kode],$d[Qty_kode]' $pilih>$d[Kode_barang] [$d[Qty_kode]]</option>";
                                    }
                                endif;
                                ?>
                            </select>
                        <?php else :
                            echo "$_POST[InvoiceList_Kode_check]";
                        endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="container-fluid">
        <table class='form_table'>
            <tbody>
                <tr>
                    <th width="2%" class="contact100-form-checkbox" style='padding-top:13px;'>
                        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='toggle(this)'>
                        <label class="label-checkbox100" for="Check_box"></label>
                    </th>
                    <th width="5%">ID</th>
                    <th width="4%">SO</th>
                    <th width="51%">Client - Deskripsi</th>
                    <th width="3%">S</th>
                    <th width="13%">Bahan</th>
                    <th width="12%">Ukuran</th>
                    <th width="10%">Qty</th>
                </tr>
            </tbody>
            <?php
            if ($_POST['Invoice_Number'] == "0") :
                $add_where = "";
            else :
                $add_where = "
                                ( penjualan.no_invoice = '0' or penjualan.no_invoice = '$Invoice_Number' ) and 
                                penjualan.client = '1' and
                                penjualan.jenis_wo = '$_POST[InvoiceList_JenisWO_check]' and
                                penjualan.kode = '$_POST[InvoiceList_Kode_check]' and
                            ";
            endif;

            $sql =
                "SELECT
                    penjualan.client_yes,
                    penjualan.id_yes,
                    penjualan.no_invoice,
                    penjualan.so_yes,
                    penjualan.oid,
                    penjualan.sisi,
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
                    $add_where
                    penjualan.cancel != 'Y' and
                    penjualan.pembayaran != 'lunas'
                order by
                    penjualan.oid
                ASC
            ";
            $n = 0;
            $result = $conn_OOP->query($sql);
            if ($result->num_rows > 0) :
                while ($row = $result->fetch_assoc()) :
                    $n++;
                    if ($row['no_invoice'] != "0") {
                        $checkbox = "checked";
                    } else {
                        $checkbox = "";
                    }

                    echo "
                        <tr>
                            <td class='contact100-form-checkbox' style='padding-top:16px;'>
                                <input class='input-checkbox100' id='cek_$n' type='checkbox' name='option' value='$row[oid]' $checkbox>
                                <label class='label-checkbox100' for='cek_$n'></label>
                            </td>
                            <td>$row[id_yes]</td>
                            <td>$row[so_yes]</td>
                            <td>$row[client_yes] - $row[description]</td>
                            <td><center><span class='$row[css_sisi] KodeProject'>$row[sisi]</span></center></td>
                            <td>$row[bahan]</td>
                            <td>$row[ukuran]</td>
                            <td>$row[qty]</td>
                        </tr>
                    ";
                endwhile;
            endif;
            ?>
        </table>
        <?php
        if ($Invoice_Number == "") :
        ?>
            <center><input type="button" class="myinput" value="Create Yescom Invoice" onclick="submitInvoice('create_invoice')"></center>
        <?php
        else :
        ?>
            <center><input type="button" class="myinput" value="Re-Add Yescom Invoice" onclick="submitInvoice('ReAdd_Invoice')"></center>
        <?php
        endif;
        ?>
    </div>

<?php elseif ($status == 'setter_penjualan_invoice') :
    $sql =
        "SELECT
            penjualan.no_invoice,
            penjualan.client,
            customer.nama_client,
            setter.nama
         FROM
            penjualan
         LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
         ON
            penjualan.client = customer.cid  
         LEFT JOIN 
            (select pm_user.uid, pm_user.nama from pm_user) setter
         ON
            penjualan.setter = setter.uid  
         WHERE
            penjualan.no_invoice = '$Invoice_Number'
     ";
    $n = 0;

    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        $d = $result->fetch_assoc();
        $id_Client = "$d[client]";
        $Client = "$d[nama_client]";
        $Setter = "$d[nama]";
    else :
        $id_Client = "";
        $Client = "0";
        $Setter = "$_SESSION[uid]";
    endif;

    if ($_POST['Invoice_Number'] == "0") :
        $add_where = "";
    else :
        $add_where = "
                ( penjualan.no_invoice = '0' or penjualan.no_invoice = '$Invoice_Number' ) and 
                penjualan.client !='1' and 
                penjualan.setter = '$_POST[InvoiceList_setter_check]' and
                penjualan.client = '$_POST[InvoiceList_client_check]' and
            ";
    endif; ?>

    <div class="row">
        <div class="col-6">
            <table class='table-form'>
                <tr>
                    <td>Client</td>
                    <td>
                        <?php if ($Invoice_Number == "") : ?>
                            <select class="myinput" id="form_client" onchange="invoice_outstanding();">
                                <option value=''>Pilih nama client</option>
                                <?php
                                $sql =
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
                                ";

                                // Perform query
                                $result = $conn_OOP->query($sql);

                                if ($result->num_rows > 0) :
                                    // output data of each row
                                    while ($d = $result->fetch_assoc()) {
                                        if ($_POST['InvoiceList_client_check'] == "") : $check = "0";
                                        else : $check = "$_POST[InvoiceList_client_check]";
                                        endif;

                                        if ($d['client'] == "$check") : $pilih = "selected";
                                        else : $pilih = "";
                                        endif;

                                        echo "<option value='$d[client],$d[Qty_OID]' $pilih>$d[nama_client] [$d[Qty_OID]]</option>";
                                    }
                                else :

                                endif;
                                ?>
                            </select>
                        <?php else : echo "$Client";
                        endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <table class='table-form'>
                <tr>
                    <td>Setter</td>
                    <td>
                        <?php if ($Invoice_Number == "") : ?>
                            <select class="myinput" id="form_setter" onchange="invoice_outstanding()">
                                <option value=''>Pilih nama Setter</option>
                                <?php
                                $sql =
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
                                        ( pm_user.level = 'admin' or pm_user.level = 'CS' or pm_user.level = 'Setter' or pm_user.level = 'accounting' )
                                    ORDER BY 
                                        pm_user.nama
                                    ";

                                // Perform query
                                $result = $conn_OOP->query($sql);

                                if ($result->num_rows > 0) :
                                    // output data of each row
                                    while ($d = $result->fetch_assoc()) {
                                        if ($_POST['InvoiceList_setter_check'] == "") : $check = "$_SESSION[uid]";
                                        else : $check = "$_POST[InvoiceList_setter_check]";
                                        endif;

                                        if ($d['uid'] == "$check") : $pilih = "selected";
                                        else : $pilih = "";
                                        endif;

                                        echo "<option value='$d[uid]' $pilih>$d[nama] [$d[Qty_OID]]</option>";
                                    }
                                else :

                                endif;
                                ?>
                            </select>
                        <?php else : echo "$Setter";
                        endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="container-fluid">
        <table class='form_table'>
            <tbody>
                <tr>
                    <th width="2%" class="contact100-form-checkbox" style='padding-top:13px;'>
                        <input class="input-checkbox100" id="Check_box" type="checkbox" name="remember" onclick='toggle(this)'>
                        <label class="label-checkbox100" for="Check_box"></label>
                    </th>
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
                    $sql =
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
                            $add_where
                            penjualan.cancel != 'Y' and
                            penjualan.pembayaran != 'lunas'
                        order by
                            penjualan.oid
                        ASC
                        ";
                    $n = 0;

                    // Perform query
                    $result = $conn_OOP->query($sql);

                    if ($result->num_rows > 0) :
                        // output data of each row
                        while ($d = $result->fetch_assoc()) :
                            $n++;
                            if ($d['no_invoice'] != "0") {
                                $checkbox = "checked";
                            } else {
                                $checkbox = "";
                            }
                            $kode_class = str_replace(" ", "_", $d['kode_barang']);
                            echo "
                                    <tr>
                                        <td class='contact100-form-checkbox' style='padding-top:16px;'>
                                            <input class='input-checkbox100' id='cek_$n' type='checkbox' name='option' value='$d[oid]' 
                                            $checkbox>
                                            <label class='label-checkbox100' for='cek_$n'></label>
                                        </td>
                                        <td><center>$d[oid]</Center></td>
                                        <td><span class='KodeProject $kode_class'>" . strtoupper($d['code']) . "</span></td>
                                        <td>$d[description]</td>
                                        <td><center><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></center></td>
                                        <td>$d[bahan]</td>
                                        <td>$d[ukuran]</td>
                                        <td>$d[qty]</td>
                                        <td>$d[Nama_Setter]</td>
                                    </tr>
                                ";
                        endwhile;
                    endif;
                    ?>
                </tr>
            </tbody>
        </table>
        <?php
        if ($Invoice_Number == "") :
        ?>
            <center><input type="button" class="myinput" value="Create Sales Invoice" onclick="submitInvoice('create_invoice')"></center>
        <?php
        else :
        ?>
            <center><input type="button" class="myinput" value="Re-Add Sales Invoice" onclick="submitInvoice('ReAdd_Invoice')"></center>
        <?php
        endif;
        ?>
    </div>

<?php else :
    echo "Not Found 404";
endif
?>

<div id='result'></div>

<?php $conn->close(); ?>