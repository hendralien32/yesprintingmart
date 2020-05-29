<?php
    session_start();
    require '../function.php';

    $dari_tanggal   = $_GET['dari_tgl'];
    $ke_tanggal     = $_GET['ke_tgl'];

    if($dari_tanggal!="" and $ke_tanggal!="") :
        $Add_date="and (LEFT( wo_list.date_create, 10 )>='$dari_tanggal' and LEFT( wo_list.date_create, 10 )<='$ke_tanggal')";
    else :
        $Add_date = "and (LEFT( wo_list.date_create, 10 )='$dari_tanggal')";
    endif;
    
    if($ke_tanggal != "" ) :
        $title = "Daily Report, Priode Tanggal ". date("d M Y",strtotime($dari_tanggal))." - ". date("d M Y",strtotime($ke_tanggal))."";
    else :
        $title = "Daily Report, Tanggal ". date("d M Y",strtotime($dari_tanggal))."";
    endif;

?>

<title> <?= $title; ?></title>
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
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
    <script data-search-pseudo-elements defer src="css/Font-Awesome-master/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    
<!--===============================================================================================-->

<?php

    if(isset($_SESSION["login"])) :
        if($_GET['Status_Print']=="print Wo list daily report") {
            $sql =
                "SELECT
                    test.Tanggal,
                    GROUP_CONCAT(test.kode) as Kode,
                    GROUP_CONCAT(test.wo_color) as wo_color,
                    GROUP_CONCAT(test.Kode_barang) as Kode_barang,
                    GROUP_CONCAT(test.ukuran SEPARATOR '*_*') as ukuran,
                    GROUP_CONCAT(test.id SEPARATOR '*_*') as id,
                    GROUP_CONCAT(test.so SEPARATOR '*_*') as so,
                    GROUP_CONCAT(test.client SEPARATOR '*_*') as client,
                    GROUP_CONCAT(test.project SEPARATOR '*_*') as project,
                    GROUP_CONCAT(test.bahan SEPARATOR '*_*') as bahan,
                    GROUP_CONCAT(test.qty SEPARATOR '*_*') as qty,
                    GROUP_CONCAT(test.satuan SEPARATOR '*_*') as satuan
                FROM
                    (
                        SELECT
                            LEFT(wo_list.date_create,10) as Tanggal,
                            wo_list.wo_color,
                            wo_list.kode,
                            (CASE
                                WHEN wo_list.kode = 'large format' THEN 'Large Format'
                                WHEN wo_list.kode = 'digital' THEN 'Digital Printing A3+'
                                WHEN wo_list.kode = 'indoor' THEN 'Indoor HP Latex'
                                WHEN wo_list.kode = 'Xuli' THEN 'Indoor Xuli'
                                WHEN wo_list.kode = 'etc' THEN 'ETC'
                                ELSE '- - -'
                            END) as Kode_barang,
                            GROUP_CONCAT((CASE
                                WHEN wo_list.panjang > 0 THEN CONCAT(wo_list.panjang, ' X ', wo_list.lebar, ' Cm')
                                WHEN wo_list.lebar > 0 THEN CONCAT(wo_list.panjang, ' X ', wo_list.lebar, ' Cm')
                                ELSE ''
                            END) SEPARATOR ',_') as ukuran,
                            GROUP_CONCAT(wo_list.id SEPARATOR ',_') as id,
                            GROUP_CONCAT(wo_list.so SEPARATOR ',_') as so,
                            GROUP_CONCAT(wo_list.client SEPARATOR ',_') as client,
                            GROUP_CONCAT(wo_list.project SEPARATOR ',_') as project,
                            GROUP_CONCAT((CASE
                                WHEN barang.id_barang > 0 THEN barang.nama_barang
                                ELSE wo_list.bahan
                            END) SEPARATOR ',_') as bahan,
                            GROUP_CONCAT(wo_list.qty SEPARATOR ',_') as qty,
                            GROUP_CONCAT(wo_list.satuan SEPARATOR ',_') as satuan
                        FROM
                            wo_list
                        LEFT JOIN 
                            (select barang.id_barang, barang.nama_barang from barang) barang
                        ON
                            wo_list.ID_Bahan = barang.id_barang 
                        WHERE
                            wo_list.status != 'deleted'
                            $Add_date
                        GROUP BY
                            wo_list.wo_color,
                            wo_list.kode,
                            LEFT(wo_list.date_create,10)
                    ) test
                GROUP BY
                    test.Tanggal
            ";

            $result = $conn_OOP -> query($sql);
            if ($result->num_rows > 0) :
                while ($row = $result->fetch_assoc()) :
                    $Kode           = explode("," , "$row[Kode]");
                    $Kode_barang    = explode("," , "$row[Kode_barang]");
                    $wo_color       = explode("," , "$row[wo_color]");
                    $ukuran         = explode("*_*" , "$row[ukuran]");
                    $id             = explode("*_*" , "$row[id]");
                    $so             = explode("*_*" , "$row[so]");
                    $client         = explode("*_*" , "$row[client]");
                    $project        = explode("*_*" , "$row[project]");
                    $bahan          = explode("*_*" , "$row[bahan]");
                    $qty            = explode("*_*" , "$row[qty]");
                    $satuan         = explode("*_*" , "$row[satuan]");

                    $count_Kode     = count($Kode);
                    $count_id       = count($id);
            ?>
            
            <div id='container'>
                <div id='wo_list_title'>
                    <h3>Daily Report, Tanggal <?= date("d M Y",strtotime($row['Tanggal'])) ?></h3>
                </div>

                <?php
                    for($i=0; $i<$count_Kode ;$i++) {
                        if($Kode[$i]=="large format" || $Kode[$i]=="indoor" || $Kode[$i]=="Xuli") {
                            $table_th = "<th>Ukuran</th>";
                        }  else {
                            $table_th = "";
                        }

                        echo "
                            <div id='wo_list_table'>
                                <h5>$Kode_barang[$i] Warna WO <u>$wo_color[$i]</u></h5>
                                <table>
                                    <tr>
                                        <th style='5%'>ID</th>
                                        <th style='8%'>SO</th>
                                        <th style='47%'>Client + Project</th>
                                        <th style='10%'>Bahan</th>
                                        $table_th
                                        <th style='10%'>Qty A3</th>
                                        <th>PO Cek</th>
                                    </tr>
                                    ";

                                    $test[$i] = explode(",_" , "$id[$i]");

                                    for($n=0; $n<count($test[$i]) ;$n++) {

                                        $X_ukuran    = explode(",_" , "$ukuran[$i]");
                                        $X_id        = explode(",_" , "$id[$i]");
                                        $X_so        = explode(",_" , "$so[$i]");
                                        $X_client    = explode(",_" , "$client[$i]");
                                        $X_project   = explode(",_" , "$project[$i]");
                                        $X_bahan     = explode(",_" , "$bahan[$i]");
                                        $X_qty       = explode(",_" , "$qty[$i]");
                                        $X_satuan    = explode(",_" , "$satuan[$i]");

                                        echo "
                                        <tr>
                                            <td>$X_id[$n]</td>
                                            <td>$X_so[$n]</td>
                                            <td>$X_client[$n] - $X_project[$n]</td>
                                            <td>$X_bahan[$n]</td>";
                                            if($Kode[$i]=="large format" || $Kode[$i]=="indoor" || $Kode[$i]=="Xuli") {
                                                echo "<td>$X_ukuran[$n]</td>";
                                            } else {
                                                echo "";
                                            }

                                            echo "
                                            <td>$X_qty[$n] $X_satuan[$n]</td>
                                            <td></td>
                                        </tr>
                                        ";
                                    }

                                    echo "
                                </table>
                            </div>
                        ";
                    }
                ?>

                <div class='wo_list_info'>
                    <span>
                        <p>
                            <strong>Note YPM : </strong><br>
                            <ul>
                                <li>Bon / Invoice untuk SO dalam list ini harap diterima paling lambat besok pagi.</li>
                                <li>Jika ada satu / dua item Bon / Invoice yang belum memungkinkan dibuka dapat dipending ke hari berikutnya.</li>
                            </ul>
                        </p>
                    </span>
                </div>

                <div class="footer">
                    <div class="column">
                        <span>
                            <p>
                            Disiapkan oleh
                            <br>
                            <br>
                            <br>
                            <br>
                            ___________________
                            </p>
                        </span>
                    </div>
                    <div class="column">
                        <span>
                            <p>
                            Diketahui oleh
                            <br>
                            <br>
                            <br>
                            <br>
                            ___________________
                            </p>
                        </span>
                    </div>
                </div>
                <span class='cut_icon'></span><hr class="line_cut">
            </div>
            <?php
                endwhile;
            endif;
        } else {
            echo "test";
        }
    else :
        header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
        exit();
    endif;
?>