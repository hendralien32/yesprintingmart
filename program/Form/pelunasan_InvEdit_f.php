<?php
    session_start();
    require_once "../../function.php";

    $ID_Order = $_POST['ID_Order'];

    $sql = 
        "SELECT 
            penjualan.file_design,
            penjualan.img_design
        FROM 
            penjualan
        WHERE
            penjualan.oid = '$ID_Order'
    ";

    $result = mysqli_query($conn, $sql);
		
	if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);
    }
?>

<h3 class='title_form'>ID Order : <?php echo $_POST['ID_Order']; ?></h3>

<div class="row">
    <div id="image_preview"><img src="../program/design/<?php echo $row['img_design']; ?>"; style="height: 200px;"><br><br>
    <b>Download File : <a href="../program/design/<?php echo $row['file_design']; ?>"><?php echo $row['file_design']; ?> <i class="fas fa-download"></i></a></b></div>
</div>