<?php
require_once "../../function_new.php";

if (!isset($_SESSION["login"])) {
    die("Error");
}

$typeValidation = !empty($_POST['typevalidation']) ? $_POST['typevalidation'] : '';

if($typeValidation == 'username') :
    $sql = 
        "SELECT
            pm_user.username
        FROM
            pm_user
        WHERE
            pm_user.username = '$_POST[data]'
    ";

    $result = $conn_OOP->query($sql);
    $jumlah = $result->num_rows;
    echo "$jumlah";
else :
    echo "ERROR";
endif;

// Close connection
$conn->close();
?>