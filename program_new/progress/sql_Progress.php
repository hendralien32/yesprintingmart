<?php
session_start();
require_once "../../function.php";

$typeSubmit = $_POST['typeSubmit'];

if($_POST['typeSubmit'] == "statusFinishedOID") :
    // if ($_POST['status'] == "N") {
    //     $status_Cetak = "selesai";
    //     $log = " <i class=\"far fa-angle-double-right\"></i> Selesai";
    // } else {
    //     $status_Cetak = "";
    //     $log = " Selesai <i class=\"far fa-angle-double-right\"></i> ";
    // }

    // $Final_log = "
    //     <tr>
    //         <td>$hr, $timestamps</td>
    //         <td>" . $_SESSION['username'] . " Cancel data</td>
    //         <td><b>Status</b> : $log  </td>
    //     </tr>
    // ";

    // $sql =
    //     "UPDATE
	// 		penjualan
	// 	set
	// 		status	= '$status_Cetak',
    //         history =  CONCAT('$Final_log', history)
	// 	where
	// 		oid		= '$_POST[oid]'
    // ";
    $sql = "OK";
else :
    $sql = "ERROR";
endif;

if ($conn->multi_query($sql) === TRUE) {
    echo "Records inserted or Update successfully.";
} else {
    if (mysqli_query($conn, $sql)) {
        echo "Records inserted or Update successfully.";
    } else {
        echo "<b class='text-danger'>ERROR: Could not able to execute<br>$typeSubmit == statusFinishedOID<br>" . mysqli_error($conn) . "</br>";
    }
}

// Close connection
$conn->close();

?>