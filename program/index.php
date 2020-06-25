<?php
session_start();
require_once '../function.php';

if (!isset($_SESSION["login"])) {
    header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
    exit;
}

$sql =
    "SELECT
        pm_user.uid,
        pm_user.nama
    FROM
        pm_user
    WHERE
        pm_user.uid = '$_SESSION[uid]'
    ";
$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    $row = $result->fetch_assoc();
endif;

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$tab = isset($_GET['tab']) ? $_GET['tab'] : 1;

if (isset($page)) :
    switch ($page):
        case 'SO_YPM':
            $title = 'Sales Order Yesprintingmart';
            break;
        case 'SI_YPM':
            $title = 'Sales Invoice Yesprintingmart';
            break;
        case 'Payment_YPM':
            $title = 'Pelunasan Invoice Yesprintingmart';
            break;
        case 'List_Payment_YPM':
            $title = 'List Pelunasan Invoice Yesprintingmart';
            break;
        case 'Client_YPM':
            $title = 'Database Client';
            break;
        case 'User_YPM':
            $title = 'Database User';
            break;
        case 'Pricelist_YPM':
            $title = 'Database Pricelist';
            break;
        case 'Bahan_YPM':
            $title = 'Database Barang';
            break;
        case 'database_pricelist':
            $title = 'Database Pricelist';
            break;
        case 'Wo_List':
            $title = 'Work Order List Yescom';
            break;
        case 'penjualan_YESCOM':
            $title = 'Sales Order Yescom';
            break;
        case 'Supplier_YPM':
            $title = 'Database Supplier';
            break;
        case 'LF_List':
            $title = 'Large Format Order List';
            break;
        case 'Stock_LF':
            $title = 'Stock Large Format';
            break;
        default:
            $title = 'YES Program V.5.0';
    endswitch;
endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->

    <link rel="icon" type="image/png" href="../images/icons/favicon.png" />
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/program.css">

    <!--===============================================================================================-->

    <script src="js/Plugin/jquery-3.4.1.min.js"></script>
    <!-- jQuery UI library -->
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/Plugin/jquery-ui.min.js"></script>

    <script src="js/script.js"></script>
    <link rel="stylesheet" type="text/css" src="css/Font-Awesome-master/css/all.css">
    <script data-search-pseudo-elements defer src="css/Font-Awesome-master/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="js/Plugin/moment-with-locales.js"></script>
    <script src="js/Plugin/moment-timezone-with-data.js"></script>

    <!--===============================================================================================-->

    <script>
        function widget() {
            var now = moment();
            var tanggal = now.lang("id").tz("Asia/Jakarta").format('dddd, Do MMM YYYY');
            var waktu = now.lang("id").tz("Asia/Jakarta").format('[Jam : ]LTS');

            $('#text').html("<b class='tanggal'>" + tanggal + "</b><br><b class='waktu'>" + waktu + "</b>");
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
                    Hai, <?= $row["nama"]; ?>! <br>
                    <a href="logout.php"><i class="far fa-sign-out"></i> Sign Out !</a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div id="menu">
            <ul>
                <a href="../">
                    <li class='<?= ($page == '1') ? 'active' : ''; ?>'>Dashboard</li>
                </a>
                <a href="?page=Client_YPM&tab=DatabaseYPM">
                    <li class='<?= ($tab == 'DatabaseYPM') ? 'active' : ''; ?>'>Database</li>
                    <a href="?page=SO_YPM&tab=SalesYPM">
                        <li class='<?= ($tab == 'SalesYPM') ? 'active' : ''; ?>'>Penjualan</li>
                    </a>
                    <a href="?page=Wo_List&tab=SalesYescom">
                        <li class='<?= ($tab == 'SalesYescom') ? 'active' : ''; ?>'>Penjualan Yescom</li>
                    </a>
                    <li>Laporan</li>
                    <a href="?page=LF_List&tab=Large_Format">
                        <li class='<?= ($tab == 'Large_Format') ? 'active' : ''; ?>'>Large Format</li>
                    </a>
                    <li>Digital Printing</li>
                    <div class="clear"></div>
            </ul>
        </div>
        <div id="sub_menu">
            <?php if ($tab == 'SalesYPM') : ?>
                <ul>
                    <a href="?page=SO_YPM&tab=SalesYPM">
                        <li class='<?= ($page == 'SO_YPM') ? 'active' : ''; ?>'>Sales Order Yesprintingmart</li>
                    </a>
                    <a href="?page=SI_YPM&tab=SalesYPM">
                        <li class='<?= ($page == 'SI_YPM') ? 'active' : ''; ?>'>Sales Invoice Yesprintingmart</li>
                    </a>
                    <a href="?page=Payment_YPM&tab=SalesYPM">
                        <li class='<?= ($page == 'Payment_YPM') ? 'active' : ''; ?>'>Pelunasan Yesprintingmart</li>
                    </a>
                    <a href="?page=List_Payment_YPM&tab=SalesYPM">
                        <li class='<?= ($page == 'List_Payment_YPM') ? 'active' : ''; ?>'>List Pelunasan Yesprintingmart</li>
                    </a>
                    <div class="clear"></div>
                </ul>
            <?php elseif ($tab == 'DatabaseYPM') : ?>
                <ul>
                    <a href="?page=Client_YPM&tab=DatabaseYPM">
                        <li class='<?= ($page == 'Client_YPM') ? 'active' : ''; ?>'>Client Database</li>
                    </a>
                    <a href="?page=Supplier_YPM&tab=DatabaseYPM">
                        <li class='<?= ($page == 'Supplier_YPM') ? 'active' : ''; ?>'>Supplier Database</li>
                    </a>
                    <a href="?page=User_YPM&tab=DatabaseYPM">
                        <li class='<?= ($page == 'User_YPM') ? 'active' : ''; ?>'>User Database</li>
                    </a>
                    <a href="?page=Pricelist_YPM&tab=DatabaseYPM">
                        <li class='<?= ($page == 'Pricelist_YPM') ? 'active' : ''; ?>'>Pricelist Database</li>
                    </a>
                    <a href="?page=Bahan_YPM&tab=DatabaseYPM">
                        <li class='<?= ($page == 'Bahan_YPM') ? 'active' : ''; ?>'>Barang Database</li>
                    </a>
                    <div class="clear"></div>
                </ul>
            <?php elseif ($tab == 'SalesYescom') : ?>
                <ul>
                    <a href="?page=Wo_List&tab=SalesYescom">
                        <li class='<?= ($page == 'Wo_List') ? 'active' : ''; ?>'>WO List Yescom</li>
                    </a>
                    <a href="?page=penjualan_YESCOM&tab=SalesYescom">
                        <li class='<?= ($page == 'penjualan_YESCOM') ? 'active' : ''; ?>'>Sales Order Yescom</li>
                    </a>
                    <a href="?page=SI_YESCOM&tab=SalesYescom">
                        <li class='<?= ($page == 'SI_YESCOM') ? 'active' : ''; ?>'>Sales Invoice Yescom</li>
                    </a>
                    <div class="clear"></div>
                </ul>
            <?php elseif ($tab == 'Large_Format') : ?>
                <ul>
                    <a href="?page=LF_List&tab=Large_Format">
                        <li class='<?= ($page == 'LF_List') ? 'active' : ''; ?>'>Large Format Order List</li>
                    </a>
                    <a href="?page=asd&tab=Large_Format">
                        <li class='<?= ($page == 'asd') ? 'active' : ''; ?>'>Pemotongan Stock LF</li>
                    </a>
                    <a href="?page=Stock_LF&tab=Large_Format">
                        <li class='<?= ($page == 'Stock_LF') ? 'active' : ''; ?>'>Stock Bahan LF</li>
                    </a>
                    <div class="clear"></div>
                </ul>
            <?php endif; ?>
        </div>

        <div id="content">

            <div class="right_content">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                if (isset($page)) :
                    switch ($page):
                        case 'SO_YPM':
                            require_once('setter_penjualan.php');
                            break;
                        case 'SI_YPM':
                            require_once('invoce_penjualan.php');
                            break;
                        case 'Payment_YPM':
                            require_once('pelunasan_penjualan.php');
                            break;
                        case 'List_Payment_YPM':
                            require_once('list_pelunasan_penjualan.php');
                            break;
                        case 'Client_YPM':
                            require_once('database_client.php');
                            break;
                        case 'User_YPM':
                            require_once('database_user.php');
                            break;
                        case 'Pricelist_YPM':
                            require_once('database_pricelist.php');
                            break;
                        case 'Bahan_YPM':
                            require_once('database_bahan.php');
                            break;
                        case 'Wo_List':
                            require_once('WO_List_yescom.php');
                            break;
                        case 'penjualan_YESCOM':
                            require_once('penjualan_yescom.php');
                            break;
                        case 'SI_YESCOM':
                            require_once('invoice_Penjualan_yescom.php');
                            break;
                        case 'LF_List':
                            require_once('LargeFormat_List.php');
                            break;
                        case 'Supplier_YPM':
                            require_once('database_supplier.php');
                            break;
                        case 'Stock_LF':
                            require_once('Stock_LF.php');
                            break;
                        default:
                            require_once('test.php');
                    endswitch;
                else :
                    echo "$page";
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>

</html>