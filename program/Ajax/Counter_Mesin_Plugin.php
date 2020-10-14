<?php
session_start();
require_once "../../function.php";

$mesin_Session = isset($_POST['type_mesin']) ? $_POST['type_mesin'] : $_SESSION['session_MesinDP'];

if ($_POST['date'] != '') :
    $tanggal = $_POST['date'];
else :
    $tanggal = $date;
endif;

$sql =
    "SELECT
        billing_konika.FC_awal,
        billing_konika.BW_awal,
        billing_konika.FC_akhir,
        billing_konika.BW_akhir
    FROM
        billing_konika
    WHERE
        billing_konika.tanggal_billing='$tanggal' and
        billing_konika.mesin = '$mesin_Session'
    LIMIT
        1
";

// Perform query
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    // output data of each row
    $d = $result->fetch_assoc();

    $Counter_Awal_FC = "". number_format($d['FC_awal']) ."";
    $Counter_Awal_BW = "". number_format($d['BW_awal']) ."";
    $Counter_Akhir_BW = "". number_format($d['FC_akhir']) ."";
    $Counter_Akhir_BW = "". number_format($d['BW_akhir']) ."";
else :
    $Counter_Awal_FC = 0;
    $Counter_Awal_BW = 0;
    $Counter_Akhir_BW = 0;
    $Counter_Akhir_BW = 0;
endif;

?>

<strong>Counter Awal : </strong>
    <span style='padding:0.7em 1.5em; color:white; background-color:#f1592a; margin-right:0.8em'><strong><?= $Counter_Awal_FC ?></strong></span>
    <span style='padding:0.7em 1.5em; color:white; background-color:#4d4d4f; margin-right:0.8em''><strong><?= $Counter_Awal_BW ?></strong></span>

    <strong>Counter Akhir : </strong>
    <span style='padding:0.7em 1.5em; color:white; background-color:#f1592a; margin-right:0.8em'><strong><?= $Counter_Akhir_BW ?></strong></span>
    <span style='padding:0.7em 1.5em; color:white; background-color:#4d4d4f; margin-right:0.8em''><strong><?= $Counter_Akhir_BW ?></strong></span>
</div>
