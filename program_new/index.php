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
    <link rel="stylesheet" type="text/css" src="../vendor/fontawesome-pro-master/css/all.css">

    <!--===============================================================================================-->
    <script data-search-pseudo-elements defer src="../vendor/fontawesome-pro-master/js/all.js"></script>
    <script src="../vendor/moment-with-locales.js"></script>
    <script src="../vendor/moment-timezone-with-data.js"></script>
    <script src="../vendor/jquery-3.4.1.min.js"></script>

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
    <title>Hawkbase Ver 5.2</title>
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
                    <a href=""><i class="far fa-sign-out"></i></a>
                </div>
            </div>
        </div>
        <div class="menu">
            <ul>
                <li class='active'>
                    <div class='icon_menu'><i class="fas fa-home-lg-alt"></i></div>
                    <div class='icon_menu'>Dashboard</div>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-database"></i></div>
                    <div class='icon_menu'>Database</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Database User</li>
                        <li>Database Client</li>
                        <li>Database Supplier</li>
                        <li>Database Barang</li>
                        <li>Database Pricelist</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Penjualan</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Sales Order Penjualan</li>
                        <li>Sales Invoice Penjualan</li>
                        <li>Pelunasan Invoice</li>
                        <li>List Pelunasan Invoice</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-shopping-cart"></i></div>
                    <div class='icon_menu'>Penjualan Yescom</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Sales Order Yescom</li>
                        <li>Sales Invoice Yescom</li>
                        <li>WO List Yescom</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-clipboard-list-check"></i></div>
                    <div class='icon_menu'>Large Format</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Large Format Order List</li>
                        <li>Pemotongan Stock Large Format</li>
                        <li>Stock Bahan</li>
                        <li>List Pemesanan Bahan</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-clipboard-list-check"></i></div>
                    <div class='icon_menu'>Digital Printing</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Digital Printing Order List</li>
                        <li>Pemotongan Stock Digital Printing</li>
                        <li>Laporan Harian Konika</li>
                        <li>List Pemasukan Kertas</li>
                        <li>Stock Kertas</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-file-chart-line"></i></div>
                    <div class='icon_menu'>Laporan</div>
                    <div class='icon_menu'><i class="far fa-chevron-down"></i></div>
                    <ul>
                        <li>Laporan Penjualan</li>
                        <li>Laporan Setoran Bank</li>
                        <li>Laporan Harian Konika</li>
                    </ul>
                </li>
                <li>
                    <div class='icon_menu'><i class="fas fa-info-square"></i></div>
                    <div class='icon_menu'>FAQs / Support</div>
                </li>
            </ul>
        </div>
        <div class="content">
            <div class="title">
                <h3>Dashboard</h3>
            </div>
            <div class='dashboard_plugin'>
                <div class='plugin_1'>
                    <div class='title_plugin'><b><i class="fas fa-dollar-sign"></i> Pendapatan Harian</b></div>
                    <div class='content_plugin'>
                        <div class='detail_plugin'>
                            <p>Hari Ini</p>
                            <span class='value'>Rp. 4.000.000</span>
                            <span><b class='positif_value'>12% <i class="fas fa-arrow-up"></i></b> dari hari sebelumnya.</span>
                        </div>
                        <div class='detail_plugin'>
                            <p>Semalam</p>
                            <span class='value'>Rp. 3.000.000</span>
                            <span><b class='positif_value'>12% <i class="fas fa-arrow-up"></i></b> dari hari sebelumnya.</span>
                        </div>
                    </div>
                </div>

                <div class='plugin_1'>
                    <div class='title_plugin'><b><i class="fas fa-dollar-sign"></i> Pendapatan Bulanan</b></div>
                    <div class='content_plugin'>
                        <div class='detail_plugin'>
                            <p>Bulan Ini</p>
                            <span class='value'>Rp. 127.000.000</span>
                            <span><b class='positif_value'>12% <i class="fas fa-arrow-up"></i></b> dari bulan sebelumnya.</span>
                        </div>
                        <div class='detail_plugin'>
                            <p>Bulan Semalam</p>
                            <span class='value'>Rp. 117.000.000</span>
                            <span><b class='positif_value'>12% <i class="fas fa-arrow-up"></i></b> dari bulan sebelumnya.</span>
                        </div>
                    </div>
                </div>

                <div class='plugin_1'>
                    <div class='title_plugin'><b><i class="fas fa-dollar-sign"></i> Target Monthly</b></div>
                    
                </div>
        </div>
    </div>
</body>

</html>