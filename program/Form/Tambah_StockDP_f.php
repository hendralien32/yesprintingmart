<?php
    session_start();
    require_once "../../function.php";

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";
?>


<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:145px'>Kode Bahan</td>
                <td>
                    <input type="text" class="form md" style="width:205px" id="BahanDigital" autocomplete="off" onkeyup="BahanDigital_Search('BahanDigital')" onChange="validasi('BahanDigital')" value=''>
                    <input type="hidden" name="nama_bahan" id="id_BahanDigital" value='' class="form sd" readonly disabled>
                    <input type="hidden" name="validasi_bahan" id="validasi_BahanDigital" class="form sd" readonly disabled>
                    <span id="Alert_ValBahanDigital"></span>
                </td>
            </tr>
            <tr>
                <td style='width:145px'>No DO</td>
                <td><input class='form sd' value='' type="number" id='qty_jalan'></td>
            </tr>
            <tr>
                <td style='width:145px'>Jenis Stock</td>
                <td>
                    <select class="myselect" id="sisi">
                        <?php
                        $array_kode = array(
                            "1" => "Masuk",
                            "2" => "Keluar"
                        );
                        foreach ($array_kode as $kode => $kd) {
                            // if ($kode == "$d[form_sisi]") : $pilih = "selected";
                            // else : $pilih = "";
                            // endif;
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
                <td><input class='form sd' value='' type="number" id='qty_jalan'></td>
            </tr>
        </table>
    </div>
</div>
<br>