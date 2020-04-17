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

    // Perform query
    $result = $conn_OOP -> query($sql);

    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();
    else :

    endif;
?>

<h3 class='title_form'><?= $_POST['judul_form']; ?></h3>

<table class='table_log'>
    <tbody>
    <tr>
        <th>Timestamp</th>
        <th colspan='2'>Massage</th>
    </tr>
    <?php 
        $history = str_replace(["<ul>","</ul>"], "", $row['history']);
        $history_1 = str_replace("<li>", "<tr style='border-right:none'><td colspan='3'>", $history);
        $FINAL_history = str_replace("</li>", "</td></tr>", $history_1);
    
        echo "$FINAL_history";
    ?>
    </tbody>
</table>