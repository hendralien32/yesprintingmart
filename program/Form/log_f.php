<?php

    require_once "../../function.php";

    $ID_Order = $_POST["ID_Order"];

    $sql = 
        "SELECT 
            penjualan.history
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

<h3 class='title_form'><?php echo $_POST['judul_form']; ?></h3>

<table class='table_log'>
    <tbody>
    <tr>
        <th>Timestamp</th>
        <th colspan='2'>Massage</th>
    </tr>
    <?php echo $row['history']; ?>
    </tbody>
</table>