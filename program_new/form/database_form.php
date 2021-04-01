<?php
require_once "../../function.php";
$tipe = $_POST['tipe'];

?>
<div class='lineBlack'></div>
<?php if($_POST['tipe'] == 'Add_User') : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class='far fa-pen-square'></i>
            <p>Add User</p>
        </div>
        <div class='Text-Content'>
            <div class='input-left'>
                <table>
                    <tr>
                        <td>Username</td>
                        <td><input type='text'></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type='text'></td>
                    </tr>
                    <tr>
                        <td>Retype Password</td>
                        <td><input type='text'></td>
                    </tr>
                </table>
            </div>
            <div class='input-right'>
                <table>
                    <tr>
                        <td>Nama</td>
                        <td><input type='text'></td>
                    </tr>
                    <tr>
                        <td>Tanggal Masuk</td>
                        <td><input type='text'></td>
                    </tr>
                    <tr>
                        <td>Tanggal Keluar</td>
                        <td><input type='text'></td>
                    </tr>
                </table>
            </div>  
        </div>
        <div class='Table-Content'>
        <table>
            <tr>
                <th>#</th>
                <th>Halaman</th>
                <th>Akses</th>
                <th>Tambah</th>
                <th>Edit</th>
                <th>Hapus</th>
                <th>Log</th>
                <th>Download</th>
            </tr>
            <tr>
                <td rowspan='4'>1</td>
                <td>Absensi</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Absensi List</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Absensi harian</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Absensi Rekapan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='6'>2</td>
                <td>Database</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Database User</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Database Client</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Database Supplier</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Database Barang</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Database Pricelist</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='5'>3</td>
                <td>Penjualan</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Sales Order</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Sales Invoice</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Pelunasan Invoice</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>List Pelunasan Invoice</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='4'>4</td>
                <td>Penjualan Yescom</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Sales Order Yescom</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Sales Invoice Yescom</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>WO List Yescom</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='5'>5</td>
                <td>Large Format</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Large Format Order List</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Large Format Pemotongan Stock</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Large Format Stock Bahan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Large Format Pemesanan Bahan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='6'>6</td>
                <td>Digital Printing</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Digital Printing Order List</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Digital Printing Pemotongan Stock</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Laporan Harian Mesin</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Digital Printing Pemesanan Bahan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Digital Printing Stock Bahan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>

            <tr>
                <td rowspan='3'>7</td>
                <td>Laporan</td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Laporan Penjualan</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
            <tr>
                <td>Laporan Setoran Bank</td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
                <td><input type="checkbox"></td>
            </tr>
        </table>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
            <button class="yes-btn">Submit Data</button>
        </div>
    </div>
    <?= die(); ?>
<?php else : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class="far fa-exclamation-circle"></i>
            <p>Data Not Found</p>
        </div>
        <div class='Text-Content'>
            Data tidak ditemukan coba kontak Web developer
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tutup Form</button>
        </div>
    </div>
<?php endif; ?>