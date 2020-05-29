<?php
    session_start();
    require '../function.php';

    $dari_tanggal   = $_GET['dari_tgl'];
    $ke_tanggal     = $_GET['ke_tgl'];
    
    if($_GET['Status_Print']=="print Wo list daily report") :
        $title_add = "Daily Report";

        if($dari_tanggal!="" and $ke_tanggal!="") :
            $Add_date="and (LEFT( wo_list.date_create, 10 )>='$dari_tanggal' and LEFT( wo_list.date_create, 10 )<='$ke_tanggal')";
        else :
            $Add_date = "and (LEFT( wo_list.date_create, 10 )='$dari_tanggal')";
        endif;

    else :
        $title_add = "Yescom Daily Invoice";

        if($dari_tanggal!="" and $ke_tanggal!="") :
            $Add_date="and (LEFT( penjualan.date_create, 10 )>='$dari_tanggal' and LEFT( penjualan.date_create, 10 )<='$ke_tanggal')";
        else :
            $Add_date = "and (LEFT( penjualan.date_create, 10 )='$dari_tanggal')";
        endif;
    endif;

    if($ke_tanggal != "" ) :
        $title = "$title_add, Priode Tanggal ". date("d M Y",strtotime($dari_tanggal))." - ". date("d M Y",strtotime($ke_tanggal))."";
    else :
        $title = "$title_add, Tanggal ". date("d M Y",strtotime($dari_tanggal))."";
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

                <div class="wo_list_footer">
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
                <span class='Wo_list_cut_icon'></span><hr class="Wo_list_line_cut">
            </div>
            <?php
                endwhile;
            else :
                header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
                exit();
            endif;
        } else {
            $sql = 
                "SELECT
                    penjualan_yes.Tanggal,
                    GROUP_CONCAT(penjualan_yes.no_invoice) as no_invoice,
                    GROUP_CONCAT(penjualan_yes.kode) as Kode,
                    GROUP_CONCAT(penjualan_yes.jenis_wo) as wo_color,
                    GROUP_CONCAT(penjualan_yes.Kode_barang) as Kode_barang,
                    GROUP_CONCAT(penjualan_yes.ukuran SEPARATOR '*_*') as ukuran,
                    GROUP_CONCAT(penjualan_yes.id_yes SEPARATOR '*_*') as id,
                    GROUP_CONCAT(penjualan_yes.so_yes SEPARATOR '*_*') as so,
                    GROUP_CONCAT(penjualan_yes.client_yes SEPARATOR '*_*') as client,
                    GROUP_CONCAT(penjualan_yes.description SEPARATOR '*_*') as project,
                    GROUP_CONCAT(penjualan_yes.bahan SEPARATOR '*_*') as bahan,
                    GROUP_CONCAT(penjualan_yes.qty SEPARATOR '*_*') as qty,
                    GROUP_CONCAT(penjualan_yes.satuan SEPARATOR '*_*') as satuan,
                    GROUP_CONCAT(penjualan_yes.harga_satuan SEPARATOR '*_*') as harga_satuan,
                    GROUP_CONCAT(penjualan_yes.total SEPARATOR '*_*') as total,
                    GROUP_CONCAT(penjualan_yes.test) as nilai_Total
                FROM
                    (SELECT
                        LEFT(penjualan.date_create,10) as Tanggal,
                        penjualan.no_invoice,
                        penjualan.jenis_wo,
                        penjualan.kode,
                        (CASE
                            WHEN penjualan.kode = 'large format' THEN 'Large Format'
                            WHEN penjualan.kode = 'digital' THEN 'Digital Printing A3+'
                            WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                            WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                            WHEN penjualan.kode = 'etc' THEN 'ETC'
                            ELSE '- - -'
                        END) as Kode_barang,
                        GROUP_CONCAT((CASE
                            WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                            ELSE ''
                        END) SEPARATOR ',_') as ukuran,
                        GROUP_CONCAT(penjualan.id_yes SEPARATOR ',_') as id_yes,
                        GROUP_CONCAT(penjualan.so_yes SEPARATOR ',_') as so_yes,
                        GROUP_CONCAT(penjualan.client_yes SEPARATOR ',_') as client_yes,
                        GROUP_CONCAT(penjualan.description SEPARATOR ',_') as description,
                        GROUP_CONCAT((CASE
                                WHEN barang.id_barang > 0 THEN barang.nama_barang
                                ELSE penjualan.bahan
                        END) SEPARATOR ',_') as bahan,
                        GROUP_CONCAT(penjualan.qty SEPARATOR ',_') as qty,
                        GROUP_CONCAT(penjualan.satuan SEPARATOR ',_') as satuan,
                        GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount) SEPARATOR ',_') as harga_satuan,
                        GROUP_CONCAT((((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty) SEPARATOR ',_') as total,
                        sum((((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)*penjualan.qty)) as test
                    FROM
                        penjualan
                    LEFT JOIN 
                        (select barang.id_barang, barang.nama_barang from barang) barang
                    ON
                        penjualan.ID_Bahan = barang.id_barang 
                    WHERE
                        penjualan.status != 'deleted' and
                        ( penjualan.no_invoice != '' or penjualan.no_invoice != '0' ) 
                        $Add_date
                    GROUP BY
                        penjualan.no_invoice
                    ) penjualan_yes
                WHERE
                    penjualan_yes.Tanggal
            ";

            $result = $conn_OOP -> query($sql);
            if ($result->num_rows > 0) :
                while ($row = $result->fetch_assoc()) :
                    $no_invoice     = explode("," , "$row[no_invoice]");
                    $Kode           = explode("," , "$row[Kode]");
                    $Kode_barang    = explode("," , "$row[Kode_barang]");
                    $wo_color       = explode("," , "$row[wo_color]");
                    $nilai_Total    = explode("," , "$row[nilai_Total]");
                    $ukuran         = explode("*_*" , "$row[ukuran]");
                    $id             = explode("*_*" , "$row[id]");
                    $so             = explode("*_*" , "$row[so]");
                    $client         = explode("*_*" , "$row[client]");
                    $project        = explode("*_*" , "$row[project]");
                    $bahan          = explode("*_*" , "$row[bahan]");
                    $qty            = explode("*_*" , "$row[qty]");
                    $satuan         = explode("*_*" , "$row[satuan]");
                    $harga_satuan   = explode("*_*" , "$row[harga_satuan]");
                    $total          = explode("*_*" , "$row[total]");

                    $count_Kode     = count($Kode);
                    $count_id       = count($id);
        ?>
            <div id='container'>
                <div id='penjualan_YES_title'>
                    <h3>Daily Invoice, Tanggal <?= date("d M Y",strtotime($row['Tanggal'])) ?></h3>
                </div>

                <?php
                    for($i=0; $i<$count_Kode ;$i++) {
                        if($Kode[$i]=="large format" || $Kode[$i]=="indoor" || $Kode[$i]=="Xuli") {
                            $table_th = "<th>Ukuran</th>";
                        }  else {
                            $table_th = "";
                        }

                        echo "
                            <div id='penjualan_YES_table'>
                                <div class='penjualan_yes_title_Container'>
                                    <div class='content'>
                                        <h5>$Kode_barang[$i] Warna WO <u>$wo_color[$i]</u></h5>
                                    </div>
                                    <div class='content'>
                                        <h5>Nomor Invoice : #$no_invoice[$i]</h5>
                                    </div>
                                </div>
                                <table>
                                    <tr>
                                        <th style='1%'>#</th>
                                        <th style='4%'>ID</th>
                                        <th style='8%'>SO</th>
                                        <th style='37%'>Client + Project</th>
                                        <th style='10%'>Bahan</th>
                                        <th style='10%'>Qty A3</th>
                                        <th style='15%'>Harga @</th>
                                        <th style='15%'>Sub Total</th>
                                    </tr>
                                    ";

                                    $test[$i] = explode(",_" , "$id[$i]");

                                    for($n=0; $n<count($test[$i]) ;$n++) {

                                        $X_ukuran           = explode(",_" , "$ukuran[$i]");
                                        $X_id               = explode(",_" , "$id[$i]");
                                        $X_so               = explode(",_" , "$so[$i]");
                                        $X_client           = explode(",_" , "$client[$i]");
                                        $X_project          = explode(",_" , "$project[$i]");
                                        $X_bahan            = explode(",_" , "$bahan[$i]");
                                        $X_qty              = explode(",_" , "$qty[$i]");
                                        $X_satuan           = explode(",_" , "$satuan[$i]");
                                        $X_harga_satuan     = explode(",_" , "$harga_satuan[$i]");
                                        $X_total            = explode(",_" , "$total[$i]");

                                        if($Kode[$i]=="large format" || $Kode[$i]=="indoor" || $Kode[$i]=="Xuli") {
                                            $detail_Uk = "$X_ukuran[$n]";
                                        } else {
                                            $detail_Uk = "";
                                        }

                                        echo "
                                        <tr>
                                            <td style='width:15px'></td>
                                            <td>$X_id[$n]</td>
                                            <td>$X_so[$n]</td>
                                            <td style='width:425px'>$X_client[$n] - $X_project[$n] $detail_Uk</td>
                                            <td>$X_bahan[$n]</td>
                                            <td>$X_qty[$n] $X_satuan[$n]</td>
                                            <td style='text-align:right'>". number_format($X_harga_satuan[$n]) ."</td>
                                            <td style='text-align:right'>". number_format($X_total[$n]) ."</td>
                                        </tr>
                                        ";
                                        
                                    }

                                    echo "
                                    <tr>
                                        <th colspan='7'>Total</th>
                                        <th style='text-align:right'>". number_format($nilai_Total[$i]) ."</th>
                                    </tr>
                                </table>
                            </div>
                            <span class='penjualan_YES_cut_icon'></span><hr class='penjualan_YES_line_cut'>
                        ";
                    }
                ?>

            </div>
        <?php
                endwhile;
            else :
                echo "Error";
            endif;
        }
    else :
        header("Location: ../vendor/colorlib-error-404-19/index.html", true, 301);
        exit();
    endif;
?>