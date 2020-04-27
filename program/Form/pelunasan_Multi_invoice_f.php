<?php
    require_once "../../function.php";

    $client = isset($_POST['ID_Order']) ? $_POST['ID_Order'] : "";

    echo "<h3 class='title_form'>$_POST[judul_form]</h3>";

?>

<input type="hidden" value="<?= $client ?>" id="client">

<div id="outstandinglist"></div>
<div id="Result"></div>