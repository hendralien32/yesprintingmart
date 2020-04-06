<?php
    session_start();
    require_once "../../function.php";
?>

<h3 class='title_form'>FORM SALES INVOICE</h3>

<input type="hidden" id="no_invoice" value="">
<input type="hidden" id="InvoiceList_client_check" value="0">
<input type="hidden" id="InvoiceList_setter_check" value="<?php echo "$_SESSION[uid]"; ?>">
<input type="hidden" id="InvoiceList_Qty_check" value="0">
    <div id="outstandinglist"></div>
    <div id="Result"></div>
</div>