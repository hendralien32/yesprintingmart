<?php
session_start();
require_once "../../function.php";

?>

<div class='absensi'>
    <div class='resultQuery'></div>
    <div class='absensiTop'>
        <table>
            <tr>
                <td>Tanggal</td>
                <td><input type="date" name="tanggal" class='tglAbsensi' id="tglAbsensi" value="<?= $date; ?>" max="<?= $date; ?>"></td>
            </tr>
        </table>
    </div>
    <div class='absensiList'>
        
    </div>
    <div class="absensiSubmit">
        <button id='submit' onclick='submitAbsensiHarian()'>Submit</button>
    </div>
</div>