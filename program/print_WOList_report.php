<?php
    session_start();
    require '../function.php';

    $dari_tanggal   = $_GET['dari_tgl'];
    $ke_tanggal     = $_GET['ke_tgl'];
    
    if($ke_tanggal != "" ) {
        $title = "Daily Report, Priode Tanggal ". date("d M Y",strtotime($dari_tanggal))." - ". date("d M Y",strtotime($ke_tanggal))."";
    } else {
        $title = "Daily Report, Tanggal ". date("d M Y",strtotime($dari_tanggal))."";
    }

?>

<title> <?= $title; ?></title>
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/print_wolist.css">

<!--===============================================================================================-->

    <script src="js/print.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- jQuery UI library -->
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" type="text/css" src="css/Font-Awesome-master/css/all.css">
    <script defer src="css/Font-Awesome-master/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    
<!--===============================================================================================-->

<?php

    if(isset($_SESSION["login"])) :
        ?>
        <div id='container'>
            <div id='wo_list_title'>
                <h3><?= $title ?></h3>
            </div>
            <div id='wo_list_table'>
                <h5>Digital A3+ Warna WO Kuning</h5>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>SO</th>
                        <th>Client + Project</th>
                        <th>Bahan</th>
                        <th>Qty A3</th>
                        <th>PO Cek</th>
                    </tr>
                    <tr>
                        <td>145214</td>
                        <td>200519018</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec Matte</td>
                        <td>2 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div id='wo_list_table'>
                <h5>Digital A3+ Warna WO Kuning</h5>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>SO</th>
                        <th>Client + Project</th>
                        <th>Bahan</th>
                        <th>Qty A3</th>
                        <th>PO Cek</th>
                    </tr>
                    <tr>
                        <td>145214</td>
                        <td>200519018</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec Matte</td>
                        <td>2 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>145218</td>
                        <td>200520006</td>
                        <td>Cust Cash jefry - Stiker Mangan</td>
                        <td>Stiker PVC Quantec</td>
                        <td>5 Lembar</td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class='wo_list_info'>
                <span>
                    <p>
                        @YPM : <br>
                        <ul>
                            <li>Bon / Invoice untuk SO dalam list ini harap diterima paling lambat besok pagi</li>
                            <li>Jika ada satu / dua item Bon / Invoice yang belum memungkinkan dibuyka dapat dipending ke hari berikutnya</li>
                        </ul>
                    </p>
                </span>
            </div>
        </div>
        <?php
    else :
        header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
        exit();
    endif;
?>