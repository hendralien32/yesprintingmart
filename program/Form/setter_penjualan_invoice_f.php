<?php
    session_start();
    require_once "../../function.php";

    $no_invoice = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

    $sql=
    "SELECT
        penjualan.client,
        penjualan.sales
    FROM
        penjualan
    WHERE
        penjualan.no_invoice = '$no_invoice'
    ";
    $n = 0;

    // Perform query
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        // output data of each row
        $d = $result->fetch_assoc();
        $client = "$d[client]";
        $sales = "$d[sales]";
    else : 
        $client = "0";
        $sales = "$_SESSION[uid]";
    endif;
?>

<h3 class='title_form'>FORM SALES INVOICE</h3>

<input type="hidden" id="no_invoice" value="<?= $no_invoice; ?>">
<input type="hidden" id="InvoiceList_client_check" value="<?= $client; ?>">
<input type="hidden" id="InvoiceList_setter_check" value="<?= $sales; ?>">
<input type="hidden" id="InvoiceList_Qty_check" value="0">
    <div id="outstandinglist"></div>
    <div id="Result"></div>
</div>