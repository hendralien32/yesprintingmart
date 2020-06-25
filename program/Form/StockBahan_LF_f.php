<?php
session_start();
require_once "../../function.php";


echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<script>
    $(document).ready(function() {
        var i = 1;

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr id="row' + i + '"><td><input type="text" class="form md" id="NamaBahan' + i + '" autocomplete="off" onkeyup="test(\'NamaBahan\',\'' + i + '\')" onChange="validasi(\'NamaBahan\',\'' + i + '\')"><input type="hidden" id="id_NamaBahan' + i + '" class="form sd" name="nama_bahan[]" readonly disabled><input type="hidden" id="validasi_NamaBahan' + i + '" class="form sd" name="validasi_bahan[]" readonly disabled><span id="Alert_ValNamaBahan' + i + '"></span></td><td><center><input class="form sd" type="number" name="panjang[]" id="form_Panjang' + i + '" disabled> x <input class="form sd" type="number" name="lebar[]" id="form_Lebar" autocomplete="off"></center></td><td><center><input class="form sd" type="text" name="qty[]" id="form_Qty" autocomplete="off"> Roll</center></td><td><center><input class="form md" type="number" name="Harga[]" id="Harga" autocomplete="off"></center></td><td class="btn_remove" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></tr>'
            );

        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

<div class="row">
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td style="width:120px">Nama Supplier</td>
                <td>
                    <select id='nama_supplier'>
                        <option value=''>Pilih Supplier</option>
                        <?php
                        $sql =
                            "SELECT
                                supplier.id_supplier,
                                supplier.nama_supplier
                            FROM
                                supplier
                            WHERE
                                supplier.hapus = 'N'
                            ORDER BY
                                supplier.nama_supplier 
                        ";

                        $result = $conn_OOP->query($sql);
                        if ($result->num_rows > 0) :
                            while ($row = $result->fetch_assoc()) :
                                echo "<option value='$row[id_supplier]'>$row[nama_supplier]</option>";
                            endwhile;
                        endif;
                        ?>
                    </select>

                </td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-pelunasan'>
            <tr>
                <td>Tanggal Order</td>
                <td><input type="date" data-placeholder="Tgl. Order" id="tanggal_order" class='form md' value="<?= $date; ?>"></td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm">
        <table class='form_table'>
            <thead>
                <tr>
                    <th width="30%">Nama Bahan</th>
                    <th width="30%">Ukuran</th>
                    <th width="30%">Qty</th>
                    <th width="30%">Harga /Meter</th>
                    <th width="2%">Act</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <tr>
                    <td>
                        <input type="text" class="form md" id="NamaBahan1" autocomplete="off" onkeyup="test('NamaBahan','1')" onChange="validasi('NamaBahan','1')">
                        <input type="hidden" name="nama_bahan[]" id="id_NamaBahan1" class="form sd" readonly disabled>
                        <input type="hidden" name="validasi_bahan[]" id="validasi_NamaBahan1" class="form sd" readonly disabled>
                        <span id="Alert_ValNamaBahan1"></span>
                    </td>
                    <td>
                        <center>
                            <input class="form sd" type="number" name="panjang[]" id="form_Panjang1" disabled> x <input class="form sd" type="number" name="lebar[]" id="form_Lebar" autocomplete="off">
                        </center>
                    </td>
                    <td>
                        <center>
                            <input class="form sd" type="text" name="qty[]" id="form_Qty" autocomplete="off"> Roll
                        </center>
                    </td>
                    <td>
                        <center>
                            <input class="form md" type="number" name="Harga[]" id="Harga" autocomplete="off">
                        </center>
                    </td>
                    <td id="add" class='pointer'>
                        <i class="fad fa-plus-square" name="add"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="submit_menu">
        <button onclick="submit('Insert_StockFlowLF')" id="submitBtn">Buka Order</button>
    </div>
    <div id="Result">

    </div>
</div>