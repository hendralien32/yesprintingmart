<?php
session_start();
require_once "../../function.php";

?>

<div class='absensi'>
    <div class='resultQuery'></div>
    <div class='absensiTop'>
        <table>
            <tr>
                <td>Bulan</td>
                <td><input type="month" id="bulan" value="<?= $months; ?>" max="<?= $months; ?>"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Hari Libur</td>
                <td><input type="text" id="tanggal" value="" placeholder="Tgl1,Tgl2,Tgl3,....."></td>
            </tr>
        </table>
    </div>
    <div class="absensiSubmit">
        <button id='submit' onclick='submitAbsensiHarian()'>Submit</button>
    </div>
</div>