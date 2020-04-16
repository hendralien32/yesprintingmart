<?php
    session_start();

    if( !isset($_SESSION["login"])) {
        header("location:../");
        exit;
    }

    require '../function.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>YES Program V.5.0</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->

    <link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/program.css">

<!--===============================================================================================-->

    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- jQuery UI library -->
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-ui.min.js"></script>
    
    <script src="js/script.js"></script>
    <link rel="stylesheet" type="text/css" src="css/Font-Awesome-master/css/all.css">
    <script defer src="css/Font-Awesome-master/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="js/moment-with-locales.js"></script>
    <script src="js/moment-timezone-with-data.js"></script>
    
<!--===============================================================================================-->
    
    <script>
        function widget() {
            var now=moment();
            var tanggal=now.lang("id").tz("Asia/Jakarta").format('dddd, Do MMM YYYY');
            var waktu=now.lang("id").tz("Asia/Jakarta").format('[Jam : ]LTS');

            $('#text').html("<b class='tanggal'>" +  tanggal + "</b><br><b class='waktu'>" + waktu + "</b>");
        }

        setInterval(widget, 1000);
    </script>
    
</head>
<body onload="widget()">
    <div id="wrapper">
        <?php
           require 'lightbox.php';
           require 'lightbox_sub.php';
        ?>
        <div id="header">
            <div class="logo">
                <img src='../images/Logo Yes Program.png'>
            </div>
            <div class="header_right">
                <div class="jam">
                    <span id="text">
                </div>
                <div class="user_logout">
                    Hai, <?= $_SESSION["username"]; ?>! <br>
                    <a href="logout.php"><i class="far fa-sign-out"></i> Sign Out !</a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div id="menu">
            <ul>
                <li>Dashboard</li>
                <li>Database</li>
                <a href="?page=SO_YPM&tab=SalesYPM"><li class=''>Penjualan</li></a>
                <li>Penjualan Yescom</li>
                <li>Pelunasan</li>
                <li>Yes WO List</li>
                <li>Laporan</li>
                <li>Large Format</li>
                <li>Digital Printing</li>
                <div class="clear"></div>
            </ul>
        </div>
        <div id="sub_menu">
            <ul>
                <a href="?page=SO_YPM&tab=SalesYPM"><li class=''>Sales Order Yesprintingmart</li></a>
                <a href="?page=SI_YPM&tab=SalesYPM"><li class=''>Sales Invoice Yesprintingmart</li></a>
                <div class="clear"></div>
            </ul>
        </div>

        <div id="content">
            
            <div class="right_content">
            <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                if (isset($page)) {
                    switch ($page) {
                        case 'SO_YPM':       require_once('setter_penjualan.php');             break;
                        case 'SI_YPM':       require_once('invoce_penjualan.php');             break;
                        default:             require_once('test.php');
                    }
                } else {
                    echo "$page";
                }
                
            ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
