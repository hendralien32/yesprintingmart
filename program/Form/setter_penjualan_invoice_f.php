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

    if ($result->num_rows >= 1) :
        // output data of each row
        $d = $result->fetch_assoc();
        $client = "$d[client]";
        $Jumlah_Order = "$d[Jumlah_Order]";
    else : 
        $client = "0";
        $Jumlah_Order = "0";
    endif;
?>

<h3 class='title_form'>FORM SALES INVOICE</h3>

<input type="text" id="no_invoice" value="<?= $no_invoice; ?>">
<input type="text" id="InvoiceList_client_check" value="<?= $client; ?>">
<input type="text" id="InvoiceList_setter_check" value="<?= $_SESSION['uid']; ?>">
<input type="text" id="InvoiceList_Qty_check" value="<?= $Jumlah_Order; ?>">
    <div id="outstandinglist"></div>
    <div id="Result"></div>
</div>