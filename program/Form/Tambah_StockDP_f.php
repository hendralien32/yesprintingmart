<?php
session_start();
require_once "../../function.php";

if ($_SESSION['level'] == "admin") {
    $disabled = "";
} else {
    $disabled = "disabled";
}

$next_count = 1;

echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<script>
    $(document).ready(function() {
        let i = <?= $next_count ?>;
        let disabled = "<?= $disabled ?>";

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr  id="row' + i + '"><td name="Jmlh_Data"><input type="text" class="form ld" id="BahanDigital' + i + '" autocomplete="off" onkeyup="find_ID(\'BahanDigital\',\'' + i + '\')" onChange="validasi_ID(\'BahanDigital\',\'' + i + '\')"><input type="hidden" name="BahanDigital[]" id="id_BahanDigital' + i + '" class="form md" readonly disabled><input type="hidden" name="validasi_BahanDigital[]" id="validasi_BahanDigital' + i + '" class="form md" readonly disabled><span id="Alert_ValBahanDigital' + i + '"></span></td><td class="a-center"><input type="number" class="form md" id="qty_$n" name="qty[]" min="0"> Lembar</td><td class="a-center"><input type="number" class="form md" id="harga_$n" name="harga[]" min="0"' + disabled + '></td><td class="btn_remove a-center pointer" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></td></tr>'
            );
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>No DO</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="NoDO" autocomplete="off" onkeyup="validasi('NoDO')" onChange="validasi('NoDO')" value=''>
                    <input type="hidden" name="validasi_NoDO" id="validasi_NoDO" class="form sd" readonly disabled>
                    <span id="Alert_ValNoDO"></span>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>Jenis Stock</td>
                <td>
                    <select class="myselect" id="jenis_stock">
                        <?php
                        $array_kode = array(
                            "barang_masuk" => "Masuk",
                            "barang_keluar" => "Keluar"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            echo "<option value='$kode'>$kd</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Tanggal</td>
                <td><input type='date' id='tanggal_ptg' data-placeholder='Tanggal' class='form md' value='<?= $date ?>' max='<?= $date ?>' style='width:96%'></td>
            </tr>
            <tr>
                <td style='width:145px'>Operator</td>
                <td><?= $_SESSION["username"] ?></td>
            </tr>
        </table>
    </div>
</div>
<br>

<div class='row'>
    <div class="col-sm">
        <table class='form_table'>
            <thead>
                <tr>
                    <th width="44%">Nama Bahan</th>
                    <th width="30%">Qty</th>
                    <th width="20%">Harga</th>
                    <th width="5%">Act</th>
                </tr>
            </thead>
            <tbody id="dynamic_field">
                <tr>
                    <td name='Jmlh_Data'>
                        <input type="text" class="form ld" id="BahanDigital<?= $next_count ?>" autocomplete="off" onkeyup="find_ID('BahanDigital','<?= $next_count ?>')" onChange="validasi_ID('BahanDigital','<?= $next_count ?>')" onkeyup="validasi_ID('BahanDigital','<?= $next_count ?>')">
                        <input type="hidden" name="id_BahanDigital[]" id="id_BahanDigital<?= $next_count ?>" class="form sd" readonly disabled>
                        <input type="hidden" name="validasi_BahanDigital[]" id="validasi_BahanDigital<?= $next_count ?>" class="form sd" readonly disabled>
                        <span id="Alert_ValBahanDigital<?= $next_count ?>"></span>
                    </td>
                    <td class="a-center"><input type="number" class="form md" id="qty_$n" name="qty[]" min="0"> Lembar</td>
                    <td class="a-center"><input type="number" class="form md" id="harga_$n" name="harga[]" min="0" <?= $disabled ?>></td>
                    <td class="a-center pointer" id="add">
                        <i class="fad fa-plus-square" name="add"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>