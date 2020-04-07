<?php
    session_start();
    
    require_once "../../function.php";

    // echo $_FILES['imageFile']['name']."<br>";
    // echo $_FILES['imageFile']['type']."<br>";
    // echo $_FILES['imageFile']['tmp_name']."<br>";
    // echo $_FILES['imageFile']['error']."<br>";
    // echo $_FILES['imageFile']['size']."<br>";

    if($_POST['jenis_submit']=='Insert' or $_POST['jenis_submit']=='Update' or $_POST['jenis_submit']=='Update_SO_Invoice') {
        $Deskripsi      = htmlspecialchars($_POST['Deskripsi'],ENT_QUOTES);
        $Notes          = htmlspecialchars($_POST['Notes'],ENT_QUOTES);
        $Satuan         = htmlspecialchars($_POST['Satuan'],ENT_QUOTES);
        $Nama_Client    = htmlspecialchars($_POST['Nama_Client'],ENT_QUOTES);
        $Nama_Bahan     = htmlspecialchars($_POST['Nama_Bahan'],ENT_QUOTES);
    } else {
        $Deskripsi      = "";
        $Notes          = "";
        $Satuan         = "";
        $Nama_Client    = "";
        $Nama_Bahan     = "";
    }

    if($_POST['jenis_submit']=='Insert') {

        if(is_array($_FILES)) {
            $newFileName = uniqid('YESPRINT-', true);
    
            if(is_uploaded_file($_FILES['DesignFile']['tmp_name'])) { // Design File
                $ekstensiFile = pathinfo($_FILES["DesignFile"]["name"],PATHINFO_EXTENSION);
                $File_DesignName = $newFileName . "." . $ekstensiFile;
    
                $sourcePath = $_FILES['DesignFile']['tmp_name'];
                $targetPath = "../design/".$File_DesignName;
                $ekstensiOk = array('rar','zip');
    
                if(in_array($ekstensiFile, $ekstensiOk) === true){
                    if(move_uploaded_file($sourcePath,$targetPath)) {
                        $mysql_FileName = "file_design,";
                        $mysqlUpdate_FileName = "file_design";
                        $mysql_FileValue = "'".$File_DesignName."',";
    
                        $Log_file = "".$File_DesignName."";
                    } else {
                        die("ERROR");
                    }
                }
            }
    
            if(is_uploaded_file($_FILES['imageFile']['tmp_name'])) {
                $ekstensiFile = pathinfo($_FILES["imageFile"]["name"],PATHINFO_EXTENSION);
                $File_DesignName = $newFileName . "." . $ekstensiFile;
    
                $sourcePath = $_FILES['imageFile']['tmp_name'];
                $targetPath = "../design/".$File_DesignName;
                $ekstensiOk = array('jpg','jpeg','png','gif');
    
                if(in_array($ekstensiFile, $ekstensiOk) === true){
                    if(move_uploaded_file($sourcePath,$targetPath)) {
                        $mysql_ImgName = "img_design,";
                        $mysqlUpdate_ImgName = "img_design";
                        $mysql_ImgValue = "'".$File_DesignName."',";
    
                        $Log_image = "".$File_DesignName."";
                    } else {
                        die("ERROR");
                    }
                }
            }
        }

        $array = array (
            "Kode barang"               => "$_POST[Desc_Kode_Brg]",
            "Nama Client"               => "$Nama_Client",
            "Deskripsi"                 => "$Deskripsi",
            "Ukuran"                    => "$_POST[Ukuran]",
            "Panjang"                   => "$_POST[Panjang]",
            "Lebar"                     => "$_POST[Lebar]",
            "Sisi"                      => "$_POST[Sisi]",
            "Nama Bahan"                => "$Nama_Bahan",
            "Notes / Finishing LF"      => "$Notes",
            "Laminating"                => "$_POST[Desc_Laminating]",
            "Alat Tambahan"             => "$_POST[Desc_alat_tambahan]",
            "Potong Putus"              => "$_POST[Ptg_Pts]",
            "Potong Gantung"            => "$_POST[Ptg_Gantung]",
            "Pon Garis"                 => "$_POST[Pon_Garis]",
            "Perporasi"                 => "$_POST[Perporasi]",
            "Cutting Stiker"            => "$_POST[CuttingSticker]",
            "Hekter Tengah"             => "$_POST[Hekter_Tengah]",
            "Blok"                      => "$_POST[Blok]",
            "Spiral"                    => "$_POST[Spiral]",
            "Qty"                       => "$_POST[Qty]",
            "Satuan"                    => "$Satuan",
            "Proffing"                  => "$_POST[Proffing]",
            "Ditunggu"                  => "$_POST[Ditunggu]",
            "Design"                    => "$_POST[Design]",
            "Akses Edit"                => "Y",
            "Nama File"                 => "$Log_file",
            "Nama Image"                => "$Log_image"
            
        );

        $log ="";

        foreach($array as $key => $value ) {
            if($value!="" && $value!="N") {
                if(is_numeric($value)) {
                    $Input_Value = number_format($value); 
                } else {
                    $Input_Value = "$value";
                }
                $log  .= "<b>$key</b> : $Input_Value<br>";
            } else {
                $log  .= "";
            }
        }

        $Final_log = "
            <tr>
                <td>$timestamps</td>
                <td>". $_SESSION['username'] ." Tambah data</td>
                <td>$log</td>
            </tr>
        ";

        // Attempt insert query execution
        $sql = 
        "INSERT INTO penjualan (
            setter, 
            kode, 
            client,
            description,
            ukuran,
            panjang,
            lebar,
            sisi,
            ID_Bahan,
            keterangan,
            laminate,
            alat_tambahan,
            potong,
            potong_gantung,
            pon,
            perporasi,
            CuttingSticker,
            Hekter_Tengah,
            Blok,
            Spiral,
            qty,
            satuan,
            Proffing,
            ditunggu,
            Design,
            akses_edit,
            $mysql_FileName
            $mysql_ImgName
            history
        ) VALUES (
            '$_POST[ID_User]', 
            '$_POST[Kode_Brg]', 
            '$_POST[ID_Client]',
            '$Deskripsi', 
            '$_POST[Ukuran]', 
            '$_POST[Panjang]', 
            '$_POST[Lebar]', 
            '$_POST[Sisi]', 
            '$_POST[ID_Bahan]', 
            '$Notes', 
            '$_POST[Laminating]', 
            '$_POST[alat_tambahan]', 
            '$_POST[Ptg_Pts]', 
            '$_POST[Ptg_Gantung]', 
            '$_POST[Pon_Garis]', 
            '$_POST[Perporasi]', 
            '$_POST[CuttingSticker]', 
            '$_POST[Hekter_Tengah]', 
            '$_POST[Blok]', 
            '$_POST[Spiral]', 
            '$_POST[Qty]', 
            '$Satuan', 
            '$_POST[Proffing]', 
            '$_POST[Ditunggu]', 
            '$_POST[Design]',
            'Y',
            $mysql_FileValue
            $mysql_ImgValue
            '$Final_log'
        )";

    } if($_POST['jenis_submit']=='Update') {

        $sql =
        "SELECT
            penjualan.description as Deskripsi,
            (CASE
                WHEN penjualan.kode = 'large format' THEN 'Large Format'
                WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                WHEN penjualan.kode = 'etc' THEN 'ETC'
                ELSE '- - -'
            END) as Kode_barang,
            (CASE
                WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                ELSE ''
            END) as Laminating,
            (CASE
                WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                ELSE ''
            END) as Alat_Tambahan,
            customer.nama_client as Nama_Client,
            customer.cid as ID_Client,
            penjualan.ukuran as Ukuran,
            penjualan.panjang as Panjang,
            penjualan.lebar as Lebar,
            penjualan.sisi as Sisi,
            penjualan.ID_Bahan,
            Bahan.nama_barang as Nama_Bahan,
            penjualan.keterangan as Notes,
            penjualan.qty as Qty,
            penjualan.satuan as Satuan,
            penjualan.potong as Potong_Putus,
            penjualan.potong_gantung as Potong_Gantung,
            penjualan.pon as Pon_Garis,
            penjualan.perporasi as Perporasi,
            penjualan.CuttingSticker as Cutting_Stiker,
            penjualan.Hekter_Tengah as Hekter_Tengah,
            penjualan.Blok,
            penjualan.Spiral,
            penjualan.ditunggu as Ditunggu,
            penjualan.Proffing,
            penjualan.Design,
            penjualan.file_design as Nama_File,
            penjualan.img_design as Nama_Image,
            penjualan.b_digital AS Biaya_Digital,
            penjualan.b_kotak AS Biaya_Kotak,
            penjualan.b_lain AS Biaya_Lain,
            penjualan.b_potong AS Biaya_Potong,
            penjualan.b_large AS Biaya_Large,
            penjualan.b_indoor AS Biaya_Indoor,
            penjualan.b_xbanner AS Biaya_Xbanner,
            penjualan.b_offset AS Biaya_Offset,
            penjualan.b_laminate AS Biaya_Laminate,
            penjualan.b_design AS Biaya_Design,
            penjualan.b_delivery AS Biaya_Delivery,
            penjualan.discount as Discount
        FROM
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid  
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) Bahan
        ON
            penjualan.ID_Bahan = Bahan.id_barang  
        WHERE
            penjualan.oid = '$_POST[ID_Order]'
        ";

        $result = mysqli_query($conn, $sql);
        if( mysqli_num_rows($result) === 1 ) {
            $row = mysqli_fetch_assoc($result);

            if(is_array($_FILES)) {

                $target_file = "../design/$row[Nama_File]";
                $target_image = "../design/$row[Nama_Image]";

  
                if(is_uploaded_file($_FILES['DesignFile']['tmp_name'])) { // Design File

                    if(file_exists($target_image)) {
                        unlink($target_image);
                    } else {
                        die("ERROR Hapus Image");
                    }
                    
                    $basename = pathinfo($target_file, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["DesignFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['DesignFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('rar','zip');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_FileValue = "file_design = '".$File_DesignName."',";
        
                            $Log_file = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
        
                if(is_uploaded_file($_FILES['imageFile']['tmp_name'])) {

                    if(file_exists($target_file)) {
                        unlink($target_file);
                    } else {
                        die("ERROR Hapus File");
                    }
                    
                    $basename = pathinfo($target_image, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["imageFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['imageFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('jpg','jpeg','png','gif');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_ImgValue = "img_design = '".$File_DesignName."',";
        
                            $Log_image = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
            }

           if($_POST['Panjang']=="") { $Panjang = "0"; } else { $Panjang = "$_POST[Panjang]"; }
           if($_POST['Lebar']=="") { $Lebar = "0"; } else { $Lebar = "$_POST[Lebar]"; }

            $array = array (
                "Kode_barang"                  => "$_POST[Desc_Kode_Brg]",
                "Nama_Client"                  => "$Nama_Client",
                "Deskripsi"                    => "$Deskripsi",
                "Ukuran"                       => "$_POST[Ukuran]",
                "Panjang"                      => "$Panjang",
                "Lebar"                        => "$Lebar",
                "Sisi"                         => "$_POST[Sisi]",
                "Nama_Bahan"                   => "$Nama_Bahan",
                "Notes"                        => "$Notes",
                "Laminating"                   => "$_POST[Desc_Laminating]",
                "Alat_Tambahan"                => "$_POST[Desc_alat_tambahan]",
                "Potong_Putus"                 => "$_POST[Ptg_Pts]",
                "Potong_Gantung"               => "$_POST[Ptg_Gantung]",
                "Pon_Garis"                    => "$_POST[Pon_Garis]",
                "Perporasi"                    => "$_POST[Perporasi]",
                "Cutting_Stiker"               => "$_POST[CuttingSticker]",
                "Hekter_Tengah"                => "$_POST[Hekter_Tengah]",
                "Blok"                         => "$_POST[Blok]",
                "Spiral"                       => "$_POST[Spiral]",
                "Qty"                          => "$_POST[Qty]",
                "Satuan"                       => "$Satuan",
                "Proffing"                     => "$_POST[Proffing]",
                "Ditunggu"                     => "$_POST[Ditunggu]",
                "Design"                       => "$_POST[Design]",
                "Biaya_Digital"                => "$_POST[b_digital]",
                "Biaya_Kotak"                  => "$_POST[b_kotak]",
                "Biaya_Lain"                   => "$_POST[b_lain]",
                "Biaya_Potong"                 => "$_POST[b_potong]",
                "Biaya_Large"                  => "$_POST[b_large]",
                "Biaya_Indoor"                 => "$_POST[b_indoor]",
                "Biaya_Xbanner"                => "$_POST[b_xbanner]",
                "Biaya_Offset"                 => "$_POST[b_offset]",
                "Biaya_Laminate"               => "$_POST[b_laminate]",
                "Biaya_Design"                 => "$_POST[b_design]",
                "Biaya_Delivery"               => "$_POST[b_delivery]",
                "Discount"                     => "$_POST[discount]"
            );

            $log ="";

            foreach($array as $key => $value ) {
                $a = $row[$key];
                if($value!="$row[$key]") {
                    if(is_numeric($value)) {
                        $Input_Value = number_format($value); 
                    } else {
                        $Input_Value = "$value";
                    }
                    $deskripsi = str_replace("_"," ", $key);
                    $log  .= "<b>$deskripsi</b> : $a <i class=\"far fa-angle-double-right\"></i> $Input_Value<br>";
                } else {
                    $log  .= "";
                }
            }

            if($log != null) {
                $Final_log = "
                    <tr>
                        <td>$timestamps</td>
                        <td>". $_SESSION['username'] ." Mengubah data</td>
                        <td>$log</td>
                    </tr>
                ";  
            } else {
                $Final_log = "";
            }
            
        } else {
            $Final_log = "
                <tr>
                    <td>$timestamps</td>
                    <td>". $_SESSION['username'] ." Mengubah data</td>
                    <td>ERROR Input Logs Data</td>
                </tr>
            ";
        }
        
        $sql = 
        "UPDATE penjualan SET 
            kode             = '$_POST[Kode_Brg]', 
            client           = '$_POST[ID_Client]',
            description      = '$Deskripsi',
            ukuran           = '$_POST[Ukuran]',
            panjang          = '$_POST[Panjang]',
            lebar            = '$_POST[Lebar]',
            sisi             = '$_POST[Sisi]',
            ID_Bahan         = '$_POST[ID_Bahan]',
            keterangan       = '$Notes',
            laminate         = '$_POST[Laminating]',
            alat_tambahan    = '$_POST[alat_tambahan]',
            potong           = '$_POST[Ptg_Pts]',
            potong_gantung   = '$_POST[Ptg_Gantung]',
            pon              = '$_POST[Pon_Garis]',
            perporasi        = '$_POST[Perporasi]',
            CuttingSticker   = '$_POST[CuttingSticker]',
            Hekter_Tengah    = '$_POST[Hekter_Tengah]',
            Blok             = '$_POST[Blok]',
            Spiral           = '$_POST[Spiral]',
            qty              = '$_POST[Qty]',
            satuan           = '$Satuan',
            Proffing         = '$_POST[Proffing]',
            ditunggu         = '$_POST[Ditunggu]',
            Design           = '$_POST[Design]',
            b_digital        = '$_POST[b_digital]',
            b_kotak          = '$_POST[b_kotak]',
            b_lain           = '$_POST[b_lain]',
            b_potong         = '$_POST[b_potong]',
            b_large          = '$_POST[b_large]',
            b_indoor         = '$_POST[b_indoor]',
            b_xbanner        = '$_POST[b_xbanner]',
            b_offset         = '$_POST[b_offset]',
            b_laminate       = '$_POST[b_laminate]',
            b_design         = '$_POST[b_design]',
            b_delivery       = '$_POST[b_delivery]',
            discount         = '$_POST[discount]',
            $mysql_FileValue
            $mysql_ImgValue
            history          =  CONCAT('$Final_log', history)
        WHERE 
            oid = $_POST[ID_Order]
        ;";
        
    } elseif($_POST['jenis_submit']=='cancel_invoice') {
        $Alasan_Cancel     = htmlspecialchars($_POST['Alasan_Cancel'],ENT_QUOTES);

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Cancel data</td>
                <td><b>Cancel</b> : Y<br><b>Alasan Cancel</b> : $Alasan_Cancel</td>
            </tr>
        ";

        // Attempt Update Cancel query execution
        $sql = 
        "UPDATE
            penjualan
        SET
            cancel			= 'Y',
            alasan_cancel   = '$Alasan_Cancel',
            history         =  CONCAT('$Final_log', history)
        WHERE
            no_invoice				= '$_POST[ID_Order]'
        ";
    } elseif($_POST['jenis_submit']=='Cancel') {

        $Alasan_Cancel     = htmlspecialchars($_POST['Alasan_Cancel'],ENT_QUOTES);

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Cancel data</td>
                <td><b>Cancel</b> : Y<br><b>Alasan Cancel</b> : $Alasan_Cancel</td>
            </tr>
        ";

        // Attempt Update Cancel query execution
        $sql = 
        "UPDATE
            penjualan
        SET
            cancel			= 'Y',
            alasan_cancel   = '$Alasan_Cancel',
            history         =  CONCAT('$Final_log', history)
        WHERE
            oid				= '$_POST[ID_Order]'
        ";

    } elseif($_POST['jenis_submit']=='force_paid') {

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." mengubah data</td>
                <td><b>Status Pelunasan</b> : Y<br><i>Sistem Memaksa status pelunasan Sales Invoice menjadi Lunas</i></td>
            </tr>
        ";

        // Attempt Update Cancel query execution
        $sql = 
        "UPDATE
            penjualan
        SET
            pembayaran		= 'lunas',
            history         =  CONCAT('$Final_log', history)
        WHERE
            no_invoice				= '$_POST[ID_Order]'
        ";

    } elseif($_POST['jenis_submit']=='create_invoice') {

        $list_yes = "$_POST[idy]";
	
        $reid = explode("," , "$list_yes");
        foreach($reid as $yes) {
            if($yes!="") { $y[] = "$yes"; }
        }
        $aid = implode("','", $y);
        $fix_yes = "'$aid'";

        //SEARCH INVOICE

        $test = false;
        if(isset($_POST['no_invoice'])){
            $test = $_POST['no_invoice'];
        } else {
            $test = "";
        }

        $q=mysqli_query($conn,"
            select
                no_invoice
            from
                penjualan
            where
                no_invoice != ''
            group by
                no_invoice
            order by
                no_invoice desc
            limit 1
        ");
        $d=mysqli_fetch_array($q);
        
        $q1=mysqli_query($conn,"
            select
                no_invoice, invoice_date
            from
                penjualan
            where
                no_invoice = '$test'
            group by
                no_invoice and invoice_date
            limit 1
        ");
        $d1=mysqli_fetch_array($q1);
        
        $nomor_akhir = "$d[no_invoice]";
        if($nomor_akhir=='') {$noinv = "100001";} else {
            if($test!=null) {
                $noinv = "$test";
                $waktu = "$d1[invoice_date]";
            } else {
                $noinv = $d['no_invoice']+1;
                $waktu = date("Y-m-d H:I:s");
            }
        }


        // SEARCH INVOICE END

        $sql_data = 
            "SELECT
                oid,
                (CASE
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 500 THEN 500_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 250 THEN 250_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 100 THEN 100_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 50 THEN 50_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 20 THEN 20_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 10 THEN 10_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 6 THEN 6sd9_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 3 THEN 3sd5_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 2 THEN 2_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 1 THEN 1_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 20 THEN 20_kotak
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 2 THEN 2sd19_kotak
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 1 THEN 1_kotak
                    ELSE '0'
                END) as b_digital,
                (CASE
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as indoor,
                (CASE
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 500 THEN 500_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 250 THEN 250_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 100 THEN 100_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 50 THEN 50_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 20 THEN 20_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 10 THEN 10_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 6 THEN 6sd9_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 3 THEN 3sd5_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 2 THEN 2_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_kotak,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 500 THEN 500_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 250 THEN 250_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 100 THEN 100_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 50 THEN 50_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 20 THEN 20_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 10 THEN 10_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 2 THEN 2_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '32' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * qty ) / test
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 500 THEN 500_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 250 THEN 250_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 100 THEN 100_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 50 THEN 50_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 20 THEN 20_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 10 THEN 10_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 6 THEN 6sd9_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 3 THEN 3sd5_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 2 THEN 2_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 1 THEN 1_lembar_Cutting + potong
                    ELSE ( potong + potong_gantung + pon + perporasi )
                END) as b_potong,
                (CASE
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN ( laminate = 'kilat1' or laminate = 'kilat2' ) and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN ( laminate = 'kilat1' or laminate = 'kilat2' ) and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN ( laminate = 'doff1' or laminate = 'doff2' ) and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN ( laminate = 'doff1' or laminate = 'doff2' ) and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'hard_lemit' THEN 10000
                    WHEN laminate = 'laminating_floor' and ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) THEN ( 40000 * qty ) / test
                    WHEN laminate = 'laminating_floor' and kode = 'digital' THEN 10000
                    WHEN ( laminate = 'kilatdingin1' or laminate = 'doffdingin1' ) and kode = 'digital' and satuan = 'lembar' THEN 5000
                    ELSE '0'
                END) as b_laminate
            FROM 
                (
                SELECT
                    penjualan.oid,
                    penjualan.kode,
                    penjualan.ID_Bahan,
                    barang.nama_barang,
                    penjualan.sisi,
                    penjualan.laminate,
                    penjualan.qty AS test,
                    barang.qty,
                    pricelist.1_lembar,
                    pricelist.2_lembar,
                    pricelist.3sd5_lembar,
                    pricelist.6sd9_lembar,
                    pricelist.10_lembar,
                    pricelist.20_lembar,
                    pricelist.50_lembar,
                    pricelist.100_lembar,
                    pricelist.250_lembar,
                    pricelist.500_lembar,
                    pricelist1.1_lembar AS 1_lembar_AT,
                    pricelist1.2_lembar AS 2_lembar_AT,
                    pricelist1.3sd5_lembar AS 3sd5_lembar_AT,
                    pricelist1.6sd9_lembar AS 6sd9_lembar_AT,
                    pricelist1.10_lembar AS 10_lembar_AT,
                    pricelist1.20_lembar AS 20_lembar_AT,
                    pricelist1.50_lembar AS 50_lembar_AT,
                    pricelist1.100_lembar AS 100_lembar_AT,
                    pricelist1.250_lembar AS 250_lembar_AT,
                    pricelist1.500_lembar AS 500_lembar_AT,
                    Pricelist_Cutting.1_lembar AS 1_lembar_Cutting,
                    Pricelist_Cutting.2_lembar AS 2_lembar_Cutting,
                    Pricelist_Cutting.3sd5_lembar AS 3sd5_lembar_Cutting,
                    Pricelist_Cutting.6sd9_lembar AS 6sd9_lembar_Cutting,
                    Pricelist_Cutting.10_lembar AS 10_lembar_Cutting,
                    Pricelist_Cutting.20_lembar AS 20_lembar_Cutting,
                    Pricelist_Cutting.50_lembar AS 50_lembar_Cutting,
                    Pricelist_Cutting.100_lembar AS 100_lembar_Cutting,
                    Pricelist_Cutting.250_lembar AS 250_lembar_Cutting,
                    Pricelist_Cutting.500_lembar AS 500_lembar_Cutting,
                    Pricelist_Cutting.1sd2m AS 1sd2m_Cutting,
                    Pricelist_Cutting.3sd9m AS 3sd9m_Cutting,
                    Pricelist_Cutting.10m AS 10m_Cutting,
                    Pricelist_Cutting.50m AS 50m_Cutting,
                    pricelist.1sd2m,
                    pricelist.3sd9m,
                    pricelist.10m,
                    pricelist.50m,
                    pricelist.20_kotak,
                    pricelist.2sd19_kotak,
                    pricelist.1_kotak,
                    barang.ID_AT,
                    barang.ID_Cutting,
                    (CASE
                        WHEN potong = 'Y' and satuan = 'lembar' THEN '500'
                        WHEN potong = 'Y' and satuan = 'kotak' THEN '2000'
                        ELSE '0'
                    END) as potong,
                    (CASE
                        WHEN potong_gantung = 'Y' THEN '500'
                        ELSE '0'
                    END) as potong_gantung,
                    (CASE
                        WHEN pon = 'Y' THEN '500'
                        ELSE '0'
                    END) as pon,
                    (CASE
                        WHEN perporasi = 'Y' THEN '500'
                        ELSE '0'
                    END) as perporasi,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN 'meter'
                        ELSE LOWER(penjualan.satuan) 
                    END) AS satuan,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT((((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
                        ELSE FORMAT(penjualan.qty,0)
                    END) AS qty_order,
                    leminating_kilat,
                    leminating_doff
                FROM
                    penjualan
                LEFT JOIN 
                    (SELECT 
                        barang.id_barang,
                        barang.nama_barang,
                        total_qty.qty,
                        total_qty.sisi,
                        total_qty.satuan as Satuan_Order,
                        total_qty.ID_AT,
                        total_qty.ID_Cutting,
                        total_laminate.leminating_kilat,
                        total_laminate.leminating_doff
                    FROM
                        barang
                    LEFT JOIN
                        (SELECT
                            penjualan.kode,
                            penjualan.ID_Bahan,
                            penjualan.sisi,
                            penjualan.satuan,
                            (CASE
                                WHEN penjualan.alat_tambahan = 'KotakNC' THEN '31'
                                WHEN penjualan.alat_tambahan = 'Ybanner' THEN '32'
                                WHEN penjualan.alat_tambahan = 'RU_60' THEN '65'
                                WHEN penjualan.alat_tambahan = 'RU_80' THEN '66'
                                WHEN penjualan.alat_tambahan = 'RU_85' THEN '67'
                                WHEN penjualan.alat_tambahan = 'Tripod' THEN '68'
                                ELSE '0'
                            END) as ID_AT,
                            (CASE
                                WHEN penjualan.CuttingSticker = 'Y' THEN '71'
                                ELSE '0'
                            END) as ID_Cutting,
                            (CASE
                                WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT(sum(((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
                                ELSE FORMAT(sum(penjualan.qty),0)
                            END) AS Qty
                        FROM
                            penjualan
                        WHERE
                            penjualan.oid IN ('$aid')
                        GROUP BY
                            penjualan.ID_Bahan, penjualan.sisi, penjualan.satuan
                        ) total_qty
                    ON
                        barang.id_barang = total_qty.ID_Bahan
                    LEFT JOIN
                        (SELECT
                            penjualan.ID_Bahan,
                            SUM(CASE 
                                WHEN penjualan.laminate = 'kilat1' and penjualan.satuan = 'lembar' THEN penjualan.qty*1
                                WHEN penjualan.laminate = 'kilat2' and penjualan.satuan = 'lembar' THEN penjualan.qty*2
                                WHEN penjualan.laminate = 'kilat1' and penjualan.satuan = 'kotak' THEN penjualan.qty*4
                                WHEN penjualan.laminate = 'kilat2' and penjualan.satuan = 'kotak' THEN penjualan.qty*8
                                ELSE 0 
                            END) AS leminating_kilat,
                            SUM(CASE 
                                WHEN penjualan.laminate = 'doff1' and penjualan.satuan = 'lembar' THEN penjualan.qty*1
                                WHEN penjualan.laminate = 'doff2' and penjualan.satuan = 'lembar' THEN penjualan.qty*2
                                WHEN penjualan.laminate = 'doff1' and penjualan.satuan = 'kotak' THEN penjualan.qty*4
                                WHEN penjualan.laminate = 'doff2' and penjualan.satuan = 'kotak' THEN penjualan.qty*8
                                ELSE 0 
                            END) AS leminating_doff
                        FROM
                            penjualan
                        WHERE
                            penjualan.oid IN ('$aid')
                        GROUP BY
                            penjualan.ID_Bahan
                        ) total_laminate
                    ON
                        barang.id_barang = total_laminate.ID_Bahan
                    ) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang
                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar,
                        pricelist.1sd2m,
                        pricelist.3sd9m,
                        pricelist.10m,
                        pricelist.50m,
                        pricelist.20_kotak,
                        pricelist.2sd19_kotak,
                        pricelist.1_kotak
                    FROM 
                        pricelist
                    ) pricelist
                ON
                    penjualan.sisi = pricelist.sisi and penjualan.ID_Bahan = pricelist.bahan and penjualan.kode = pricelist.jenis 

                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar
                    FROM 
                        pricelist
                    ) pricelist1
                ON
                    barang.ID_AT = pricelist1.bahan

                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar,
                        pricelist.1sd2m,
                        pricelist.3sd9m,
                        pricelist.10m,
                        pricelist.50m
                    FROM 
                        pricelist
                    ) Pricelist_Cutting
                ON
                    barang.ID_Cutting = Pricelist_Cutting.bahan and penjualan.kode = Pricelist_Cutting.jenis 

                WHERE
                    penjualan.oid IN ('$aid') and
                    penjualan.ID_Bahan = barang.id_barang and
                    penjualan.sisi = barang.sisi and
                    penjualan.satuan = barang.Satuan_Order
                ) table_invoice
                ORDER BY
                   oid
                ASC
        ";// OK WORKING FINE

        $data = mysqli_query($conn, $sql_data);
        while( $row = mysqli_fetch_assoc( $data)){
            $new_array[$row['oid']] = array( 
                'oid' => $row['oid'], 
                'b_digital' => $row['b_digital'], 
                'b_lf' => $row['b_lf'],
                'indoor' => $row['indoor'],
                'b_potong' => $row['b_potong'],
                'b_kotak' => $row['b_kotak'],
                'b_AlatTambahan' => $row['b_AlatTambahan'],
                'b_laminate' => $row['b_laminate']
            );
        }

        $b_digital = "";
        $b_lf = "";
        $indoor = "";
        $b_potong = "";
        $b_kotak = "";
        $b_AlatTambahan = "";
        $b_laminate = "";
        $no_invoice = "";
        $invoice_date = "";

        foreach($new_array as $array) {       
            $b_digital .= "when oid = $array[oid] then '$array[b_digital]'";
            $b_lf .= "when oid = $array[oid] then '$array[b_lf]'";
            $indoor .= "when oid = $array[oid] then '$array[indoor]'";
            $b_potong .= "when oid = $array[oid] then '$array[b_potong]'";
            $b_kotak .= "when oid = $array[oid] then '$array[b_kotak]'";
            $b_AlatTambahan .= "when oid = $array[oid] then '$array[b_AlatTambahan]'";
            $b_laminate .= "when oid = $array[oid] then '$array[b_laminate]'";
            $no_invoice .= "when oid = $array[oid] then '$noinv'";
            $invoice_date .= "when oid = $array[oid] then '$waktu'";
        }

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Tambah Data</td>
                <td><b>No Invoice</b> :  #$noinv</td>
            </tr>
        ";

        $sql = 
        "UPDATE penjualan
            SET no_invoice = (CASE 
                                $no_invoice
                            END),
                invoice_date = (CASE 
                                $invoice_date
                            END),
                b_digital = (CASE 
                                $b_digital
                            END),
                b_large = (CASE 
                                $b_lf
                            END),
                b_kotak = (CASE 
                                $b_kotak
                            END),
                b_laminate = (CASE 
                                $b_laminate
                            END),
                b_potong = (CASE 
                                $b_potong
                            END),
                b_indoor = (CASE 
                                $indoor
                            END),
                b_xbanner = (CASE 
                                $b_AlatTambahan
                            END),
                history   =  CONCAT('$Final_log', history)
            WHERE oid IN ('$aid');
        ";
    } elseif($_POST['jenis_submit']=='Update_SO_Invoice' and $_POST['Auto_Calc']=='Y') { 
        $sql_Data_OID =
        "SELECT
            penjualan.oid,
            penjualan.description as Deskripsi,
            (CASE
                WHEN penjualan.kode = 'large format' THEN 'Large Format'
                WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                WHEN penjualan.kode = 'etc' THEN 'ETC'
                ELSE '- - -'
            END) as Kode_barang,
            (CASE
                WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                ELSE ''
            END) as Laminating,
            (CASE
                WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                ELSE ''
            END) as Alat_Tambahan,
            customer.nama_client as Nama_Client,
            customer.cid as ID_Client,
            penjualan.ukuran as Ukuran,
            penjualan.panjang as Panjang,
            penjualan.lebar as Lebar,
            penjualan.sisi as Sisi,
            penjualan.ID_Bahan,
            Bahan.nama_barang as Nama_Bahan,
            penjualan.keterangan as Notes,
            penjualan.qty as Qty,
            penjualan.satuan as Satuan,
            penjualan.potong as Potong_Putus,
            penjualan.potong_gantung as Potong_Gantung,
            penjualan.pon as Pon_Garis,
            penjualan.perporasi as Perporasi,
            penjualan.CuttingSticker as Cutting_Stiker,
            penjualan.Hekter_Tengah as Hekter_Tengah,
            penjualan.Blok,
            penjualan.Spiral,
            penjualan.ditunggu as Ditunggu,
            penjualan.Proffing,
            penjualan.Design,
            penjualan.file_design as Nama_File,
            penjualan.img_design as Nama_Image,
            penjualan.b_digital AS Biaya_Digital,
            penjualan.b_kotak AS Biaya_Kotak,
            penjualan.b_lain AS Biaya_Lain,
            penjualan.b_potong AS Biaya_Potong,
            penjualan.b_large AS Biaya_Large,
            penjualan.b_indoor AS Biaya_Indoor,
            penjualan.b_xbanner AS Biaya_Xbanner,
            penjualan.b_offset AS Biaya_Offset,
            penjualan.b_laminate AS Biaya_Laminate,
            penjualan.b_design AS Biaya_Design,
            penjualan.b_delivery AS Biaya_Delivery,
            penjualan.discount as Discount
        FROM
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid  
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) Bahan
        ON
            penjualan.ID_Bahan = Bahan.id_barang  
        WHERE
            penjualan.oid = '$_POST[ID_Order]'
        ";

        $result = mysqli_query($conn, $sql_Data_OID);
        if( mysqli_num_rows($result) === 1 ) {
            $row = mysqli_fetch_assoc($result);

            if(is_array($_FILES)) {

                $target_file = "../design/$row[Nama_File]";
                $target_image = "../design/$row[Nama_Image]";

  
                if(is_uploaded_file($_FILES['DesignFile']['tmp_name'])) { // Design File

                    if(file_exists($target_image)) {
                        unlink($target_image);
                    } else {
                        die("ERROR Hapus Image");
                    }
                    
                    $basename = pathinfo($target_file, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["DesignFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['DesignFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('rar','zip');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_FileValue = "file_design = '".$File_DesignName."',";
        
                            $Log_file = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
        
                if(is_uploaded_file($_FILES['imageFile']['tmp_name'])) {

                    if(file_exists($target_file)) {
                        unlink($target_file);
                    } else {
                        die("ERROR Hapus File");
                    }
                    
                    $basename = pathinfo($target_image, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["imageFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['imageFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('jpg','jpeg','png','gif');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_ImgValue = "img_design = '".$File_DesignName."',";
        
                            $Log_image = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
            }

            if($_POST['Panjang'] == "") { $Panjang = "0"; } else { $Panjang = "$_POST[Panjang]"; }
            if($_POST['Lebar'] == "") { $Lebar = "0"; } else { $Lebar = "$_POST[Lebar]"; }
           
            if($_POST['b_lain'] == "undefined" or $_POST['b_lain'] == "" ) { $b_lain = "0"; } else { $b_lain = "$_POST[b_lain]"; }
            if($_POST['b_offset'] == "undefined" or $_POST['b_offset'] == "" ) { $b_offset = "0"; } else { $b_offset = "$_POST[b_offset]"; }
            if($_POST['b_laminate'] == "undefined" or $_POST['b_laminate'] == "" ) { $b_laminate = "0"; } else { $b_laminate = "$_POST[b_laminate]"; }
            if($_POST['b_design'] == "undefined" or $_POST['b_design'] == "" ) { $b_design = "0"; } else { $b_design = "$_POST[b_design]"; }
            if($_POST['b_delivery'] == "undefined" or $_POST['b_delivery'] == "" ) { $b_delivery = "0"; } else { $b_delivery = "$_POST[b_delivery]"; }

            $array = array (
                "Kode_barang"                  => "$_POST[Desc_Kode_Brg]",
                "Nama_Client"                  => "$Nama_Client",
                "Deskripsi"                    => "$Deskripsi",
                "Ukuran"                       => "$_POST[Ukuran]",
                "Panjang"                      => "$Panjang",
                "Lebar"                        => "$Lebar",
                "Sisi"                         => "$_POST[Sisi]",
                "Nama_Bahan"                   => "$Nama_Bahan",
                "Notes"                        => "$Notes",
                "Laminating"                   => "$_POST[Desc_Laminating]",
                "Alat_Tambahan"                => "$_POST[Desc_alat_tambahan]",
                "Potong_Putus"                 => "$_POST[Ptg_Pts]",
                "Potong_Gantung"               => "$_POST[Ptg_Gantung]",
                "Pon_Garis"                    => "$_POST[Pon_Garis]",
                "Perporasi"                    => "$_POST[Perporasi]",
                "Cutting_Stiker"               => "$_POST[CuttingSticker]",
                "Hekter_Tengah"                => "$_POST[Hekter_Tengah]",
                "Blok"                         => "$_POST[Blok]",
                "Spiral"                       => "$_POST[Spiral]",
                "Qty"                          => "$_POST[Qty]",
                "Satuan"                       => "$Satuan",
                "Proffing"                     => "$_POST[Proffing]",
                "Ditunggu"                     => "$_POST[Ditunggu]",
                "Design"                       => "$_POST[Design]",
                "Biaya_Lain"                   => "$b_lain",
                "Biaya_Offset"                 => "$b_offset",
                "Biaya_Design"                 => "$b_design",
                "Biaya_Delivery"               => "$b_delivery",
                "Discount"                     => "$_POST[discount]"
            );

            $log ="";

            foreach($array as $key => $value ) {
                $a = $row[$key];
                if($value!="$row[$key]") {
                    if(is_numeric($value)) {
                        $Input_Value = number_format($value); 
                    } else {
                        $Input_Value = "$value";
                    }
                    $deskripsi = str_replace("_"," ", $key);
                    $log  .= "<b>$deskripsi</b> : $a <i class=\"far fa-angle-double-right\"></i> $Input_Value<br>";
                } else {
                    $log  .= "";
                }
            }

            if($log != null) {
                $Final_log = "
                    <tr>
                        <td>$timestamps</td>
                        <td>". $_SESSION['username'] ." Mengubah data</td>
                        <td>$log</td>
                    </tr>
                ";
                if($_POST['inv_check']=="Y") {
                    if($_POST['akses_edit']=="Y") { $akses_edit = "N"; } else { $akses_edit = "$_POST[akses_edit]"; } /* new update */
                } else {
                    $akses_edit = "$_POST[akses_edit]";
                }
            } else {
                $Final_log = "";
                $akses_edit = "$_POST[akses_edit]"; /* new update */
            }
            
        } else {
            $Final_log = "
                <tr>
                    <td>$timestamps</td>
                    <td>". $_SESSION['username'] ." Mengubah data</td>
                    <td>ERROR Input Logs Data</td>
                </tr>
            ";
            $akses_edit = "$_POST[akses_edit]"; /* new update */
        }
        
        $test = 
        "UPDATE penjualan SET 
            kode             = '$_POST[Kode_Brg]', 
            client           = '$_POST[ID_Client]',
            description      = '$Deskripsi',
            ukuran           = '$_POST[Ukuran]',
            panjang          = '$_POST[Panjang]',
            lebar            = '$_POST[Lebar]',
            sisi             = '$_POST[Sisi]',
            ID_Bahan         = '$_POST[ID_Bahan]',
            keterangan       = '$Notes',
            laminate         = '$_POST[Laminating]',
            alat_tambahan    = '$_POST[alat_tambahan]',
            potong           = '$_POST[Ptg_Pts]',
            potong_gantung   = '$_POST[Ptg_Gantung]',
            pon              = '$_POST[Pon_Garis]',
            perporasi        = '$_POST[Perporasi]',
            CuttingSticker   = '$_POST[CuttingSticker]',
            Hekter_Tengah    = '$_POST[Hekter_Tengah]',
            Blok             = '$_POST[Blok]',
            Spiral           = '$_POST[Spiral]',
            qty              = '$_POST[Qty]',
            satuan           = '$Satuan',
            Proffing         = '$_POST[Proffing]',
            ditunggu         = '$_POST[Ditunggu]',
            Design           = '$_POST[Design]',
            b_offset         = '$_POST[b_offset]',
            b_design         = '$_POST[b_design]',
            b_delivery       = '$_POST[b_delivery]',
            discount         = '$_POST[discount]',
            akses_edit         = '$akses_edit',
            $mysql_FileValue
            $mysql_ImgValue
            history          =  CONCAT('$Final_log', history)
        WHERE 
            oid = $_POST[ID_Order]
        ";

        mysqli_query($conn, $test);

        $sql_data = 
            "SELECT
                oid,
                (CASE
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 500 THEN 500_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 250 THEN 250_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 100 THEN 100_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 50 THEN 50_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 20 THEN 20_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 10 THEN 10_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 6 THEN 6sd9_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 3 THEN 3sd5_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 2 THEN 2_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'lembar' and qty >= 1 THEN 1_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 20 THEN 20_kotak
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 2 THEN 2sd19_kotak
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and satuan = 'kotak' and qty >= 1 THEN 1_kotak
                    ELSE '0'
                END) as b_digital,
                (CASE
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * qty ) / test
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as indoor,
                (CASE
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 500 THEN 500_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 250 THEN 250_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 100 THEN 100_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 50 THEN 50_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 20 THEN 20_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 10 THEN 10_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 6 THEN 6sd9_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 3 THEN 3sd5_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 2 THEN 2_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and qty >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_kotak,
                (CASE
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 500 THEN 500_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 250 THEN 250_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 100 THEN 100_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 50 THEN 50_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 20 THEN 20_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 10 THEN 10_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 2 THEN 2_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '32' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * qty ) / test
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 500 THEN 500_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 250 THEN 250_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 100 THEN 100_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 50 THEN 50_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 20 THEN 20_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 10 THEN 10_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 6 THEN 6sd9_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 3 THEN 3sd5_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 2 THEN 2_lembar_Cutting + potong
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 1 THEN 1_lembar_Cutting + potong
                    ELSE ( potong + potong_gantung + pon + perporasi )
                END) as b_potong,
                (CASE
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN ( laminate = 'kilat1' or laminate = 'kilat2' ) and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN ( laminate = 'kilat1' or laminate = 'kilat2' ) and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN ( laminate = 'doff1' or laminate = 'doff2' ) and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN ( laminate = 'doff1' or laminate = 'doff2' ) and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'hard_lemit' THEN 10000
                    WHEN laminate = 'laminating_floor' and ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) THEN ( 40000 * qty ) / test
                    WHEN laminate = 'laminating_floor' and kode = 'digital' THEN 6300
                    WHEN ( laminate = 'kilatdingin1' or laminate = 'doffdingin1' ) and kode = 'digital' and satuan = 'lembar' THEN 5000
                    ELSE '0'
                END) as b_laminate
            FROM 
                (
                SELECT
                    penjualan.oid,
                    penjualan.kode,
                    penjualan.ID_Bahan,
                    barang.nama_barang,
                    penjualan.sisi,
                    penjualan.laminate,
                    penjualan.qty AS test,
                    barang.qty,
                    pricelist.1_lembar,
                    pricelist.2_lembar,
                    pricelist.3sd5_lembar,
                    pricelist.6sd9_lembar,
                    pricelist.10_lembar,
                    pricelist.20_lembar,
                    pricelist.50_lembar,
                    pricelist.100_lembar,
                    pricelist.250_lembar,
                    pricelist.500_lembar,
                    pricelist1.1_lembar AS 1_lembar_AT,
                    pricelist1.2_lembar AS 2_lembar_AT,
                    pricelist1.3sd5_lembar AS 3sd5_lembar_AT,
                    pricelist1.6sd9_lembar AS 6sd9_lembar_AT,
                    pricelist1.10_lembar AS 10_lembar_AT,
                    pricelist1.20_lembar AS 20_lembar_AT,
                    pricelist1.50_lembar AS 50_lembar_AT,
                    pricelist1.100_lembar AS 100_lembar_AT,
                    pricelist1.250_lembar AS 250_lembar_AT,
                    pricelist1.500_lembar AS 500_lembar_AT,
                    Pricelist_Cutting.1_lembar AS 1_lembar_Cutting,
                    Pricelist_Cutting.2_lembar AS 2_lembar_Cutting,
                    Pricelist_Cutting.3sd5_lembar AS 3sd5_lembar_Cutting,
                    Pricelist_Cutting.6sd9_lembar AS 6sd9_lembar_Cutting,
                    Pricelist_Cutting.10_lembar AS 10_lembar_Cutting,
                    Pricelist_Cutting.20_lembar AS 20_lembar_Cutting,
                    Pricelist_Cutting.50_lembar AS 50_lembar_Cutting,
                    Pricelist_Cutting.100_lembar AS 100_lembar_Cutting,
                    Pricelist_Cutting.250_lembar AS 250_lembar_Cutting,
                    Pricelist_Cutting.500_lembar AS 500_lembar_Cutting,
                    Pricelist_Cutting.1sd2m AS 1sd2m_Cutting,
                    Pricelist_Cutting.3sd9m AS 3sd9m_Cutting,
                    Pricelist_Cutting.10m AS 10m_Cutting,
                    Pricelist_Cutting.50m AS 50m_Cutting,
                    pricelist.1sd2m,
                    pricelist.3sd9m,
                    pricelist.10m,
                    pricelist.50m,
                    pricelist.20_kotak,
                    pricelist.2sd19_kotak,
                    pricelist.1_kotak,
                    barang.ID_AT,
                    barang.ID_Cutting,
                    (CASE
                        WHEN potong = 'Y' and satuan = 'lembar' THEN '500'
                        WHEN potong = 'Y' and satuan = 'kotak' THEN '2000'
                        ELSE '0'
                    END) as potong,
                    (CASE
                        WHEN potong_gantung = 'Y' THEN '500'
                        ELSE '0'
                    END) as potong_gantung,
                    (CASE
                        WHEN pon = 'Y' THEN '500'
                        ELSE '0'
                    END) as pon,
                    (CASE
                        WHEN perporasi = 'Y' THEN '500'
                        ELSE '0'
                    END) as perporasi,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN 'meter'
                        ELSE LOWER(penjualan.satuan) 
                    END) AS satuan,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT((((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
                        ELSE FORMAT(penjualan.qty,0)
                    END) AS qty_order,
                    leminating_kilat,
                    leminating_doff
                FROM
                    penjualan
                LEFT JOIN 
                    (SELECT 
                        barang.id_barang,
                        barang.nama_barang,
                        total_qty.qty,
                        total_qty.sisi,
                        total_qty.satuan as Satuan_Order,
                        total_qty.ID_AT,
                        total_qty.ID_Cutting,
                        total_laminate.leminating_kilat,
                        total_laminate.leminating_doff
                    FROM
                        barang
                    LEFT JOIN
                        (SELECT
                            penjualan.kode,
                            penjualan.ID_Bahan,
                            penjualan.sisi,
                            penjualan.satuan,
                            (CASE
                                WHEN penjualan.alat_tambahan = 'KotakNC' THEN '31'
                                WHEN penjualan.alat_tambahan = 'Ybanner' THEN '32'
                                WHEN penjualan.alat_tambahan = 'RU_60' THEN '65'
                                WHEN penjualan.alat_tambahan = 'RU_80' THEN '66'
                                WHEN penjualan.alat_tambahan = 'RU_85' THEN '67'
                                WHEN penjualan.alat_tambahan = 'Tripod' THEN '68'
                                ELSE '0'
                            END) as ID_AT,
                            (CASE
                                WHEN penjualan.CuttingSticker = 'Y' THEN '71'
                                ELSE '0'
                            END) as ID_Cutting,
                            (CASE
                                WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT(sum(((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
                                ELSE FORMAT(sum(penjualan.qty),0)
                            END) AS Qty
                        FROM
                            penjualan
                        WHERE
                            penjualan.no_invoice = $_POST[no_invoice]
                        GROUP BY
                            penjualan.ID_Bahan, penjualan.sisi, penjualan.satuan
                        ) total_qty
                    ON
                        barang.id_barang = total_qty.ID_Bahan
                        LEFT JOIN
                        (SELECT
                            penjualan.ID_Bahan,
                            SUM(CASE 
                                WHEN penjualan.laminate = 'kilat1' and penjualan.satuan = 'lembar' THEN penjualan.qty*1
                                WHEN penjualan.laminate = 'kilat2' and penjualan.satuan = 'lembar' THEN penjualan.qty*2
                                WHEN penjualan.laminate = 'kilat1' and penjualan.satuan = 'kotak' THEN penjualan.qty*4
                                WHEN penjualan.laminate = 'kilat2' and penjualan.satuan = 'kotak' THEN penjualan.qty*8
                                ELSE 0 
                            END) AS leminating_kilat,
                            SUM(CASE 
                                WHEN penjualan.laminate = 'doff1' and penjualan.satuan = 'lembar' THEN penjualan.qty*1
                                WHEN penjualan.laminate = 'doff2' and penjualan.satuan = 'lembar' THEN penjualan.qty*2
                                WHEN penjualan.laminate = 'doff1' and penjualan.satuan = 'kotak' THEN penjualan.qty*4
                                WHEN penjualan.laminate = 'doff2' and penjualan.satuan = 'kotak' THEN penjualan.qty*8
                                ELSE 0 
                            END) AS leminating_doff
                        FROM
                            penjualan
                        WHERE
                            penjualan.no_invoice = $_POST[no_invoice]
                        GROUP BY
                            penjualan.ID_Bahan
                        ) total_laminate
                    ON
                        barang.id_barang = total_laminate.ID_Bahan
                    ) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang
                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar,
                        pricelist.1sd2m,
                        pricelist.3sd9m,
                        pricelist.10m,
                        pricelist.50m,
                        pricelist.20_kotak,
                        pricelist.2sd19_kotak,
                        pricelist.1_kotak
                    FROM 
                        pricelist
                    ) pricelist
                ON
                    penjualan.sisi = pricelist.sisi and penjualan.ID_Bahan = pricelist.bahan and penjualan.kode = pricelist.jenis 

                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar
                    FROM 
                        pricelist
                    ) pricelist1
                ON
                    barang.ID_AT = pricelist1.bahan

                LEFT JOIN 
                    (
                    SELECT
                        pricelist.sisi,
                        pricelist.bahan,
                        pricelist.jenis,
                        pricelist.1_lembar,
                        pricelist.2_lembar,
                        pricelist.3sd5_lembar,
                        pricelist.6sd9_lembar,
                        pricelist.10_lembar,
                        pricelist.20_lembar,
                        pricelist.50_lembar,
                        pricelist.100_lembar,
                        pricelist.250_lembar,
                        pricelist.500_lembar,
                        pricelist.1sd2m,
                        pricelist.3sd9m,
                        pricelist.10m,
                        pricelist.50m
                    FROM 
                        pricelist
                    ) Pricelist_Cutting
                ON
                    barang.ID_Cutting = Pricelist_Cutting.bahan and penjualan.kode = Pricelist_Cutting.jenis 
                WHERE
                    penjualan.no_invoice = $_POST[no_invoice] and
                    penjualan.ID_Bahan = barang.id_barang and
                    penjualan.sisi = barang.sisi and
                    penjualan.satuan = barang.Satuan_Order
                ) table_invoice
                ORDER BY
                   oid
                ASC
        ";// OK WORKING FINE

        $data = mysqli_query($conn, $sql_data);
        while( $harga = mysqli_fetch_assoc($data)){
            $new_array[$harga['oid']] = array( 
                'oid' => $harga['oid'], 
                'b_digital' => $harga['b_digital'], 
                'b_lf' => $harga['b_lf'],
                'indoor' => $harga['indoor'],
                'b_potong' => $harga['b_potong'],
                'b_kotak' => $harga['b_kotak'],
                'b_AlatTambahan' => $harga['b_AlatTambahan'],
                'b_laminate' => $harga['b_laminate']
            );
        }

        $b_digital = "";
        $b_lf = "";
        $indoor = "";
        $b_potong = "";
        $b_kotak = "";
        $b_AlatTambahan = "";
        $b_laminate = "";
        $oid = "";
        $log ="";
        $test = "";

        $array1 = array(
            "Biaya_Digital",
            "Biaya_Large",
            "Biaya_Indoor",
            "Biaya_Potong",
            "Biaya_Kotak",
            "Biaya_Xbanner",
            "Biaya_Laminate"
        );

        $array2 = array(
            "b_digital",
            "b_lf",
            "indoor",
            "b_potong",
            "b_kotak",
            "b_AlatTambahan",
            "b_laminate"
        );

        foreach($new_array as $array) { 
            $oid .= "$array[oid],";
            $b_digital .= "when oid = $array[oid] then '$array[b_digital]'";
            $b_lf .= "when oid = $array[oid] then '$array[b_lf]'";
            $indoor .= "when oid = $array[oid] then '$array[indoor]'";
            $b_laminate .= "when oid = $array[oid] then '$array[b_laminate]'";
            $b_potong .= "when oid = $array[oid] then '$array[b_potong]'";
            $b_kotak .= "when oid = $array[oid] then '$array[b_kotak]'";
            $b_AlatTambahan .= "when oid = $array[oid] then '$array[b_AlatTambahan]'";

            for ($x = 0; $x <= 7; $x++) {
                if((((int)$row[$array1[$x]]) != ((int)$array[$array2[$x]])) and ( ($row['oid']) == ($array['oid']) )) {
                    $test .= "<b>$array1[$x] </b> : ". number_format($row[$array1[$x]]) ." <i class=\"far fa-angle-double-right\"></i> ". number_format($array[$array2[$x]]) ."<br>";
                } else { 
                    $test .= ""; 
                }
            }

            $log  .= "
            when oid = $array[oid] then '
                <tr>
                    <td>
                        $timestamps
                    </td>
                    <td>
                        ". $_SESSION['username'] ." Mengubah data
                    </td>
                    <td>
                        $test
                    </td>
                </tr>
            '";
        }

        if($test != null) {
            $Final_log = "$log";  
        } else {
            $Final_log = "ERROR";
        }
	
        $reid = explode("," , "$oid");
        foreach($reid as $yes) {
            if($yes!="") { $y[] = "$yes"; }
        }
        $aid = implode("','", $y);

        $sql = 
        "UPDATE penjualan
            SET b_digital = (CASE 
                                $b_digital
                            END),
                b_large = (CASE 
                                $b_lf
                            END),
                b_kotak = (CASE 
                                $b_kotak
                            END),
                b_laminate = (CASE 
                                $b_laminate
                            END),
                b_potong = (CASE 
                                $b_potong
                            END),
                b_indoor = (CASE 
                                $indoor
                            END),
                b_xbanner = (CASE 
                                $b_AlatTambahan
                            END),
                history   = CONCAT((CASE 
                                $Final_log
                            END), history)
            WHERE oid IN ('$aid');
        ";
    } elseif($_POST['jenis_submit']=='Update_SO_Invoice' and $_POST['Auto_Calc']=='N') {
        $sql_Data_OID =
        "SELECT
            penjualan.oid,
            penjualan.description as Deskripsi,
            (CASE
                WHEN penjualan.kode = 'large format' THEN 'Large Format'
                WHEN penjualan.kode = 'digital' THEN 'Digital Printing'
                WHEN penjualan.kode = 'indoor' THEN 'Indoor HP Latex'
                WHEN penjualan.kode = 'Xuli' THEN 'Indoor Xuli'
                WHEN penjualan.kode = 'offset' THEN 'Offset Printing'
                WHEN penjualan.kode = 'etc' THEN 'ETC'
                ELSE '- - -'
            END) as Kode_barang,
            (CASE
                WHEN penjualan.laminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                WHEN penjualan.laminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                WHEN penjualan.laminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                WHEN penjualan.laminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                WHEN penjualan.laminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                WHEN penjualan.laminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                WHEN penjualan.laminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                WHEN penjualan.laminate = 'laminating_floor' THEN 'Laminating Floor'
                ELSE ''
            END) as Laminating,
            (CASE
                WHEN penjualan.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                WHEN penjualan.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                WHEN penjualan.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                WHEN penjualan.alat_tambahan = 'Tripod' THEN 'Tripod'
                WHEN penjualan.alat_tambahan = 'Softboard' THEN 'Softboard'
                WHEN penjualan.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                ELSE ''
            END) as Alat_Tambahan,
            customer.nama_client as Nama_Client,
            customer.cid as ID_Client,
            penjualan.ukuran as Ukuran,
            penjualan.panjang as Panjang,
            penjualan.lebar as Lebar,
            penjualan.sisi as Sisi,
            penjualan.ID_Bahan,
            Bahan.nama_barang as Nama_Bahan,
            penjualan.keterangan as Notes,
            penjualan.qty as Qty,
            penjualan.satuan as Satuan,
            penjualan.potong as Potong_Putus,
            penjualan.potong_gantung as Potong_Gantung,
            penjualan.pon as Pon_Garis,
            penjualan.perporasi as Perporasi,
            penjualan.CuttingSticker as Cutting_Stiker,
            penjualan.Hekter_Tengah as Hekter_Tengah,
            penjualan.Blok,
            penjualan.Spiral,
            penjualan.ditunggu as Ditunggu,
            penjualan.Proffing,
            penjualan.Design,
            penjualan.file_design as Nama_File,
            penjualan.img_design as Nama_Image,
            penjualan.b_digital AS Biaya_Digital,
            penjualan.b_kotak AS Biaya_Kotak,
            penjualan.b_lain AS Biaya_Lain,
            penjualan.b_potong AS Biaya_Potong,
            penjualan.b_large AS Biaya_Large,
            penjualan.b_indoor AS Biaya_Indoor,
            penjualan.b_xbanner AS Biaya_Xbanner,
            penjualan.b_offset AS Biaya_Offset,
            penjualan.b_laminate AS Biaya_Laminate,
            penjualan.b_design AS Biaya_Design,
            penjualan.b_delivery AS Biaya_Delivery,
            penjualan.discount as Discount,
            (CASE
                WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                WHEN penjualan.akses_edit = 'N' THEN 'N'
                ELSE 'N'
            END) as Akses_Edit
        FROM
            penjualan
        LEFT JOIN 
            (select customer.cid, customer.nama_client from customer) customer
        ON
            penjualan.client = customer.cid  
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) Bahan
        ON
            penjualan.ID_Bahan = Bahan.id_barang  
        WHERE
            penjualan.oid = '$_POST[ID_Order]'
        ";

        $result = mysqli_query($conn, $sql_Data_OID);
        if( mysqli_num_rows($result) === 1 ) {
            $row = mysqli_fetch_assoc($result);

            if(is_array($_FILES)) {

                $target_file = "../design/$row[Nama_File]";
                $target_image = "../design/$row[Nama_Image]";

  
                if(is_uploaded_file($_FILES['DesignFile']['tmp_name'])) { // Design File

                    if(file_exists($target_image)) {
                        unlink($target_image);
                    } else {
                        die("ERROR Hapus Image");
                    }
                    
                    $basename = pathinfo($target_file, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["DesignFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['DesignFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('rar','zip');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_FileValue = "file_design = '".$File_DesignName."',";
        
                            $Log_file = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
        
                if(is_uploaded_file($_FILES['imageFile']['tmp_name'])) {

                    if(file_exists($target_file)) {
                        unlink($target_file);
                    } else {
                        die("ERROR Hapus File");
                    }
                    
                    $basename = pathinfo($target_image, PATHINFO_FILENAME);

                    $ekstensiFile = pathinfo($_FILES["imageFile"]["name"],PATHINFO_EXTENSION);
                    $File_DesignName = $basename . "." . $ekstensiFile;
        
                    $sourcePath = $_FILES['imageFile']['tmp_name'];
                    $targetPath = "../design/".$File_DesignName;
                    $ekstensiOk = array('jpg','jpeg','png','gif');
        
                    if(in_array($ekstensiFile, $ekstensiOk) === true){
                        if(move_uploaded_file($sourcePath,$targetPath)) {
                            $mysql_ImgValue = "img_design = '".$File_DesignName."',";
        
                            $Log_image = "".$File_DesignName."";
                        } else {
                            die("ERROR");
                        }
                    }
                }
            }

            if($_POST['Panjang'] == "") { $Panjang = "0"; } else { $Panjang = "$_POST[Panjang]"; }
            if($_POST['Lebar'] == "") { $Lebar = "0"; } else { $Lebar = "$_POST[Lebar]"; }
           
            if($_POST['b_digital'] == "undefined" or $_POST['b_digital'] == "" ) { $b_digital = "0"; } else { $b_digital = "$_POST[b_digital]"; }
            if($_POST['b_large'] == "undefined" or $_POST['b_large'] == "" ) { $b_large = "0"; } else { $b_large = "$_POST[b_large]"; }
            if($_POST['b_kotak'] == "undefined" or $_POST['b_kotak'] == "" ) { $b_kotak = "0"; } else { $b_kotak = "$_POST[b_kotak]"; }
            if($_POST['b_laminate'] == "undefined" or $_POST['b_laminate'] == "" ) { $b_laminate = "0"; } else { $b_laminate = "$_POST[b_laminate]"; }
            if($_POST['b_potong'] == "undefined" or $_POST['b_potong'] == "" ) { $b_potong = "0"; } else { $b_potong = "$_POST[b_potong]"; }
            if($_POST['b_indoor'] == "undefined" or $_POST['b_indoor'] == "" ) { $b_indoor = "0"; } else { $b_indoor = "$_POST[b_indoor]"; }
            if($_POST['b_xbanner'] == "undefined" or $_POST['b_xbanner'] == "" ) { $b_xbanner = "0"; } else { $b_xbanner = "$_POST[b_xbanner]"; }
            if($_POST['b_lain'] == "undefined" or $_POST['b_lain'] == "" ) { $b_lain = "0"; } else { $b_lain = "$_POST[b_lain]"; }
            if($_POST['b_offset'] == "undefined" or $_POST['b_offset'] == "" ) { $b_offset = "0"; } else { $b_offset = "$_POST[b_offset]"; }
            if($_POST['b_design'] == "undefined" or $_POST['b_design'] == "" ) { $b_design = "0"; } else { $b_design = "$_POST[b_design]"; }
            if($_POST['b_delivery'] == "undefined" or $_POST['b_delivery'] == "" ) { $b_delivery = "0"; } else { $b_delivery = "$_POST[b_delivery]"; }
            if($_POST['discount'] == "undefined" or $_POST['discount'] == "" ) { $discount = "0"; } else { $discount = "$_POST[discount]"; }

            $array = array (
                "Kode_barang"                  => "$_POST[Desc_Kode_Brg]",
                "Nama_Client"                  => "$Nama_Client",
                "Deskripsi"                    => "$Deskripsi",
                "Ukuran"                       => "$_POST[Ukuran]",
                "Panjang"                      => "$Panjang",
                "Lebar"                        => "$Lebar",
                "Sisi"                         => "$_POST[Sisi]",
                "Nama_Bahan"                   => "$Nama_Bahan",
                "Notes"                        => "$Notes",
                "Laminating"                   => "$_POST[Desc_Laminating]",
                "Alat_Tambahan"                => "$_POST[Desc_alat_tambahan]",
                "Potong_Putus"                 => "$_POST[Ptg_Pts]",
                "Potong_Gantung"               => "$_POST[Ptg_Gantung]",
                "Pon_Garis"                    => "$_POST[Pon_Garis]",
                "Perporasi"                    => "$_POST[Perporasi]",
                "Cutting_Stiker"               => "$_POST[CuttingSticker]",
                "Hekter_Tengah"                => "$_POST[Hekter_Tengah]",
                "Blok"                         => "$_POST[Blok]",
                "Spiral"                       => "$_POST[Spiral]",
                "Qty"                          => "$_POST[Qty]",
                "Satuan"                       => "$Satuan",
                "Proffing"                     => "$_POST[Proffing]",
                "Ditunggu"                     => "$_POST[Ditunggu]",
                "Design"                       => "$_POST[Design]",
                "Biaya_Digital"                => "$b_digital",
                "Biaya_Large"                  => "$b_large",
                "Biaya_Kotak"                  => "$b_kotak",
                "Biaya_Laminate"               => "$b_laminate",
                "Biaya_Potong"                 => "$b_potong",
                "Biaya_Indoor"                 => "$b_indoor",
                "Biaya_Xbanner"                => "$b_xbanner",
                "Biaya_Lain"                   => "$b_lain",
                "Biaya_Offset"                 => "$b_offset",
                "Biaya_Design"                 => "$b_design",
                "Biaya_Delivery"               => "$b_delivery",
                "Discount"                     => "$discount"
            );

            $log ="";

            foreach($array as $key => $value ) {
                $a = $row[$key];
                if($value!="$row[$key]") {
                    if(is_numeric($value)) {
                        $Input_Value = number_format($value); 
                    } else {
                        $Input_Value = "$value";
                    }
                    $deskripsi = str_replace("_"," ", $key);
                    $log  .= "<b>$deskripsi</b> : $a <i class=\"far fa-angle-double-right\"></i> $Input_Value<br>";
                } else {
                    $log  .= "";
                }
            }

            if($log != null) {
                $Final_log = "
                    <tr>
                        <td>$timestamps</td>
                        <td>". $_SESSION['username'] ." Mengubah data</td>
                        <td>$log</td>
                    </tr>
                ";
                if($_POST[akses_edit]=="Y") { $akses_edit = "N"; } else { $akses_edit = "$_POST[akses_edit]"; }
            } else {
                $Final_log = "";
                $akses_edit = "$_POST[akses_edit]";
            }
            
        } else {
            $Final_log = "
                <tr>
                    <td>$timestamps</td>
                    <td>". $_SESSION['username'] ." Mengubah data</td>
                    <td>ERROR Input Logs Data</td>
                </tr>
            ";
        }
        
        $sql = 
        "UPDATE penjualan SET 
            kode             = '$_POST[Kode_Brg]', 
            client           = '$_POST[ID_Client]',
            description      = '$Deskripsi',
            ukuran           = '$_POST[Ukuran]',
            panjang          = '$_POST[Panjang]',
            lebar            = '$_POST[Lebar]',
            sisi             = '$_POST[Sisi]',
            ID_Bahan         = '$_POST[ID_Bahan]',
            keterangan       = '$Notes',
            laminate         = '$_POST[Laminating]',
            alat_tambahan    = '$_POST[alat_tambahan]',
            potong           = '$_POST[Ptg_Pts]',
            potong_gantung   = '$_POST[Ptg_Gantung]',
            pon              = '$_POST[Pon_Garis]',
            perporasi        = '$_POST[Perporasi]',
            CuttingSticker   = '$_POST[CuttingSticker]',
            Hekter_Tengah    = '$_POST[Hekter_Tengah]',
            Blok             = '$_POST[Blok]',
            Spiral           = '$_POST[Spiral]',
            qty              = '$_POST[Qty]',
            satuan           = '$Satuan',
            Proffing         = '$_POST[Proffing]',
            ditunggu         = '$_POST[Ditunggu]',
            Design           = '$_POST[Design]',
            b_digital        = '$b_digital',
            b_large          = '$b_large',
            b_kotak          = '$b_kotak',
            b_laminate       = '$b_laminate',
            b_potong         = '$b_potong',
            b_indoor         = '$b_indoor',
            b_xbanner        = '$b_xbanner',
            b_lain           = '$b_lain',
            b_offset         = '$b_offset',
            b_design         = '$b_design',
            b_delivery       = '$b_delivery',
            discount         = '$discount',
            akses_edit       = '$akses_edit',
            $mysql_FileValue
            $mysql_ImgValue
            history          =  CONCAT('$Final_log', history)
        WHERE 
            oid = $_POST[ID_Order]
        ";
    } elseif($_POST['jenis_submit']=='Akses_Edit') {

        if($_POST[jenis_akses]=="Y") {
            $akses_edit = "N";
        } else {
            $akses_edit = "Y";
        }

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." mengubah data</td>
                <td><b>Akses Edit</b> : $akses_edit</td>
            </tr>
        ";
        
        // Attempt Update Cancel query execution
        $sql = 
        "UPDATE
            penjualan
        SET
            akses_edit	    = '$akses_edit',
            history         =  CONCAT('$Final_log', history)
        WHERE
            oid				= '$_POST[ID_Order]'
        ";
    } elseif($_POST['jenis_submit']=='check_invoice') {

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." mengubah data</td>
                <td><b>Invoice Check</b> : Y<br>
                    <b>Akses Edit Check</b> : N<br>
                    <b>Sales</b> : ". $_SESSION['username'] ."
                </td>
            </tr>
        ";
        
        // Attempt Update Cancel query execution
        $sql = 
        "UPDATE
            penjualan
        SET
            inv_check	    = 'Y',
            sales	        = '$_SESSION[uid]',
            akses_edit	    = 'N',
            history         =  CONCAT('$Final_log', history)
        WHERE
            no_invoice		= '$_POST[ID_Order]'
        ";
    }

    if (mysqli_query($conn, $sql)){
        echo "Records inserted or Update successfully.";
    } else{
        echo "<b class='text-danger'>ERROR: Could not able to execute<br> $sql<br>" . mysqli_error($conn) . "</b>";
    }
    
    // Close connection
    mysqli_close($conn);
?>