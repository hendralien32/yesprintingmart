<?php
session_start();
require_once "../../function.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}

$typeSubmit = $_POST['typeSubmit'];

if($_POST['typeSubmit'] == "statusFinishedOID") : // Status Finished di OID penjualan
    $log = 
        ($_POST['status'] == "N") 
            ? " <i class=\"far fa-angle-double-right\"></i> Selesai"
            : " Selesai <i class=\"far fa-angle-double-right\"></i> \"-\"";

    $pushlogs = "
        <tr>
            <td>$day_timestamps</td>
            <td>$_SESSION[username] Cancel data</td>
            <td><b>Status</b> : $log </td>
        </tr>
    ";

    $sql =
        "UPDATE
			penjualan
		set
			status	= '$_POST[sSelesai]',
            history =  CONCAT('$pushlogs', history)
		where
			oid		= '$_POST[oid]'
    ";
else :

endif;

if ($conn->multi_query($sql) === TRUE) {
    echo "Y";
} else {
    if (mysqli_query($conn, $sql)) {
        echo "Y";
    } else {
        echo "N";
        // echo "<b class='text-danger'>ERROR: Could not able to execute<br>$sql<br>" . mysqli_error($conn) . "</br>";
    }
}

$conn->close();

?>