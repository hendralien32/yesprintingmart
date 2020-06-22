<?php
session_start();
require_once "../../function.php";

if (isset($_POST['ID_Order'])) {
    $status_submit = "update_supplier";
    $nama_submit = "Update Supplier";
    $sql =
        "SELECT
            supplier.id_supplier,
            supplier.nama_supplier,
            supplier.keterangan
        FROM
            supplier
        WHERE
            supplier.id_supplier = '$_POST[ID_Order]' and
            supplier.hapus = 'N'
        ";
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    endif;
} else {
    $status_submit = "submit_supplier";
    $nama_submit = "Submit Supplier";
}

if (isset($row)) {
    $SID = $row['id_supplier'];
    $nama_supplier = $row['nama_supplier'];
    $no_telp = $row['keterangan'];
    $alert = "<b style='color:green'> Nama Client Sama</b>";
} else {
    $SID = "";
    $nama_supplier = "";
    $no_telp = "";
    $alert = "";
}


echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>
<input type='hidden' value='<?= $SID; ?>' id='id_supplier'>
<div class="row">
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td>Nama Supplier</td>
                <td>
                    <input type="text" id="supplier" class='form md' value="<?= $nama_supplier; ?>" autocomplete="off" onkeyup="validasi('supplier')" style='width:150px;'>
                    <input type='hidden' id='validasi_supplier' value="0" class='form sd' disabled>
                    <span id="Alert_Valsupplier"><?= $alert; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td style="vertical-align: top">No. Telepon</td>
                <td><textarea id="no_telp" class='form md' style='width:350px;'><?= $no_telp; ?></textarea></td>
            </tr>
        </table>
    </div>
    <div id="submit_menu">
        <hr>
        <button onclick="submit('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
    </div>
    <div id="Result">

    </div>
</div>

<?php $conn->close(); ?>