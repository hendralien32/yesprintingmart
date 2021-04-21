<?php
require_once "../../function_new.php";
$tipe = $_POST['tipe'];

?>
<div class='lineBlack'></div>
<?php if($tipe == 'Add_User') : ?>
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
                        <td><input id='username' type='text' autocomplete='off' onkeyup="validasi('username')"> <span id='alert_username'></span></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input id='password' type='password'> <span id='alert_password'></span></td>
                    </tr>
                    <tr>
                        <td>Retype Password</td>
                        <td><input id='retypePassword' type='password'> <span id='alert_retype'></span></td>
                    </tr>
                </table>
            </div>
            <div class='input-right'>
                <table>
                    <tr>
                        <td>Nama</td>
                        <td><input id='nama' type='text' autocomplete='off'> <span id='alert_retype'></span></td>
                    </tr>
                    <tr>
                        <td>Tanggal Masuk</td>
                        <td><input id='tglMasuk' type='date'></td>
                    </tr>
                    <tr>
                        <td>Tanggal Keluar</td>
                        <td><input id='tglKeluar' type='date'></td>
                    </tr>
                </table>
            </div>  
        </div>
        <div class='Table-Content'>
        <table>
            <tr>
                <th>#</th>
                <th>Jenis Halaman</th>
                <th>Halaman</th>
                <th>Akses</th>
                <th>Tambah</th>
                <th>Edit</th>
                <th>Hapus</th>
                <th>Log</th>
                <th>Download</th>
                <th>Img Prev</th>
            </tr>
            <?php
                for ($x = 0; $x < $countRow; $x++) {
                    $nomor = $x + 1;
                    $listPageId = explode("," , $pageId[$x]);
                    $listPageName = explode("," , $pageName[$x]);
                    $rowspan = count($listPageName) + 1;
                    echo "
                        <tbody>
                        <tr>
                            <td rowspan='". $rowspan ."'>$nomor</td>
                            <td rowspan='". $rowspan ."'>$page[$x]</td>
                        </tr>
                    ";
                    for ($i = 0; $i < count($listPageName); $i++) {
                        $namePageName = str_replace(" ", "_", $listPageName[$i]);
                        echo "
                        <tr>
                            <td>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 page_$namePageName' id='Page $namePageName' type='checkbox' onclick=test(\"$namePageName\")>
                                    <label class='label-checkbox100 pageName' data-pageID='$listPageId[$i]' for='Page $namePageName'>$listPageName[$i]</label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 access_$namePageName $namePageName' id='Access $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Access $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 add_$namePageName $namePageName' id='Add $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Add $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 edit_$namePageName $namePageName' id='Edit $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Edit $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 delete_$namePageName $namePageName' id='Delete $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Delete $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 log_$namePageName $namePageName' id='Log $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Log $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 download_$namePageName $namePageName' id='Download $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='Download $namePageName'></label>
                                </div>
                            </td>
                            <td class='center'>
                                <div class='form-checkbox'>
                                    <input class='input-checkbox100 imagePreview_$namePageName $namePageName' id='ImagePreview $namePageName' type='checkbox'>
                                    <label class='label-checkbox100' for='ImagePreview $namePageName'></label>
                                </div>
                            </td>
                        </tr>
                        ";
                    }
                    echo "</tbody>";
                }
            ?>
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