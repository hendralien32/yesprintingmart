<?php
session_start();
require_once "../../function.php";

$ID_Order = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

if ($_SESSION['level'] == "admin") {
    $disabled = "";
} else {
    $disabled = "disabled";
}

$sql =
    "SELECT
        flow_barang.no_do,
        flow_barang.tanggal,
        GROUP_CONCAT(flow_barang.fid) as fid,
        GROUP_CONCAT(flow_barang.ID_Bahan) as ID_Bahan,
        GROUP_CONCAT((CASE
            WHEN barang.nama_barang != '' THEN barang.nama_barang
            WHEN barang_Kode.nama_barang != '' THEN barang_Kode.nama_barang
            ELSE '- - -'
        END)) as nama_barang,
        GROUP_CONCAT((CASE
            WHEN flow_barang.barang_masuk != '' THEN flow_barang.barang_masuk
            WHEN flow_barang.barang_keluar != '' THEN flow_barang.barang_keluar
            ELSE '0'
        END)) as Qty,
        (CASE
            WHEN flow_barang.barang_masuk != '' THEN 'barang_masuk'
            WHEN flow_barang.barang_keluar != '' THEN 'barang_keluar'
            ELSE 'barang_masuk'
        END) as Jenis_Stock,
        GROUP_CONCAT(flow_barang.harga_barang) as harga_barang
    FROM
        flow_barang
    LEFT JOIN
        (SELECT
            barang.id_barang,
            barang.nama_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = 'KRTS'
        ) barang
    ON 
        barang.id_barang = flow_barang.ID_Bahan
    LEFT JOIN
        (SELECT
            barang.kode_barang,
            barang.nama_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = 'KRTS'
        ) barang_Kode
    ON 
        barang_Kode.kode_barang = flow_barang.kode_barang
    WHERE
        flow_barang.no_do = '$ID_Order' and
        ( flow_barang.hapus = '' or flow_barang.hapus = 'N' ) and
        ( barang.nama_barang != '' or barang_Kode.nama_barang != '' )
    GROUP BY
        flow_barang.no_do
";

$result = $conn_OOP->query($sql);
if ($result->num_rows > 0) :
    $d = $result->fetch_assoc();
    $fid = explode(",", "$d[fid]");
    $no_do = $d['no_do'];
    $tanggal = $d['tanggal'];
    $validasi_NoDO = 0;
    $Jenis_Stock = $d['Jenis_Stock'];
    $ID_Bahan = explode(",", "$d[ID_Bahan]");
    $nama_barang = explode(",", "$d[nama_barang]");
    $Qty = explode(",", "$d[Qty]");
    $harga_barang = explode(",", "$d[harga_barang]");
    $count_ID_Bahan = count($ID_Bahan);
    $next_count = $count_ID_Bahan + 1;
    $alert = "<i class='fad fa-check-double' style='margin-left:10px; margin-right:5px;'></i>";
    $status_submit = "update_stock";
    $nama_submit = "Update Stock";
else :
    $fid = "";
    $no_do = "";
    $tanggal = "$date";
    $validasi_NoDO = "";
    $Jenis_Stock = "";
    $ID_Bahan = "0";
    $nama_barang = "";
    $Qty = "";
    $harga_barang = "";
    $next_count = 1;
    $alert = "";
    $status_submit = "submit_stock";
    $nama_submit = "Submit Stock";
endif;


echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>

<script>
    $(document).ready(function() {
        let i = <?= $next_count ?>;
        let disabled = "<?= $disabled ?>";

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append(
                '<tr  id="row' + i + '"><td name="Jmlh_Data"><input type="hidden" name="fid[]" value="0"><input type="text" class="form ld" id="BahanDigital' + i + '" autocomplete="off" onkeyup="find_ID(\'BahanDigital\',\'' + i + '\')" onChange="validasi_ID(\'BahanDigital\',\'' + i + '\')"><input type="hidden" name="id_BahanDigital[]" id="id_BahanDigital' + i + '" class="form sd" readonly disabled><input type="hidden" name="validasi_BahanDigital[]" id="validasi_BahanDigital' + i + '" class="form md" readonly disabled><span id="Alert_ValBahanDigital' + i + '"></span></td><td class="a-center"><input type="number" class="form md" id="qty_$n" name="qty[]" min="0"> Lembar</td><td class="a-center"><input type="number" class="form md" id="harga_$n" name="harga[]" min="0"' + disabled + '></td><td class="btn_remove a-center pointer" style="vertical-align:middle;" id="' + i + '"><i class="fad fa-minus-square" type="button" name="remove"></i></td></tr>'
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
                    <input type="text" class="form md" style="width:205px" id="NoDO" autocomplete="off" onkeyup="validasi('NoDO')" onChange="validasi('NoDO')" value='<?= $no_do ?>'>
                    <input type="hidden" name="validasi_NoDO" id="validasi_NoDO" class="form sd" value="<?= $validasi_NoDO ?>" readonly disabled>
                    <span id="Alert_ValNoDO"><?= $alert ?></span>
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
                            if ($kode == $Jenis_Stock) : $pilih = "selected";
                            else : $pilih = "";
                            endif;
                            echo "<option value='$kode' $pilih>$kd</option>";
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
                <td><input type='date' id='Tanggal_Stock' data-placeholder='Tanggal' class='form ld' value='<?= $tanggal ?>' max='<?= $date ?>' style='width:100%'></td>
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
                <?php
                if (isset($count_ID_Bahan)) {
                    for ($i = 0; $i < $count_ID_Bahan; $i++) :
                        $n = $i + 1;
                        echo "
                        <tr>
                            <td name='Jmlh_Data'>
                                <input type='hidden' name='fid[]' value='$fid[$i]'>
                                <input type='text' class='form ld' id='BahanDigital$n' value='$nama_barang[$i]' autocomplete='off' onkeyup='find_ID(\"BahanDigital\",\"$n\")' onChange='validasi_ID(\"BahanDigital\",\"$n\")' onkeyup='validasi_ID(\"BahanDigital\",\"$n\")'>
                                <input type='hidden' name='id_BahanDigital[]' value='$ID_Bahan[$i]' id='id_BahanDigital$n' class='form sd' readonly disabled>
                                <input type='hidden' name='validasi_BahanDigital[]' id='validasi_BahanDigital$n' class='form sd' readonly disabled>
                                <span id='Alert_ValBahanDigital$n'></span>
                            </td>
                            <td class='a-center'><input type='number' class='form md' value='$Qty[$i]' id='qty_$n' name='qty[]' min='0'> Lembar</td>
                            <td class='a-center'><input type='number' class='form md' value='$harga_barang[$i]' id='harga_$n' name='harga[]' min='0' $disabled></td>
                            <td class='a-center'><span class='icon_status'><i class='far fa-trash-alt text-danger'></i></span></td>
                        </tr>
                        ";
                    endfor;
                }
                ?>
                <tr>
                    <td name='Jmlh_Data'>
                        <input type='hidden' name='fid[]' value='0'>
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
    <div id="submit_menu">
        <button onClick="submit_stock('<?= $status_submit ?>')" id="submitBtn"><?= $nama_submit ?></button>
    </div>
    <div id="Result">

    </div>
</div>

<?php $conn->close(); ?>