<h3 class='title_form'>Cara Step & Repeat file Bleed di Acrobat Menggunakan Quite Imposing</h3>

<div id='faq_description'>
    <ul>
        <li>Buka File yang mau di <b>Step & Repeat</b> di Acrobat</li>
        <li>
            dalam kasus ini File yang mau dibuat itu berukuran <b>A5 ( 14.85 x 21 )</b> yang sudah di kasih <b>Bleed ( Lebih Background )</b> sebanyak <b>2 mm Keliling</b> menjadi <b>15.25 x 21.4 Cm</b><br>
            <img src="../images/FAQ_Images/Img_15.jpg">
        </li>
        <li>
            Pertama-tama kita tambahkan bleedny di file kita dengan plugin <b>Quite Imposing</b> dengan cara click menu <b>Plug-Ins</b><br>
            <img src="../images/FAQ_Images/Img_16.jpg">
        </li>
        <li>
            setelah itu pilih <b>Quite Imposing</b><br>
            <img src="../images/FAQ_Images/Img_17.jpg">
        </li>
        <li>
            setelah itu pilih <b>Define Bleeds...</b><br>
            <img src="../images/FAQ_Images/Img_18.jpg">
        </li>
        <li>
            setelah itu pilih <b>3. Define Bleeds for the four edges separately</b><br>
            <img src="../images/FAQ_Images/Img_19.jpg">
        </li>
        <li>
            di <b>bleed by (Cm)</b> isi dengan bleed yang dibuat dalam kasus ini kita kasih 2mm keliling jadi <b>Left, Right, Top & Buttom</b> di isi <b>0.2 ( 2mm )</b><br>
            <img src="../images/FAQ_Images/Img_20.jpg">
        </li>
        <li>
            di <b>which pages to works on</b> pilih <b>Entire Document ( Kesemua Page )</b><br>
            <img src="../images/FAQ_Images/Img_21.jpg">
        </li>
        <li>
            setelah itu pilih <b>All Pages in Ranges</b><br>
            <img src="../images/FAQ_Images/Img_22.jpg">
        </li>
        <li>
            setelah itu Klik <b>Apply</b><br>
            <img src="../images/FAQ_Images/Img_23.jpg">
        </li>
        <li>
            maka Akan muncul <b>Border Bleed ( Dengan Warna Negatif )</b> ditampilan filenya<br>
            <img src="../images/FAQ_Images/Img_24.jpg">
        </li>
        <li>
            setelah itu Klik <b>Close</b><br>
            <img src="../images/FAQ_Images/Img_25.jpg">
        </li>
        <li>
            selanjutnya kita melakukan step & repeated dengan ke menu <b>Plug-Ins</b><br>
            <img src="../images/FAQ_Images/Img_16.jpg">
        </li>
        <li>
            setelah itu pilih <b>Quite Imposing</b><br>
            <img src="../images/FAQ_Images/Img_17.jpg">
        </li>
        <li>
            setelah itu pilih <b>Step and Repeat...</b><br>
            <img src="../images/FAQ_Images/Img_26.jpg">
        </li>
        <li>
            pada tampilan step and repeat<br>
            <b>1. Check Create a New Document Instead of modifiying this one</b></br>
            <b>2. Pilih Sheet Will not be trimmed</b></br>
            <b>3. Pilih No, place all pages full sized</b></br>
            <b>4. Pilih Next</b></br>
            <img src="../images/FAQ_Images/Img_27.jpg">
        </li>
        <li>
            Maka Akan tampil halaman selanjutnya<br>
            <b>1. Pilih Margin</b></br>
            <b>2. pada Space Between each other Place menjadi 0.4 ( karena kasus kita kasih bleed / Keliling background 2mm jadi total 4 mm / 0.2 Cm )</b></br>
            <b>3. check add crop mark</b></br>
            <b>4. klik custom</b></br>
            <b>5. maka akan muncul tab kecil isi datanya sesuai dengan yang diinginkan</b></br>
            <b>6. Pilih OK</b></br>
            <b>7. Pilih Next</b></br>
            <img src="../images/FAQ_Images/Img_28.jpg">
        </li>
        <li>
            Maka Akan tampil halaman selanjutnya<br>
            <b>1. Pilih ukuran kertas yang mau di cetak</b></br>
            <b>2. Maximum coloums & rows menjadi 0</b></br>
            <b>3. Click Set</b></br>
            <b>4. Align where pilih Centre</b></br>
            <b>5. klik OK</b></br>
            <b>6. pilih shape Sheet itu TALL / WIDE</b></br>
            <b>7. cara menentukan itu tall / wide dengan cara dilihat dari jumlah / Nilai di "pages per sheet", selalu ambil nominal yang paling besar. misalnya Tall Dapat 4 sedangkan Wide dapat 3. maka kita piliah Tall karena nilainya lebih besar</b></br>
            <b>6. klik finished</b></br>
            <img src="../images/FAQ_Images/Img_29.jpg">
        </li>
        <li>
            maka akan muncul Tab <b>Bleed Question</b> pilih <b>used the bleed information to impose . . .</b> </br>
            <img src="../images/FAQ_Images/Img_30.jpg">
        </li>
        <li>
            setelah itu klik <b>OK</b> </br>
            <img src="../images/FAQ_Images/Img_31.jpg">
        </li>
        <li>
            Hasilnya seperti gambar dibawah </br>
            <img src="../images/FAQ_Images/Img_32.jpg">
        </li>
    </ul>
</div>


<?php
session_start();
require_once "../../function.php";

if (isset($_POST['ID_Order'])) {
    $sql =
        "SELECT
        faq.judul,
        faq.deskripsi
    FROM
        faq
    WHERE
        faq.id_FAQ = '$_POST[ID_Order]' and
        faq.hapus = 'N'
    ";
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    endif;
} else {
    $status_submit = "submit_client";
    $nama_submit = "Submit Client";
}
?>

<h3 class='title_form'><?= $row['judul']; ?></h3>

<div id='faq_description'>
    <?= $row['deskripsi']; ?>
</div>