<h3 class='title_form'></h3>

<div id='faq_description'>

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