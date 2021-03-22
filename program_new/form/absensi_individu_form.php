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
                <td><input type="date" name="tanggal" class='tglAbsensi' id="tglAbsensi" value="<?= $date; ?>" max="<?= $date; ?>"></td>
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
                <th>Cuti</th>
                <th width="4%"></th>
            </tr>
            <tbody id='dynamic-field'>
                <tr>
                    <td>
                        <input type='text' data-nomor='1' class='namaKaryawan_1' id='namaKaryawan'>
                        <div class='autocomplete ac_1'>
                            
                        </div>
                        <input type='hidden' data-nomor='1' class='idKaryawan_1' id='idKaryawan' style='width:15%'>
                        <span class='checklist_1'></span>
                    </td>
                    <td class='center'><input data-nomor='1' type='time' id='jamMulai'></td>
                    <td class='center'><input data-nomor='1' type='time' id='jamSelesai'></td>
                    <td class='center'><input data-nomor='1' type='checkbox' id='permisi' value='permisi'></td>
                    <td class='center'><input data-nomor='1' type='checkbox' id='lembur' value='lembur'></td>
                    <td class='center'><input data-nomor='1' type='checkbox' id='cuti' value='cuti'></td>
                    <td class='center add'><i class='far fa-plus'></i></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="absensiSubmit">
        <button id='submit' onclick='submitAbsensiIndividu()'>Submit</button>
    </div>
</div>