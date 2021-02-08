<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="../images/icons/favicon.png" />

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" src="css/fontawesome-pro-master/css/all.css">

    <!--===============================================================================================-->
    <script data-search-pseudo-elements defer src="css/fontawesome-pro-master/js/all.js"></script>
    <script src="js/vendor/moment-with-locales.js"></script>
    <script src="js/vendor/moment-timezone-with-data.js"></script>
    <script src="js/vendor/jquery-3.4.1.min.js"></script>

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
    <title>Hawkbase V5.2</title>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">
                <img src="../images/Logo Yes Program White.png">
            </div>
            <div class="icon_right">
                <div class="icon">
                    <span id="text"></span>
                </div>
                <div class="icon">
                    <i class="far fa-scroll"></i>
                    <span class='notif_number'>13</span>
                </div>
                <div class="icon">
                    <img src="../images/profile.jpg">
                    <p>Hendra</p>
                </div>
                <div class="icon">
                    <i class="far fa-sign-out"></i>
                </div>
            </div>
        </div>
        <div class="menu">
            <ul>
                <li class='active'><i class="fas fa-home-lg-alt"></i> Dashboard</li>
                <li>
                    <div class='icon'><i class="fas fa-database"></i></div>
                    <div class='icon'>Database</div>
                    <div class='icon'><i class="far fa-chevron-down"></i></div>
                </li>
                <!-- <li><i class="fas fa-shopping-cart"></i> Penjualan <i class="far fa-chevron-down"></i></li>
                <li><i class="fas fa-shopping-cart"></i> Penjualan Yescom <i class="far fa-chevron-down"></i></li>
                <li><i class="fas fa-clipboard-list-check"></i> Large Format <i class="far fa-chevron-down"></i></li>
                <li><i class="fas fa-clipboard-list-check"></i> Digital Printing <i class="far fa-chevron-down"></i></li>
                <li><i class="fas fa-file-chart-line"></i> Laporan <i class="far fa-chevron-down"></i></li>
                <li><i class="fas fa-info-square"></i> FAQs / Support</li> -->
            </ul>
        </div>
        <div class="content">
            test
        </div>
    </div>
</body>

</html>