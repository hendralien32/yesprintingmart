<?php
session_start();
require_once "../../function.php";

?>

<div class='absensi individu'>
    <div class='resultQuery'></div>
    <div class='absensiTop'>
        <table>
            <tr>
                <td>Tanggal</td>
                <td><input type="date" name="tanggal" class='tglLembur' id="tglLembur" value="<?= $date; ?>" max="<?= $date; ?>"></td>
            </tr>
        </table>
    </div>
    <div class='absensiList'>
        <table>
            <tr>
                <th>Nama Karyawan</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Permisi</th>
                <th>Lembur</th>
                <th width="4%"></th>
            </tr>
            <tbody id='dynamic-field'>
                <tr>
                    <td><input type='text' id='namaKaryawan'><i class='far fa-check'></i></td>
                    <td class='center'><input type='time' id='jamMulai'></td>
                    <td class='center'><input type='time' id='jamSelesai'></td>
                    <td class='center'><input type='checkbox' id='permisi' value='permisi'></td>
                    <td class='center'><input type='checkbox' id='lembur' value='lembur'></td>
                    <td class='center add'><i class='far fa-plus'></i></td>
                </tr>
            </tbody>
            <!-- <tr>
                <td><input type='text' id='namaKaryawan'><i class='far fa-check'></i></td>
                <td class='center'><input type='time' id='jamMulai'></td>
                <td class='center'><input type='time' id='jamSelesai'></td>
                <td class='center'><input type='checkbox' id='permisi' value='permisi'></td>
                <td class='center'><input type='checkbox' id='lembur' value='lembur'></td>
                <td class='center'><i class='far fa-minus'></i></td>
            </tr>
            <tr>
                <td><input type='text' id='namaKaryawan'><i class='far fa-check'></i></td>
                <td class='center'><input type='time' id='jamMulai'></td>
                <td class='center'><input type='time' id='jamSelesai'></td>
                <td class='center'><input type='checkbox' id='permisi' value='permisi'></td>
                <td class='center'><input type='checkbox' id='lembur' value='lembur'></td>
                <td class='center'><i class='far fa-minus'></i></td>
            </tr> -->
        </table>
    </div>
    <div class="absensiSubmit">
        <button id='submit' onclick='submitAbsensiHarian()'>Submit</button>
    </div>
</div>