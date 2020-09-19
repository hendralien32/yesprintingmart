<?php
session_start();
require_once "../../function.php";

$ID_Order = $_POST['ID_Order'];

$sql =
    "SELECT 
            penjualan.posisi_file,
            penjualan.file_design,
            penjualan.img_design
        FROM 
            penjualan
        WHERE
            penjualan.oid = '$ID_Order'
    ";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $url = $row['posisi_file'] . $row['img_design'];
    $image = file_get_contents($url);
    $image_codes = base64_encode($image);
}
?>

<h3 class='title_form'>ID Order : <?php echo $_POST['ID_Order']; ?></h3>

<div class="row">
    <div id="image_preview">
        <image src="data:image/jpg;charset=utf-8;base64,<?= $image_codes; ?>" style="height: 200px;">
            <br><br>
            <b>Download File : <a href='download.php?link=<?= $row['file_design'] ?>&LocationFile=<?= $row['posisi_file'] ?>'><?= $row['file_design'] ?> <i class='fas fa-download pointer'></i></a>
    </div>
</div>