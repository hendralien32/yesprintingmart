<?php
    session_start();
    require_once "../../function.php";

    $ID_Order = $_POST['ID_Order'];

    if($_POST['AksesEdit'] == "cancel_invoice") {    

        echo "
        <h3 class='title_form'>$_POST[judul_form] Sales Invoice No. Invoice : $ID_Order</h3>

        <table class='form_table' style='width:100%'>
            <tr>
                <th width='1%'>#</th>
                <th width='15%'>Client</th>
                <th width='5%'>ID Order</th>
                <th width='4%'>K</th>
                <th width='33%'>Keterangan</th>
                <th width='2%'>Sisi</th>
                <th width='12%'>Bahan</th>
                <th width='11%'>Qty</th>
                <th width='8%'>@ Harga</th>
                <th width='9%'>Total Harga</th>
                </tr>
        ";

        $sql = 
        "SELECT
            penjualan.no_invoice,
            GROUP_CONCAT((CASE
                WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                WHEN penjualan.akses_edit = 'N' THEN 'N'
                ELSE 'N'
            END)) as akses_edit,
            LEFT( penjualan.invoice_date, 10 ) as tanggal,
            customer.nama_client,
            GROUP_CONCAT(penjualan.oid) as oid,
            GROUP_CONCAT(penjualan.description) as description,
            GROUP_CONCAT((CASE
                WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                ELSE ''
            END)) as ukuran,
            GROUP_CONCAT(LEFT(penjualan.kode, 1)) as code,
            GROUP_CONCAT(penjualan.kode) as kode_barang,
            GROUP_CONCAT(penjualan.sisi) as sisi,
            GROUP_CONCAT((CASE
                WHEN penjualan.sisi = '1' THEN 'satu'
                WHEN penjualan.sisi = '2' THEN 'dua'
                ELSE ''
            END)) as css_sisi,
            GROUP_CONCAT((CASE
                WHEN barang.id_barang > 0 THEN barang.nama_barang
                ELSE penjualan.bahan
            END)) as bahan,
            GROUP_CONCAT(CONCAT('<b>',penjualan.qty, '</b> ' ,penjualan.satuan)) as qty,
            GROUP_CONCAT((CASE
                WHEN penjualan.status = 'selesai' THEN 'Y'
                WHEN penjualan.status = '' THEN 'N'
                ELSE ''
            END)) as Finished,
            GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)-penjualan.discount)) as harga_satuan,
            GROUP_CONCAT(((penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_offset+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_design+penjualan.b_indoor+penjualan.b_delivery)*penjualan.qty)) as total,
            penjualan.cancel
        from
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid  
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) barang
        ON
            penjualan.ID_Bahan = barang.id_barang  
        LEFT JOIN 
            (select pm_user.uid, pm_user.nama from pm_user) setter
        ON
            penjualan.setter = setter.uid  
        where
            penjualan.no_invoice = '$ID_Order'
        GROUP BY
            penjualan.no_invoice
        order by
            penjualan.oid
        desc";

        // Perform query
        $result = $conn_OOP -> query($sql);

        if ($result->num_rows > 0) :
            // output data of each row
            while($d = $result->fetch_assoc()) :
                $kode_class=str_replace(" ","_",$d['kode_barang']);
                $oid = explode("," , "$d[oid]");
                $description = explode("," , "$d[description]");
                $ukuran = explode("," , "$d[ukuran]");
                $kode_barang = explode("," , "$kode_class");
                $code = explode("," , "$d[code]");
                $sisi = explode("," , "$d[sisi]");
                $bahan = explode("," , "$d[bahan]");
                $qty = explode("," , "$d[qty]");
                $harga_satuan = explode("," , "$d[harga_satuan]");
                $total = explode("," , "$d[total]");
                $akses_edit = explode("," , "$d[akses_edit]");
                $count_oid = count($oid);
                
                for($i=0;$i<$count_oid ;$i++) :
                    $n = $i+1;
                    echo "
                    <tr>
                        <td>$n</td>
                        <td>$d[nama_client]</td>
                        <td>$oid[$i]</td>
                        <td><span class='KodeProject $kode_barang[$i]'>". strtoupper($code[$i]) ."</span></td>
                        <td>$description[$i] $ukuran[$i]</td>
                        <td>$sisi[$i]</td>
                        <td>$bahan[$i]</td>
                        <td>$qty[$i]</td>
                        <td>". number_format($harga_satuan[$i]) ."</td>
                        <td>". number_format($total[$i]) ."</td>
                    </tr>
                    ";
                endfor;
            endwhile;
        else :

        endif;

        echo "</table><br>";
        $cancel_action = "cancel_invoice";

    } else {
        
        echo "<h3 class='title_form'>$_POST[judul_form] Sales Order No. ID : $ID_Order</h3>";

        $sql = 
            "SELECT 
                penjualan.description,
                (CASE
                    WHEN penjualan.kode = 'large format' THEN 'Large Format'
                    WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                    WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                    WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                    WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                    WHEN penjualan.kode = 'etc' THEN 'ETC'
                    ELSE '- - -'
                END) as kode,
                (CASE
                    WHEN penjualan.panjang > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    WHEN penjualan.lebar > 0 THEN CONCAT(penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                    ELSE penjualan.ukuran
                END) as ukuran,
                CONCAT(penjualan.sisi, ' Sisi') as sisi,
                (CASE
                    WHEN barang.id_barang > 0 THEN barang.nama_barang
                    ELSE penjualan.bahan
                END) as bahan,
                customer.nama_client,
                penjualan.keterangan,
                (CASE
                    WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                    WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                    WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                    WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                    WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                    WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                    WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                    WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                    ELSE '- - -'
                END) as laminating,
                (CASE
                    WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                    WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                    WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                    WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                    WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                    WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                    WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                    ELSE '- - -'
                END) as alat_tambahan,
                CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
                penjualan.potong,
                penjualan.potong_gantung,
                penjualan.pon,
                penjualan.perporasi,
                penjualan.CuttingSticker,
                penjualan.Hekter_Tengah,
                penjualan.Blok,
                penjualan.Spiral,
                penjualan.Proffing,
                penjualan.ditunggu,
                penjualan.Design
            FROM 
                penjualan
            LEFT JOIN 
                (select customer.cid, customer.nama_client from customer) customer
            ON
                penjualan.client = customer.cid   
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) barang
            ON
                penjualan.ID_Bahan = barang.id_barang  
            WHERE
                penjualan.oid = '$ID_Order'
        ";

        // Perform query
        $result = $conn_OOP -> query($sql);

        if ($result->num_rows > 0) :
            // output data of each row
            $row = $result->fetch_assoc();
            
            ?>
            
            <div class="row">
                <div class="col-6">
                    <table class='table-form'>
                        <tr> <td>Kode Barang</td><td><?php echo $row['kode']; ?></td> </tr>
                        <tr> <td>Client</td><td><?php echo ucwords($row['nama_client']); ?></td> </tr>
                        <tr> <td>Deskripsi</td><td><?php echo ucfirst($row['description']); ?></td> </tr>
                        <tr> <td>Ukuran</td><td><?php echo $row['ukuran']; ?></td> </tr>
                        <tr> <td>sisi</td><td><?php echo $row['sisi']; ?></td> </tr>
                        <tr> <td>Bahan</td><td><?php echo $row['bahan']; ?></td> </tr>
                        <tr> <td>Notes / Finishing LF</td><td><?php echo ucfirst($row['keterangan']); ?></td> </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class='table-form'>
                        <tr> <td>Laminating</td><td colspan="3"><?php echo $row['laminating']; ?></td> </tr>
                        <tr> <td>Alat Tambahan</td><td colspan="3"><?php echo $row['alat_tambahan']; ?></td> </tr>
                        <tr>
                            <td>Finishing</td>
                            <?php
                                $array_kode = array(
                                    "potong",
                                    "potong_gantung",
                                    "pon",
                                    "perporasi",
                                    "CuttingSticker",
                                    "Hekter_Tengah",
                                    "Blok",
                                    "Spiral",
                                    "Proffing",
                                    "ditunggu",
                                    "Design"
                                );
                                foreach($array_kode as $kode) {
                                    if($row[$kode]=="Y") {
                                        ${'check_'.$kode} = "<i class='fad fa-check-square'></i>";
                                    } else {
                                        ${'check_'.$kode} = "<i class='fad fa-times-square'></i>";
                                    }
                                }
                            ?>
                            <td>
                                <div class="contact100-form-checkbox">
                                    <?php echo $check_potong; ?>
                                    <label class='checkbox-fa' for='Ptg_Pts'> Ptg Putus </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_potong_gantung; ?>
                                    <label class='checkbox-fa' for='Ptg_Gantung'> Ptg Gantung </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_pon; ?>
                                    <label class='checkbox-fa' for='Pon_Garis'> Pon Garis </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_perporasi; ?>
                                    <label class='checkbox-fa' for='Perporasi'> Perporasi </label>
                                </div>
                            </td>
                            <td colspan="2">
                                <div class="contact100-form-checkbox">
                                    <?php echo $check_CuttingSticker; ?>
                                    <label class='checkbox-fa' for='CuttingSticker'> Cutting Sticker </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_Hekter_Tengah; ?>
                                    <label class='checkbox-fa' for='Hekter_Tengah'> Hekter Tengah </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_Blok; ?>
                                    <label class='checkbox-fa' for='Blok'> Blok </label>
                                </div>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_Spiral; ?>
                                    <label class='checkbox-fa' for='Spiral'> Ring Spiral </label>
                                </div>
                            </td>
                        </tr>
                        <tr> <td>Qty</td><td colspan="3"><?php echo $row['qty']; ?></td> </tr>
                        <tr>
                            <td>Permintaan Order</td>
                            <td>
                                <div class="contact100-form-checkbox">
                                    <?php echo $check_Proffing; ?>
                                    <label class='checkbox-fa' for='proffing'> Proffing</label>
                                </div>
                            </td>
                            <td>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_ditunggu; ?>
                                    <label class='checkbox-fa' for='Ditunggu'> Ditunggu </label>
                                </div>
                            </td>
                            <td>
                                <div class='contact100-form-checkbox'>
                                    <?php echo $check_Design; ?>
                                    <label class='checkbox-fa' for='Design'> Design </label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
    <?php 

        $cancel_action = "Cancel";

        else :

        endif;
    }
    ?>

    <hr>
    <div id="cancel_container">
        <div class="cancel_left">
            <input type="hidden" id="id_order" value="<?php echo $ID_Order; ?>">
            Alasan Cancel
        </div>
        <div class="cancel_right">
            <input type="hidden" id="id_order" value="<?php echo $ID_Order; ?>">
            <textarea id='alasan_cancel' class='form ld' style='width:45vw;'></textarea>
        </div>               
    </div>
    <div class="row">                    
        <div id="submit_menu">
            <button onclick="cancel('<?php echo $cancel_action; ?>')">Cancel Order</button>
        </div>
    </div>
