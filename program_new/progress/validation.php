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
        LIMIT
            1
    ";

    $result = $conn_OOP->query($sql)->num_rows;
    echo "$result";
else :
    echo "ERROR";
endif;

// Close connection
$conn->close();
?>