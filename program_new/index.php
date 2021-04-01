<?php
require_once "../function.php";

if (!isset($_SESSION["login"])) {
    header("Location: ../", true, 301);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="../images/icons/favicon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" src="../vendor/fontawesome-pro-master/css/all.css">
    <!--===============================================================================================-->
    
    <title>Hawkbase Ver 5.2</title>
</head>

<body>
    <div class="wrapper">
        <div class="lightbox-input">
            <div class='content-lightbox'></div>
        </div>
        <div class='lightbox'>
        </div>

        <div class="header">
            <div class="logo">
                <img src="../images/Logo Yes Program White.png">
            </div>
            <div class="icon_right">
                <div class="icon">
                    <span class="header-time"></span>
                </div>
                <div class="icon">
                    <i class="far fa-scroll"></i>
                    <span class='notif_number'>13</span>
                    <div class='notif_display display-none'>
                        <table class='table_notif'>

                        </table>
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="icon">
                    <img src="../images/profile.jpg">
                    <p><?= $_SESSION['username'] ?></p>
                </div>
                <div class="icon">
                    <a href="logout.php"><i class="far fa-sign-out"></i></a>
                </div>
            </div>
        </div>

        <div class="menu">
            <ul>
                <a href='?page=dashboard'>
                    <li class='active'>
                        <div class='icon_menu'><i class="fas fa-home-lg-alt"></i></div>
                        <div class='icon_menu'>Dashboard</div>
                    </li>
                </a>
                <?php if($absensi[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-fingerprint"></i></div>
                    <div class='icon_menu'>Absensi</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($absensi); $i++) {
                                if($absensi[$i] === "Y") {
                                    echo "<a href='?page=$listAbsensi[$i]'><li>$listAbsensi[$i]</li></a>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($database[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-database"></i></div>
                    <div class='icon_menu'>Database</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($database); $i++) {
                                if($database[$i] === "Y") {
                                    echo "<a href='?page=$listDb[$i]'><li>$listDb[$i]</li></a>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($SalesOrder[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Penjualan</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($SalesOrder); $i++) {
                                if($SalesOrder[$i] === "Y") {
                                    echo "<li>$listSalesOrder[$i]</li>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($salesOrderYescom[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Penjualan Yescom</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($salesOrderYescom); $i++) {
                                if($salesOrderYescom[$i] === "Y") {
                                    echo "<li>$listSalesOrderYescom[$i] Yescom</li>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($largeFormat[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>LargeFormat</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($largeFormat); $i++) {
                                if($largeFormat[$i] === "Y") {
                                    echo "<li>$listlargeFormat[$i]</li>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($digitalPrinting[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Digital</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($digitalPrinting); $i++) {
                                if($digitalPrinting[$i] === "Y") {
                                    echo "<li>$listdigitalPrinting[$i]</li>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <?php if($laporan[0] == 'Y') : ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Laporan</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <?php
                            for ($i = 1; $i < count($laporan); $i++) {
                                if($laporan[$i] === "Y") {
                                    echo "<li>Laporan $listlaporan[$i]</li>";
                                }
                            }
                        ?>
                    </ul>
                </li>
                <?php endif ?>
                <li>
                    <div class='icon_menu'><i class="fas fa-info-square"></i></div>
                    <div class='icon_menu'>FAQs</div>
                </li>
            </ul>
        </div>

        <div class="container">
            <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                if (isset($page)) :
                    switch ($page):
                        case 'dashboard':
                            require_once('dashboard.php');
                            break;
                        case 'Absensi Harian':
                            require_once('absensi_harian.php');
                            break;
                        case 'Absensi Rekapan':
                            require_once('absensi_rekapan.php');
                            break;
                        case 'Absensi List':
                            require_once('absensi_list.php');
                            break;
                        case 'Database User':
                                require_once('database_user.php');
                                break;
                        default:
                            require_once('dashboard.php');
                    endswitch;
                else :
                    echo "$page";
                endif;
            ?>
        </div>
    <div>

    <!--===============================================================================================-->

    <script data-search-pseudo-elements defer src="../vendor/fontawesome-pro-master/js/all.js"></script>
    <script src="../vendor/moment-with-locales.js"></script>
    <script src="../vendor/moment-timezone-with-data.js"></script>
    <script src="js/script.js"></script>

    <!--===============================================================================================-->

</body>

</html>