
<div class='lineBlack'></div>

<?php if($_POST['tipe'] == 'Hapus_Absensi') : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class="fas fa-trash-alt"></i>
            <p>Hapus Data Absensi</p>
        </div>
        <div class='Text-Content'>
            Apabila anda Menghapus data maka data akan permanen terhapus dari database anda. Apa anda yakin ?. <br>
            <b>Data yang sudah hapus tidak dapat dikembalikan.</b>
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tidak, Tutup Form</button>
            <button class="yes-btn">Iya, Hapus Data</button>
        </div>
    </div>
<?php else : ?>
    <div class='content'>
        <div class='Title-Content'>
            <i class="far fa-exclamation-circle"></i>
            <p>Data Not Found</p>
        </div>
        <div class='Text-Content'>
            Data Confirm Box tidak ditemukan coba kontak Web developer
        </div>
        <div class='resultError'></div>
        <div class="Btn-Content">
            <button class="no-btn">Tidak, Tutup Form</button>
        </div>
    </div>
<?php endif; ?>


