<?php
require_once "../function_new.php";

if (!isset($_SESSION["login"])) {
    header("Location: ../", true, 301);
    exit;
}

$page_type = $_SESSION["page_type"];
$page_name = $_SESSION["page_name"];
$access_page = $_SESSION["access_page"];
$access_add = $_SESSION["access_add"];
$access_edit = $_SESSION["access_edit"];
$access_delete = $_SESSION["access_delete"];
$access_download = $_SESSION["access_download"];
$access_imagePreview = $_SESSION["access_imagePreview"];

// echo "
// page_type = $page_type<br>
// page_name = $page_name<br>
// access_page = $access_page<br>
// access_add = $access_add<br>
// access_edit = $access_edit<br>
// access_delete = $access_delete<br>
// access_download = $access_download<br>
// access_imagePreview = $access_imagePreview <br><br><br>
// ";

$page = explode("," , $page_type);
$pageName = explode("|" , $page_name);

// Access Role User Account
$pageAccess = explode("|" , $access_page);
$addAccess = explode("|" , $access_add);
$editAccess = explode("|" , $access_edit);
$deleteAccess = explode("|" , $access_delete);
$downloadAccess = explode("|" , $access_download);
$imagePreviewAccess = explode("|" , $access_imagePreview);

// printf()
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
                <?php
                    for ($x = 0; $x < count($page); $x++) {
                        $listPageName = explode("," , $pageName[$x]);
                        $listPageAccess = explode("," , $pageAccess[$x]);
                    
                        $listAddAccess = explode("," , $addAccess[$x]);
                        $listEditAccess = explode("," , $editAccess[$x]);
                        $listDeleteAccess = explode("," , $deleteAccess[$x]);
                        $listDownloadAccess = explode("," , $downloadAccess[$x]);
                        $listImagePreviewAccess = explode("," , $imagePreviewAccess[$x]);
                    
                        $menuAccess = in_array("Y", $listPageAccess);
                    
                        if($menuAccess == 1) {
                            echo "
                                <li>
                                    <div class='icon_menu'><i class='fas fa-fingerprint'></i></div>
                                    <div class='icon_menu'>$page[$x]</div>
                                    <div class='icon_menu'><i class='far fa-chevron-down'></i></div>
                            ";
                            
                            echo "<ul>";
                            for ($i = 0; $i < count($listPageName); $i++) {
                                if($listPageAccess[$i] == "Y") {
                                    echo "<a href='?page=$listPageName[$i]'><li>$listPageName[$i]</li></a>";
                                }
                            }
                            echo "
                                </ul>
                                </li>
                            ";
                        }
                    }
                ?>
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