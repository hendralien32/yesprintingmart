<?php
    session_start();
    require_once "../../function.php";

    $no_invoice = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";
    $Client_ID = isset($_POST['AksesEdit']) ? $_POST['AksesEdit'] : "";
    

    $sql=
    "SELECT
        count(penjualan.oid) as Jumlah_Order,
        penjualan.client,
        penjualan.sales
    FROM
        penjualan
    WHERE
        penjualan.client = '$Client_ID' and (penjualan.no_invoice = '$no_invoice' or penjualan.no_invoice = '0')
    ";
    $n = 0;

    // Perform query
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        // output data of each row
        $d = $result->fetch_assoc();
        $client = "$d[client]";
        $sales = "$d[sales]";
        $Jumlah_Order = "$d[Jumlah_Order]";
    else : 
        $client = "0";
        $sales = "$_SESSION[uid]";
        $Jumlah_Order = "0";
    endif;
?>

<h3 class='title_form'>FORM SALES INVOICE</h3>

<input type="hidden" id="no_invoice" value="<?= $no_invoice; ?>">
<input type="hidden" id="InvoiceList_client_check" value="<?= $client; ?>">
<input type="hidden" id="InvoiceList_setter_check" value="<?= $sales; ?>">
<input type="hidden" id="InvoiceList_Qty_check" value="<?= $Jumlah_Order; ?>">
    <div id="outstandinglist"></div>
    <div id="Result"></div>
</div>