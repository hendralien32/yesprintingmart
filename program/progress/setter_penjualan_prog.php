<?php
    session_start();
    
    require_once "../../function.php";

    // echo $_FILES['imageFile']['name']."<br>";
    // echo $_FILES['imageFile']['type']."<br>";
    // echo $_FILES['imageFile']['tmp_name']."<br>";
    // echo $_FILES['imageFile']['error']."<br>";
    // echo $_FILES['imageFile']['size']."<br>";

    if($_POST['jenis_submit']=='Insert' or $_POST['jenis_submit']=='Update' or $_POST['jenis_submit']=='Update_SO_Invoice' or $_POST['jenis_submit']=='Update_PenjualanYESCOM') {
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

    if($_POST['jenis_submit']=='Insert') :

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
            if($value!="" && $value!="N" && $value!="0") {
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

    elseif($_POST['jenis_submit']=='Update') :

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
        
    elseif($_POST['jenis_submit']=='cancel_invoice') :
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
    elseif($_POST['jenis_submit']=='Cancel') :

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

    elseif($_POST['jenis_submit']=='force_paid') :

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

    elseif($_POST['jenis_submit']=='create_invoice') :
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
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
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
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 500 THEN 500_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 250 THEN 250_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 100 THEN 100_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 50 THEN 50_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 20 THEN 20_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 10 THEN 10_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 2 THEN 2_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty < 1 THEN ( 1sd2m_Cutting ) / test 
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
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2),0)
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2),0)
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2)*4,0)
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
                    ((penjualan.panjang * penjualan.lebar)/10000) as Uk_PxL,
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
    elseif($_POST['jenis_submit']=='Update_SO_Invoice' and $_POST['Auto_Calc']=='Y') : 
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
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
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
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 500 THEN 500_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 250 THEN 250_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 100 THEN 100_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 50 THEN 50_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 20 THEN 20_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 10 THEN 10_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 2 THEN 2_lembar_AT
                    WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty < 1 THEN ( 1sd2m_Cutting / test )
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
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2),0)
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2),0)
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2)*4,0)
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
                    ((penjualan.panjang * penjualan.lebar)/10000) as Uk_PxL,
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
    elseif($_POST['jenis_submit']=='Update_SO_Invoice' and $_POST['Auto_Calc']=='N') :
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
    elseif($_POST['jenis_submit']=='Akses_Edit') :
        if($_POST['jenis_akses']=="Y") {
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
    elseif($_POST['jenis_submit']=='check_invoice') :
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
    elseif($_POST['jenis_submit']=='ReAdd_Invoice') :
        $waktu = date("Y-m-d H:I:s");

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
            $test = "0";
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
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
                    WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
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
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 500 THEN 500_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 250 THEN 250_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 100 THEN 100_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 50 THEN 50_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 20 THEN 20_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 10 THEN 10_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 2 THEN 2_lembar_AT
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_AT != '31' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * Uk_PxL )
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * Uk_PxL )
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
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2),0)
                    WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2),0)
                    WHEN laminate = 'doff1' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'doff2' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2)*4,0)
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
                    ((penjualan.panjang * penjualan.lebar)/10000) as Uk_PxL,
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
                            penjualan.oid IN ($fix_yes)
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
                            penjualan.oid IN ($fix_yes)
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
        while( $row = mysqli_fetch_assoc($data)){
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
            $no_invoice .= "when oid = $array[oid] then '$test'";
            $invoice_date .= "when oid = $array[oid] then '$waktu'";
        }

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Update Data</td>
                <td><b>No Invoice</b> :  #$test</td>
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
                history   =  CONCAT('$Final_log', history),
                inv_check =  'N'
            WHERE oid IN ($fix_yes);
        ";

        $list_no = "$_POST[idx]";
        $reid = explode("," , "$list_no");
        foreach($reid as $no) {
            if($no!="") { $n[] = "$no"; }
        }
        $REaid = implode("','", $n);
        $fix_no = "'$REaid'";

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Update Data</td>
                <td><b>No Invoice</b> :  - </td>
            </tr>
        ";


        $query_no = 
        "UPDATE
            penjualan
        SET
            no_invoice = '0',
            invoice_date = '0000-00-00 00:00:00',
            history   =  CONCAT('$Final_log', history),
            inv_check =  'N'
        WHERE
            oid IN ($fix_no)
        ";
        
        if($list_no!="") { 
            mysqli_query($conn, $query_no); 
        } else {

        }

    elseif($_POST['jenis_submit']=='Payment') :
        
        if(($_POST['sisa_bayar'] == $_POST['jumlah_bayar']) and $_POST['bank'] == "") {
            $type_pembayaran = "Cash";
            $status_lunas = "Lunas";
        } elseif(($_POST['jumlah_bayar'] < $_POST['sisa_bayar']) and $_POST['bank'] == "") {
            $type_pembayaran = "DP";
            $status_lunas = "";
        } elseif(($_POST['sisa_bayar'] == $_POST['jumlah_bayar']) and $_POST['bank'] != "") {
            $type_pembayaran = "Kartu Kredit";
            $status_lunas = "Lunas";
        } elseif(($_POST['jumlah_bayar'] < $_POST['sisa_bayar']) and $_POST['bank'] != "") {
            $type_pembayaran = "DP Kartu Kredit";
            $status_lunas = "";
        } else {
            $type_pembayaran = "";
            $status_lunas = "";
        }

        if($status_lunas=="Lunas") :
            $lunas = "pembayaran = 'lunas',";
        endif;

        $log = "";

        $array_kode = array(
            "Jumlah_Bayar"               => "$_POST[jumlah_bayar]",
            "Adjust_Pay"                 => "$_POST[adjust]",
            "Jenis_Kartu"                => "$_POST[bank]",
            "Type_Pembayaran"            => "$type_pembayaran",
            "Nomor_Kartu"                => "$_POST[nomor_atm]",
            "Rekening_Tujuan"            => "$_POST[rekening_tujuan]"
        );

        foreach($array_kode as $key => $value ) :
            if($value!="") :
                if(is_numeric($value)) {
                    $Input_Value = number_format($value); 
                } else {
                    $Input_Value = "$value";
                }
                $deskripsi = str_replace("_"," ", $key);
                $log  .= "<b>$deskripsi</b> : $Input_Value<br>";
            else :
                $log  .= "";
            endif;
        endforeach;
        
        if($log != null) :
            $Final_log = "
                <tr>
                    <td>$hr, $timestamps</td>
                    <td>". $_SESSION['username'] ." Pelunasan Invoice</td>
                    <td>$log</td>
                </tr>
            ";
        else :
            $Final_log = "";
        endif;

        
        $penjualan_Lunas = 
        "UPDATE
            penjualan
        SET
            $lunas
            history    =  CONCAT('$Final_log', history)
        WHERE
            no_invoice = '$_POST[no_invoice]'
        ";

        $conn_OOP -> query($penjualan_Lunas);

        $sql = 
        "INSERT INTO pelunasan (
            no_invoice,
            tot_pay,
            adj_pay,
            type_pem,
            jenis_kartu,
            nomor_kartu,
            rekening_tujuan
        ) VALUES (
            '$_POST[no_invoice]',
            '$_POST[jumlah_bayar]',
            '$_POST[adjust]',
            '$type_pembayaran',
            '$_POST[bank]',
            '$_POST[nomor_atm]',
            '$_POST[rekening_tujuan]'
        )";

    elseif($_POST['jenis_submit']=='edit_Payment') :

        $PID_Inv = explode("*" , "$_POST[no_invoice]");

        $ID_Order = $PID_Inv[0];
        $Inv_Order = $PID_Inv[1];
            
        if(($_POST['sisa_bayar'] == $_POST['jumlah_bayar']) and $_POST['bank'] == "") {
            $type_pembayaran = "Cash";
            $status_lunas = "Lunas";
        } elseif(($_POST['jumlah_bayar'] < $_POST['sisa_bayar']) and $_POST['bank'] == "") {
            $type_pembayaran = "DP";
            $status_lunas = "";
        } elseif(($_POST['sisa_bayar'] == $_POST['jumlah_bayar']) and $_POST['bank'] != "") {
            $type_pembayaran = "Kartu Kredit";
            $status_lunas = "Lunas";
        } elseif(($_POST['jumlah_bayar'] < $_POST['sisa_bayar']) and $_POST['bank'] != "") {
            $type_pembayaran = "DP Kartu Kredit";
            $status_lunas = "";
        } else {
            $type_pembayaran = "";
            $status_lunas = "";
        }

        $log_pelunasan = 
        "SELECT
            pelunasan.tot_pay as Jumlah_Bayar,
            pelunasan.adj_pay as Adjust_Pay,
            pelunasan.type_pem as Type_Pembayaran,
            pelunasan.jenis_kartu as Jenis_Kartu,
            pelunasan.nomor_kartu as Nomor_Kartu,
            pelunasan.rekening_tujuan as Rekening_Tujuan
        FROM
            pelunasan
        WHERE
            pid = '$ID_Order'
        ";

        $result = $conn_OOP -> query($log_pelunasan);

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();

            $array = array (
                "Jumlah_Bayar"               => "$_POST[jumlah_bayar]",
                "Adjust_Pay"                 => "$_POST[adjust]",
                "Jenis_Kartu"                => "$_POST[bank]",
                "Type_Pembayaran"            => "$type_pembayaran",
                "Nomor_Kartu"                => "$_POST[nomor_atm]",
                "Rekening_Tujuan"            => "$_POST[rekening_tujuan]"
            );

            $log ="";

            foreach($array as $key => $value ) :
                $a = $row[$key];
                if($value!="$row[$key]") :
                    if(is_numeric($value)) {
                        $Input_Value = number_format($value); 
                    } else {
                        $Input_Value = "$value";
                    }
                    $deskripsi = str_replace("_"," ", $key);
                    $log  .= "<b>$deskripsi</b> : $a <i class=\"far fa-angle-double-right\"></i> $Input_Value<br>";
                else :
                    $log  .= "";
                endif;
            endforeach;

            $test = $row['Jumlah_tagihan'] - $row['total_bayar'];
        endif;

        
        if($status_lunas=="Lunas") :
            $lunas = "pembayaran = 'lunas',";
        endif;

        if($log != null) :
            $Final_log = "
                <tr>
                    <td>$hr, $timestamps</td>
                    <td>". $_SESSION['username'] ." Pelunasan Invoice</td>
                    <td>$log</td>
                </tr>
            ";
        else :
            $Final_log = "";
        endif;

        $penjualan_Lunas = 
        "UPDATE
            penjualan
        SET
            $lunas
            history    =  CONCAT('$Final_log', history)
        WHERE
            no_invoice = '$Inv_Order'
        ";

        $conn_OOP -> query($penjualan_Lunas);

        $sql = 
        "UPDATE
            pelunasan
        SET
            tot_pay = '$_POST[jumlah_bayar]',
            adj_pay = '$_POST[adjust]',
            type_pem = '$type_pembayaran',
            jenis_kartu = '$_POST[bank]',
            nomor_kartu = '$_POST[nomor_atm]',
            rekening_tujuan = '$_POST[rekening_tujuan]'
        WHERE
            pid = '$ID_Order'
        ";
    elseif($_POST['jenis_submit']=='multipayment') :
        $test = "";

        $list_pembayaran = explode("," , "$_POST[Nilai_bayar]");
        $list_invoice = explode("," , "$_POST[No_Invoice]");
        $list_Sisabayar = explode("," , "$_POST[Sisa_bayar]");
        

        $count = count($list_invoice);
        
        for($i=1 ; $i<$count ; $i++) {
            if($list_pembayaran[$i]!="" or $list_pembayaran[$i]!="0") {

                if(($list_Sisabayar[$i] == $list_pembayaran[$i]) and $_POST['bank'] == "") {
                    $type_pembayaran = "Cash";
                    $lunas = "pembayaran = 'lunas',";
                } elseif(($list_pembayaran[$i] < $list_Sisabayar[$i]) and $_POST['bank'] == "") {
                    $type_pembayaran = "DP";
                    $lunas = "";
                } elseif(($list_Sisabayar[$i] == $list_pembayaran[$i]) and $_POST['bank'] != "") {
                    $type_pembayaran = "Kartu Kredit";
                    $lunas = "";
                } elseif(($list_pembayaran[$i] < $list_Sisabayar[$i]) and $_POST['bank'] != "") {
                    $type_pembayaran = "DP Kartu Kredit";
                    $lunas = "";
                } else {
                    $type_pembayaran = "";
                    $lunas = "";
                }
        
                $log = "";
        
                $array_kode = array(
                    "Jumlah_Bayar"               => "$list_pembayaran[$i]",
                    "Jenis_Kartu"                => "$_POST[bank]",
                    "Type_Pembayaran"            => "$type_pembayaran",
                    "Nomor_Kartu"                => "$_POST[Nomor_Kartu]",
                    "Rekening_Tujuan"            => "$_POST[rekening_tujuan]"
                );
        
                foreach($array_kode as $key => $value ) :
                    if($value!="") :
                        if(is_numeric($value)) {
                            $Input_Value = number_format($value); 
                        } else {
                            $Input_Value = "$value";
                        }
                        $deskripsi = str_replace("_"," ", $key);
                        $log  .= "<b>$deskripsi</b> : $Input_Value<br>";
                    else :
                        $log  .= "";
                    endif;
                endforeach;
                
                if($log != null) :
                    $Final_log = "
                        <tr>
                            <td>$hr, $timestamps</td>
                            <td>". $_SESSION['username'] ." Pelunasan Invoice</td>
                            <td>$log</td>
                        </tr>
                    ";
                else :
                    $Final_log = "";
                endif;
        
                
                $sql .= 
                "UPDATE
                    penjualan
                SET
                    $lunas
                    history    =  CONCAT('$Final_log', history)
                WHERE
                    ( no_invoice = '$list_invoice[$i]' );
                ";

                $sql .= 
                "INSERT INTO pelunasan 
                    (
                        no_invoice,
                        tot_pay,
                        type_pem,
                        jenis_kartu,
                        nomor_kartu,
                        rekening_tujuan
                    )
                VALUES 
                    (
                        '$list_invoice[$i]',
                        '$list_pembayaran[$i]',
                        '$type_pembayaran',
                        '$_POST[bank]',
                        '$_POST[Nomor_Kartu]',
                        '$_POST[rekening_tujuan]'
                    );
                ";

            } else {
                $sql .= "";
            }
            
        }
        
    elseif($_POST['jenis_submit']=='delete_client') :
        if($_POST['status_client'] == "A") : $status_client = "T";
        else : $status_client = "A";
        endif;
        
        $sql = 
        "UPDATE
            customer
        SET
            status_client   = '$status_client'
        WHERE
            cid 		    = '$_POST[Client_ID]'
        ";
    elseif($_POST['jenis_submit']=='submit_client') :
        $sql = 
        "INSERT INTO customer (
            nama_client,
            no_telp,
            email,
            alamat_kantor,
            level_client,
            status_client,
            special
        ) VALUES (
            '$_POST[NamaClient]',
            '$_POST[NoTelp]',
            '$_POST[EmailClient]',
            '$_POST[DeskClient]',
            '$_POST[levelClient]',
            'A',
            '$_POST[Special]'
        )";
    elseif($_POST['jenis_submit']=='update_client') :
        $sql = 
        "UPDATE
            customer
        SET
            nama_client         = '$_POST[NamaClient]',
            no_telp             = '$_POST[NoTelp]',
            email               = '$_POST[EmailClient]',
            alamat_kantor       = '$_POST[DeskClient]',
            level_client        = '$_POST[levelClient]',
            special             = '$_POST[Special]'
        WHERE
            cid 		        = '$_POST[IdClient]'
        ";
    elseif($_POST['jenis_submit']=='delete_user') :
        if($_POST['status_user'] == "a") : $status_user = "n";
        else : $status_user = "a";
        endif;
        
        $sql = 
        "UPDATE
            pm_user
        SET
            status   = '$status_user'
        WHERE
            uid      = '$_POST[user_ID]'
        ";
    elseif($_POST['jenis_submit']=='submit_username') :
        $password	= htmlentities($_POST['Password'], ENT_QUOTES);
        $pass       = md5("pmart"."$password");
    
        $sql = 
        "INSERT INTO pm_user (
            nama,
            username,
            password,
            password_visible,
            phone,
            tanggal_masuk,
            tanggal_resign,
            level,
            status
        ) VALUES (
            '$_POST[Nama]',
            '$_POST[Username]',
            '$pass',
            '$_POST[Password]',
            '$_POST[NoTelp]',
            '$_POST[TglMasuk]',
            '$_POST[Tgl_Keluar]',
            '$_POST[LevelUser]',
            'a'
        )";
    elseif($_POST['jenis_submit']=='update_username') :
        if($_POST['Password']!="") {
            $password	= htmlentities($_POST['Password'], ENT_QUOTES);
            $pass       = md5("pmart"."$password");

            $change_pass = "
                password	        = '$pass', 
                password_visible    = '$password', 
            ";
        } else {
            $change_pass = "";
        }

        $sql = 
        "UPDATE
			pm_user
		set
            nama		        = '$_POST[Nama]',
			username	        = '$_POST[Username]',
            phone               = '$_POST[NoTelp]',
            tanggal_masuk       = '$_POST[TglMasuk]',
            tanggal_resign      = '$_POST[Tgl_Keluar]',
            $change_pass
            level		        = '$_POST[LevelUser]'
		where
			uid			        = '$_POST[IdUser]'
		limit
            1
        ";
    elseif($_POST['jenis_submit']=='delete_bahan') :
        if($_POST['status_bahan'] == "a") : $status_bahan = "n";
        else : $status_bahan = "a";
        endif;
        
        $sql = 
        "UPDATE
            barang
        SET
            status_bahan   = '$status_bahan'
        WHERE
            id_barang      = '$_POST[bahan_ID]'
        ";
    elseif($_POST['jenis_submit']=='submit_bahan') :
        $query =
        "SELECT
            GROUP_CONCAT(CAST((REPLACE(barang.kode_barang, '$_POST[JenisBahan]', '')) AS UNSIGNED)) as kode_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = '$_POST[JenisBahan]'
        GROUP BY
            barang.jenis_barang
        ";
        $result = $conn_OOP -> query($query) -> fetch_assoc();

        $arr1 = explode(',',$result['kode_barang']); //buat kode barang dijadikan array
        $arr2 = range(1,max($arr1));                                                 
        $missing = array_diff($arr2,$arr1); // cari nilai array yang hilang
        if($missing[1]!="") { 
            $angka = $_POST['JenisBahan'].sprintf("%02d",$missing[1]);
        } else {
            $angka = $_POST['JenisBahan'].sprintf("%02d",max($arr1)+1);
        }

        $Satuan = ucfirst($_POST['Satuan']);

        $sql =
        "INSERT INTO barang (
            nama_barang,
            jenis_barang,
            kode_barang,
            min_stock,
            satuan,
            status_bahan
        ) VALUES (
            '$_POST[Bahan]',
            '$_POST[JenisBahan]',
            '$angka',
            '$_POST[MinStock]',
            '$Satuan',
            'a'
        )
        ";
    elseif($_POST['jenis_submit']=='update_bahan') :
        $query =
        "SELECT
            GROUP_CONCAT(CAST((REPLACE(barang.kode_barang, '$_POST[JenisBahan]', '')) AS UNSIGNED)) as kode_barang
        FROM
            barang
        WHERE
            barang.jenis_barang = '$_POST[JenisBahan]' and
            barang.kode_barang != '$_POST[KodeBrng]'
        GROUP BY
            barang.jenis_barang
        ";
        $result = $conn_OOP -> query($query) -> fetch_assoc();

        $arr1 = explode(',',$result['kode_barang']); //buat kode barang dijadikan array
        $arr2 = range(1,max($arr1));                                                 
        $missing = implode("",array_diff($arr2,$arr1)); // cari nilai array yang hilang

        if($missing!="") { 
            $angka = $_POST['JenisBahan'].sprintf("%02d",$missing);
        } else {
            $angka = $_POST['JenisBahan'].sprintf("%02d",max($arr1)+1);
        }

        $Satuan = ucfirst($_POST['Satuan']);

        $sql = 
        "UPDATE
			barang
		set
            nama_barang		    = '$_POST[Bahan]',
			jenis_barang	    = '$_POST[JenisBahan]',
            kode_barang         = '$angka',
            min_stock           = '$_POST[MinStock]',
            satuan              = '$Satuan'
		where
            id_barang			= '$_POST[IdBahan]'
		limit
            1
        ";
    elseif($_POST['jenis_submit']=='submit_pricelist') :
        $sql =
        "INSERT INTO pricelist (
            pricelist.bahan,
            pricelist.jenis,
            pricelist.sisi,
            pricelist.warna,
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
            pricelist.1_kotak,
            pricelist.2sd19_kotak,
            pricelist.20_kotak,
            pricelist.1sd2m,
            pricelist.3sd9m,
            pricelist.10m,
            pricelist.50m,
            pricelist.harga_indoor,
            pricelist.6sd8pass_indoor,
            pricelist.12pass_indoor,
            pricelist.20pass_indoor,
            pricelist.special_price,
            pricelist.status_pricelist
        ) VALUES (
            '$_POST[bahanFC]',
            '$_POST[kode_barng]',
            '$_POST[Sisi]',
            '$_POST[form_Warna]',
            '$_POST[f_1_lembar]',
            '$_POST[f_2_lembar]',
            '$_POST[f_3sd5_lembar]',
            '$_POST[f_6sd9_lembar]',
            '$_POST[f_10_lembar]',
            '$_POST[f_20_lembar]',
            '$_POST[f_50_lembar]',
            '$_POST[f_100_lembar]',
            '$_POST[f_250_lembar]',
            '$_POST[f_500_lembar]',
            '$_POST[f_1_kotak]',
            '$_POST[f_2sd19_kotak]',
            '$_POST[f_20_kotak]',
            '$_POST[f_1sd2m]',
            '$_POST[f_3sd9m]',
            '$_POST[f_10m]',
            '$_POST[f_50m]',
            '$_POST[f_harga_indoor]',
            '$_POST[f_6sd8pass_indoor]',
            '$_POST[f_12pass_indoor]',
            '$_POST[f_20pass_indoor]',
            '$_POST[SpecialPrice]',
            'a'
        )
        ";
    elseif($_POST['jenis_submit']=='update_pricelist') :    
        $sql = 
        "UPDATE
            pricelist
        SET
            pricelist.bahan = '$_POST[bahanFC]', 
            pricelist.jenis = '$_POST[kode_barng]', 
            pricelist.sisi = '$_POST[Sisi]', 
            pricelist.warna = '$_POST[form_Warna]', 
            pricelist.1_lembar = '$_POST[f_1_lembar]', 
            pricelist.2_lembar = '$_POST[f_2_lembar]', 
            pricelist.3sd5_lembar = '$_POST[f_3sd5_lembar]', 
            pricelist.6sd9_lembar = '$_POST[f_6sd9_lembar]', 
            pricelist.10_lembar = '$_POST[f_10_lembar]', 
            pricelist.20_lembar = '$_POST[f_20_lembar]', 
            pricelist.50_lembar = '$_POST[f_50_lembar]', 
            pricelist.100_lembar = '$_POST[f_100_lembar]', 
            pricelist.250_lembar = '$_POST[f_250_lembar]', 
            pricelist.500_lembar = '$_POST[f_500_lembar]', 
            pricelist.1_kotak = '$_POST[f_1_kotak]', 
            pricelist.2sd19_kotak = '$_POST[f_2sd19_kotak]', 
            pricelist.20_kotak = '$_POST[f_20_kotak]', 
            pricelist.1sd2m = '$_POST[f_1sd2m]', 
            pricelist.3sd9m = '$_POST[f_3sd9m]', 
            pricelist.10m = '$_POST[f_10m]', 
            pricelist.50m = '$_POST[f_50m]', 
            pricelist.harga_indoor = '$_POST[f_harga_indoor]', 
            pricelist.6sd8pass_indoor = '$_POST[f_6sd8pass_indoor]', 
            pricelist.12pass_indoor = '$_POST[f_12pass_indoor]', 
            pricelist.20pass_indoor = '$_POST[f_20pass_indoor]', 
            pricelist.special_price = '$_POST[SpecialPrice]'
        WHERE
            price_id 		        = '$_POST[id_pricelist]'
        ";
    elseif($_POST['jenis_submit']=='delete_pricelist') :
        if($_POST['status_pricelist'] == "a") : $status_pricelist = "n";
        else : $status_pricelist = "a";
        endif;
        
        $sql = 
        "UPDATE
            pricelist
        SET
            status_pricelist   = '$status_pricelist'
        WHERE
            price_id      = '$_POST[pricelist_ID]'
        ";
    elseif($_POST['jenis_submit']=='Insert_WO_List') :
        $project         = htmlspecialchars($_POST['Deskripsi'],ENT_QUOTES);
        $Client_YES      = htmlspecialchars($_POST['Nama_Client'],ENT_QUOTES);

        $array = array (
            "ID Yes"                    => "$_POST[id_yescom]",
            "SO Yes"                    => "$_POST[so_yescom]",
            "AE Yes"                    => "$_POST[marketing_yescom]",
            "Warna Work Order"          => "$_POST[wo_yescom]",
            "Ukuran Yes"                => "$_POST[ukuran_yescom]",
            "Qty Yes"                   => "$_POST[qty_yescom]",
            "Kode barang"               => "$_POST[Desc_Kode_Brg]",
            "Nama Client"               => "$Client_YES",
            "Deskripsi"                 => "$project",
            "Ukuran"                    => "$_POST[Ukuran]",
            "Panjang"                   => "$_POST[Panjang]",
            "Lebar"                     => "$_POST[Lebar]",
            "Sisi"                      => "$_POST[Sisi]",
            "Warna"                     => "$_POST[warna_cetakan]",
            "Nama Bahan"                => "$Nama_Bahan",
            "Bahan Sendiri"             => "$_POST[bahan_sendiri]",
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
            "Urgent"                    => "$_POST[urgent]",
            "Proffing"                  => "$_POST[Proffing]",
            "Ditunggu"                  => "$_POST[Ditunggu]"
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

        $sql = 
        "INSERT INTO wo_list (
            wo_list.kode,
            wo_list.wo_color,
            wo_list.send_via,
            wo_list.marketing,
            wo_list.id,
            wo_list.so,
            wo_list.client,
            wo_list.project,
            wo_list.ID_Bahan,
            wo_list.bahan_sendiri,
            wo_list.ukuran,
            wo_list.ukuran_jadi,
            wo_list.panjang,
            wo_list.lebar,
            wo_list.cetak,
            wo_list.potong,
            wo_list.potong_gantung,
            wo_list.pon,
            wo_list.perporasi,
            wo_list.CuttingSticker,
            wo_list.Hekter_Tengah,
            wo_list.Blok,
            wo_list.Spiral,
            wo_list.leminate,
            wo_list.finishing,
            wo_list.qty,
            wo_list.qty_jadi,
            wo_list.satuan,
            wo_list.urgent,
            wo_list.Proffing,
            wo_list.Ditunggu,
            wo_list.send_by,
            wo_list.warna,
            wo_list.alat_tambahan,
            wo_list.log,
            wo_list.akses_edit
        ) VALUES (
            '$_POST[Kode_Brg]',
            '$_POST[wo_yescom]',
            'email',
            '$_POST[marketing_yescom]',
            '$_POST[id_yescom]',
            '$_POST[so_yescom]',
            '$Client_YES',
            '$project',
            '$_POST[ID_Bahan]',
            '$_POST[bahan_sendiri]',
            '$_POST[Ukuran]',
            '$_POST[ukuran_yescom]',
            '$_POST[Panjang]',
            '$_POST[Lebar]',
            '$_POST[Sisi]',
            '$_POST[Ptg_Pts]',
            '$_POST[Ptg_Gantung]',
            '$_POST[Pon_Garis]',
            '$_POST[Perporasi]',
            '$_POST[CuttingSticker]',
            '$_POST[Hekter_Tengah]',
            '$_POST[Blok]',
            '$_POST[Spiral]',
            '$_POST[Laminating]',
            '$_POST[Notes]',
            '$_POST[Qty]',
            '$_POST[qty_yescom]',
            '$_POST[Satuan]',
            '$_POST[urgent]',
            '$_POST[Proffing]',
            '$_POST[Ditunggu]',
            '$_SESSION[username]',
            '$_POST[warna_cetakan]',
            '$_POST[alat_tambahan]',
            '$Final_log',
            'N'
        )
        ";
    elseif($_POST['jenis_submit']=='delete_WOLIST') :

        if($_POST['status_WO_LIST'] == "deleted") : 
            $status_pricelist = "";
            $detail_log = "<b>Hapus</b> : Y <i class=\"far fa-angle-double-right\"></i> N";
        else : 
            $status_pricelist = "deleted";
            $detail_log = "<b>Hapus</b> : N <i class=\"far fa-angle-double-right\"></i> Y";
        endif;

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Hapus data</td>
                <td>$detail_log</td>
            </tr>
        ";

        $sql = 
        "UPDATE
            wo_list
        SET
            status			= '$status_pricelist',
            log             =  CONCAT('$Final_log', log)
        WHERE
            wio				= '$_POST[WO_LIST_ID]'
        ";
    elseif($_POST['jenis_submit']=='WOList_Akses_Edit') :
        if($_POST['jenis_akses']=="Y") {
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
            wo_list
        SET
            akses_edit	    = '$akses_edit',
            log             =  CONCAT('$Final_log', log)
        WHERE
            wio				= '$_POST[WO_LIST_ID]'
        ";
    elseif($_POST['jenis_submit']=='Update_WO_List') :
        $sql =
        "SELECT
            wo_list.id as ID_Yes,
            CONVERT(wo_list.so, CHAR) as SO_Yes,
            wo_list.marketing as AE_Yes,
            wo_list.wo_color as Warna_Work_Order,
            wo_list.ukuran_jadi as Ukuran_Yes,
            wo_list.qty_jadi as Qty_Yes,
            wo_list.project	 as Project,
            (CASE
                WHEN wo_list.kode = 'large format' THEN 'Large Format'
                WHEN wo_list.kode = 'digital' THEN 'Digital Printing'
                WHEN wo_list.kode = 'indoor' THEN 'Indoor HP Latex'
                WHEN wo_list.kode = 'Xuli' THEN 'Indoor Xuli'
                WHEN wo_list.kode = 'etc' THEN 'ETC'
                ELSE '- - -'
            END) as Kode_barang,
            (CASE
                WHEN wo_list.leminate = 'kilat1' THEN 'Laminating Kilat 1 Sisi'
                WHEN wo_list.leminate = 'kilat2' THEN 'Laminating Kilat 2 Sisi'
                WHEN wo_list.leminate = 'doff1' THEN 'Laminating Doff 1 Sisi'
                WHEN wo_list.leminate = 'doff2' THEN 'Laminating Doff 2 Sisi'
                WHEN wo_list.leminate = 'kilatdingin1' THEN 'Laminating Kilat Dingin'
                WHEN wo_list.leminate = 'doffdingin1' THEN 'Laminating Doff Dingin'
                WHEN wo_list.leminate = 'hard_lemit' THEN 'Hard Laminating / Lamit KTP'
                WHEN wo_list.leminate = 'laminating_floor' THEN 'Laminating Floor'
                ELSE ''
            END) as Laminating,
            (CASE
                WHEN wo_list.alat_tambahan = 'Ybanner' THEN 'Ybanner'
                WHEN wo_list.alat_tambahan = 'RU_60' THEN 'Roller Up 60 x 160 Cm'
                WHEN wo_list.alat_tambahan = 'RU_80' THEN 'Roller Up 80 x 200 Cm'
                WHEN wo_list.alat_tambahan = 'RU_85' THEN 'Roller Up 85 x 200 Cm'
                WHEN wo_list.alat_tambahan = 'Tripod' THEN 'Tripod'
                WHEN wo_list.alat_tambahan = 'Softboard' THEN 'Softboard'
                WHEN wo_list.alat_tambahan = 'KotakNC' THEN 'Kotak Kartu Nama'
                ELSE ''
            END) as Alat_Tambahan,
            wo_list.ukuran as Ukuran,
            wo_list.panjang as Panjang,
            wo_list.lebar as Lebar,
            wo_list.cetak as Sisi,
            wo_list.ID_Bahan,
            Bahan.nama_barang as Nama_Bahan,
            wo_list.bahan_sendiri as Bahan_Sendiri,
            wo_list.finishing as Notes,
            wo_list.qty as Qty,
            wo_list.satuan as Satuan,
            wo_list.potong as Potong_Putus,
            wo_list.potong_gantung as Potong_Gantung,
            wo_list.pon as Pon_Garis,
            wo_list.perporasi as Perporasi,
            wo_list.CuttingSticker as Cutting_Stiker,
            wo_list.Hekter_Tengah as Hekter_Tengah,
            wo_list.Blok,
            wo_list.Spiral,
            wo_list.warna as Warna,
            wo_list.urgent as Urgent,
            wo_list.Proffing,
            wo_list.Ditunggu
        FROM
            wo_list
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) Bahan
        ON
            wo_list.ID_Bahan = Bahan.id_barang  
        WHERE
            wo_list.wio = '$_POST[id_Order]'
        ";

        $result = mysqli_query($conn, $sql);
        if( mysqli_num_rows($result) === 1 ) {
            $row = mysqli_fetch_assoc($result);

           if($_POST['Panjang']=="") { $Panjang = "0"; } else { $Panjang = "$_POST[Panjang]"; }
           if($_POST['Lebar']=="") { $Lebar = "0"; } else { $Lebar = "$_POST[Lebar]"; }

            $array = array (
                "ID_Yes"                      => "$_POST[id_yescom]",
                "SO_Yes"                      => "$_POST[so_yescom]",
                "AE_Yes"                      => "$_POST[marketing_yescom]",
                "Warna_Work_Order"            => "$_POST[wo_yescom]",
                "Ukuran_Yes"                  => "$_POST[ukuran_yescom]",
                "Qty_Yes"                     => "$_POST[qty_yescom]",
                "Project"                     => "$_POST[Deskripsi]",
                "Warna"                       => "$_POST[warna_cetakan]",
                "Kode_barang"                 => "$_POST[Desc_Kode_Brg]",
                "Laminating"                  => "$_POST[Laminating]",
                "Alat_Tambahan"               => "$_POST[Desc_alat_tambahan]",
                "Ukuran"                      => "$_POST[Ukuran]",
                "Panjang"                     => "$Panjang",
                "Lebar"                       => "$Lebar",
                "Sisi"                        => "$_POST[Sisi]",
                "Nama_Bahan"                  => "$_POST[Nama_Bahan]",
                "Bahan_Sendiri"               => "$_POST[bahan_sendiri]",
                "Notes"                       => "$_POST[Notes]",
                "Qty"                         => "$_POST[Qty]",
                "Satuan"                      => "$_POST[Satuan]",
                "Potong_Putus"                => "$_POST[Ptg_Pts]",
                "Potong_Gantung"              => "$_POST[Ptg_Gantung]",
                "Pon_Garis"                   => "$_POST[Pon_Garis]",
                "Perporasi"                   => "$_POST[Perporasi]",
                "Cutting_Stiker"              => "$_POST[CuttingSticker]",
                "Hekter_Tengah"               => "$_POST[Hekter_Tengah]",
                "Blok"                        => "$_POST[Blok]",
                "Spiral"                      => "$_POST[Spiral]",
                "Urgent"                      => "$_POST[urgent]",
                "Proffing"                    => "$_POST[Proffing]",
                "Ditunggu"                    => "$_POST[Ditunggu]"
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


        $project         = htmlspecialchars($_POST['Deskripsi'],ENT_QUOTES);
        $Client_YES      = htmlspecialchars($_POST['Nama_Client'],ENT_QUOTES);

        $sql = 
        "UPDATE
            wo_list
        SET
            kode                =   '$_POST[Kode_Brg]', 
            wo_color            =   '$_POST[wo_yescom]',
            marketing           =   '$_POST[marketing_yescom]', 
            id                  =   '$_POST[id_yescom]', 
            so                  =   '$_POST[so_yescom]', 
            client              =   '$Client_YES', 
            project             =   '$project', 
            ID_Bahan            =   '$_POST[ID_Bahan]', 
            bahan_sendiri       =   '$_POST[bahan_sendiri]', 
            ukuran              =   '$_POST[Ukuran]', 
            ukuran_jadi         =   '$_POST[ukuran_yescom]', 
            panjang             =   '$_POST[Panjang]', 
            lebar               =   '$_POST[Lebar]', 
            cetak               =   '$_POST[Sisi]', 
            potong              =   '$_POST[Ptg_Pts]', 
            potong_gantung      =   '$_POST[Ptg_Gantung]', 
            pon                 =   '$_POST[Pon_Garis]', 
            perporasi           =   '$_POST[Perporasi]', 
            CuttingSticker      =   '$_POST[CuttingSticker]', 
            Hekter_Tengah       =   '$_POST[Hekter_Tengah]', 
            Blok                =   '$_POST[Blok]', 
            Spiral              =   '$_POST[Spiral]', 
            leminate            =   '$_POST[Laminating]', 
            finishing           =   '$_POST[Notes]', 
            qty                 =   '$_POST[Qty]', 
            qty_jadi            =   '$_POST[qty_yescom]', 
            satuan              =   '$_POST[Satuan]', 
            urgent              =   '$_POST[urgent]',
            Proffing            =   '$_POST[Proffing]',
            Ditunggu            =   '$_POST[Ditunggu]',
            warna               =   '$_POST[warna_cetakan]', 
            alat_tambahan       =   '$_POST[alat_tambahan]',
            alat_tambahan       =   '$_POST[alat_tambahan]',
            log                 =   CONCAT('$Final_log', log),
            akses_edit          =   'N'
        WHERE
            wio				            =   '$_POST[id_Order]'
        ";
    elseif($_POST['jenis_submit']=='Submit_Generator_Code') :
        // '$array[0]' => wio
        // '$array[1]' => kode
        // '$array[2]' => wo_color
        // '$array[3]' => send_via
        // '$array[4]' => marketing
        // '$array[5]' => id
        // '$array[6]' => so
        // '$array[7]' => client
        // '$array[8]' => project
        // '$array[9]' => ID_Bahan
        // '$array[10]' => ukuran
        // '$array[11]' => ukuran_jadi
        // '$array[12]' => panjang
        // '$array[13]' => lebar
        // '$array[14]' => cetak
        // '$array[15]' => potong
        // '$array[16]' => potong_gantung
        // '$array[17]' => pon
        // '$array[18]' => perporasi
        // '$array[19]' => CuttingSticker
        // '$array[20]' => Hekter_Tengah
        // '$array[21]' => Blok
        // '$array[22]' => Spiral
        // '$array[23]' => leminate
        // '$array[24]' => finishing
        // '$array[25]' => qty
        // '$array[26]' => qty_jadi
        // '$array[27]' => satuan
        // '$array[28]' => urgent
        // '$array[29]' => send_by
        // '$array[30]' => warna
        // '$array[31]' => alat_tambahan
        // '$array[32]' => date_create
        // '$array[33]' => generate_date
        // '$array[34]' => so_date
        // '$array[35]' => deadline
        // '$array[36]' => additional_charge
        // '$array[37]' => harga
        // '$array[38]' => ppn
        // '$array[39]' => ds
        // '$array[40]' => cs
        // '$array[41]' => shipto
        // '$array[42]' => Proffing
        // '$array[43]' => Ditunggu
        // '$array[44]' => nama_barang

        $generator1     = str_replace("////// ACTION START ---->>>","",$_POST['generator']);
        $generator2     = str_replace("<<<---- ACTION END //////","",$generator1);
        $encoded 		= str_rot13($generator2);
        $array			= explode("*_*","$encoded");

        $description	= htmlentities($array[8], ENT_QUOTES);
        $keterangan		= htmlentities($array[24], ENT_QUOTES);
        $client_yes		= htmlentities($array[7], ENT_QUOTES);
        $qty_jadi		= htmlentities($array[26], ENT_QUOTES);
        $ukuran_jadi	= htmlentities($array[11], ENT_QUOTES);
        
        $description_F	= str_replace("amp;","",$description);
        $keterangan_F	= str_replace("amp;","",$keterangan);
        $client_F		= str_replace("amp;","",$client_yes);

        $array_Log = array (
            "wio" => "$array[0]",
            "Kode barang" => "$array[1]",
            "Warna WO" => "$array[2]",
            "AE Yes" => "$array[4]",
            "ID Yes" => "$array[5]",
            "SO Yes" => "$array[6]",
            "Nama Client" => "$array[7]",
            "Deskripsi" => "$array[8]",
            "Nama Bahan" => "$array[44]",
            "Bahan Sendiri" => "$array[45]",
            "Ukuran" => "$array[10]",
            "Ukuran Yes" => "$array[11]",
            "Panjang" => "$array[12]",
            "Lebar" => "$array[13]",
            "Sisi" => "$array[14]",
            "Potong Putus" => "$array[15]",
            "Potong Gantung" => "$array[16]",
            "Pon Garis" => "$array[17]",
            "Perporasi" => "$array[18]",
            "Cutting Stiker" => "$array[19]",
            "Hekter Tengah" => "$array[20]",
            "Blok" => "$array[21]",
            "Spiral" => "$array[22]",
            "Laminating" => "$array[23]",
            "Notes / Finishing LF" => "$array[24]",
            "Qty" => "$array[25]",
            "Satuan" => "$array[27]",
            "Qty Yes" => "$array[26]",
            "Urgent" => "$array[28]",
            "WO List Dibuat Oleh" => "$array[29]",
            "Warna Cetak" => "$array[30]",
            "Alat Tambahan" => "$array[31]",
            "Tanggal Buka WO List" => "$array[32]",
            "Tanggal Generate" => "$array[33]",
            "Tanggal SO" => "$array[34]",
            "Deadline" => "$array[35]",
            "Additional Charge Yes" => "$array[36]",
            "Harga Yes" => "$array[37]",
            "PPN Yes" => "$array[38]",
            "Designer Yes" => "$array[39]",
            "Creative Support Yes" => "$array[40]",
            "Ship to" => "$array[41]",
            "Proffing" => "$array[42]",
            "Ditunggu" => "$array[43]"
        );

        $log ="";

        foreach($array_Log as $key => $value ) {
            if($value!="" && $value!="N" && $value!="0") {
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
        
        $sql = 
        "INSERT into penjualan (
            kode,
            jenis_wo,
            send_by,
            marketing,
            id_yes,
            so_yes,
            client_yes,
            description,
            ID_Bahan,
            ukuran,
            ukuran_jadi,
            panjang,
            lebar,
            sisi,
            potong,
            potong_gantung,
            pon,
            perporasi,
            CuttingSticker,
            Hekter_Tengah,
            Blok,
            Spiral,
            laminate,
            keterangan,
            qty,
            qty_jadi,
            satuan,
            urgent,
            CS_Generate,
            warna_cetak,
            alat_tambahan,
            date_create,
            DateSO_Yes,
            dead_line,
            additional_charge_YES,
            harga_YES,
            ppn_YES,
            designer_YES,
            cs_YES,
            Shipto_YES,
            Proffing,
            ditunggu,
            bahan_sendiri,
            client,
            sales,
            history
        ) values (
            '$array[1]', 
            '$array[2]', 
            '$array[3]', 
            '$array[4]', 
            '$array[5]', 
            '$array[6]', 
            '$client_F', 
            '$description_F', 
            '$array[9]', 
            '$array[10]',
            '$ukuran_jadi',
            '$array[12]',
            '$array[13]',
            '$array[14]',
            '$array[15]',
            '$array[16]',
            '$array[17]',
            '$array[18]',
            '$array[19]',
            '$array[20]',
            '$array[21]',
            '$array[22]',
            '$array[23]',
            '$keterangan_F',
            '$array[25]',
            '$qty_jadi',
            '$array[27]',
            '$array[28]',
            '$array[29]',
            '$array[30]',
            '$array[31]',
            '$array[33]',
            '$array[34]',
            '$array[35]',
            '$array[36]',
            '$array[37]',
            '$array[38]',
            '$array[39]',
            '$array[40]',
            '$array[41]',
            '$array[42]',
            '$array[43]',
            '$array[45]',
            '1',
            '$_SESSION[uid]',
            '$Final_log'
        )
        ";
    elseif($_POST['jenis_submit']=='delete_PenjualanYes') :

        if($_POST['status_PenjualanYES'] == "Y") : 
            $status_pricelist = "";
            $detail_log = "<b>Hapus</b> : N <i class=\"far fa-angle-double-right\"></i> Y";
        else : 
            $status_pricelist = "Y";
            $detail_log = "<b>Hapus</b> : Y <i class=\"far fa-angle-double-right\"></i> N";
        endif;

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>". $_SESSION['username'] ." Hapus data</td>
                <td>$detail_log</td>
            </tr>
        ";

        $sql = 
        "UPDATE
            penjualan
        SET
            cancel			= '$status_pricelist',
            history         =  CONCAT('$Final_log', history)
        WHERE
            oid				= '$_POST[PenjualanYES_ID]'
        ";
    elseif($_POST['jenis_submit']=='Update_PenjualanYESCOM') :

        $sql_Update =
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
                penjualan.client_yes as Nama_Client,
                penjualan.ukuran as Ukuran,
                penjualan.panjang as Panjang,
                penjualan.lebar as Lebar,
                penjualan.sisi as Sisi,
                penjualan.ID_Bahan,
                Bahan.nama_barang as Nama_Bahan,
                penjualan.bahan_sendiri as Bahan_Sendiri,
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
                penjualan.urgent as Urgent,
                penjualan.ditunggu as Ditunggu,
                penjualan.Proffing,
                penjualan.b_digital AS Biaya_Digital,
                penjualan.b_kotak AS Biaya_Kotak,
                penjualan.b_potong AS Biaya_Potong,
                penjualan.b_large AS Biaya_Large,
                penjualan.b_indoor AS Biaya_Indoor,
                penjualan.b_xbanner AS Biaya_Xbanner,
                penjualan.b_lain AS Biaya_Lain,
                penjualan.b_laminate AS Biaya_Laminate,
                penjualan.jenis_wo as Jenis_WO,
                penjualan.id_yes as ID_Yes,
                penjualan.so_yes as So_Yes,
                penjualan.warna_cetak as Warna_Cetak
            FROM
                penjualan
            LEFT JOIN 
                (select barang.id_barang, barang.nama_barang from barang) Bahan
            ON
                penjualan.ID_Bahan = Bahan.id_barang  
            WHERE
                penjualan.oid = '$_POST[id_Order]'
        ";

        $result = $conn_OOP -> query($sql_Update);
        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
        endif;
        
        if($_POST['panjang']=="") { $Panjang = "0"; } else { $Panjang = "$_POST[panjang]"; }
        if($_POST['lebar']=="") { $Lebar = "0"; } else { $Lebar = "$_POST[lebar]"; }

        $array = array (
            "Kode_barang"                  => "$_POST[Desc_Kode_Brg]",
            "Jenis_WO"                     => "$_POST[wo_yescom]",
            "ID_Yes"                       => "$_POST[id_yescom]",
            "So_Yes"                       => "$_POST[so_yescom]",
            "Nama_Client"                  => "$Nama_Client",
            "Deskripsi"                    => "$Deskripsi",
            "Ukuran"                       => "$_POST[ukuran]",
            "Panjang"                      => "$Panjang",
            "Lebar"                        => "$Lebar",
            "Sisi"                         => "$_POST[Sisi]",
            "Nama_Bahan"                   => "$Nama_Bahan",
            "Bahan_Sendiri"                => "$_POST[bahan_sendiri]",
            "Notes"                        => "$Notes",
            "Laminating"                   => "$_POST[Desc_Laminating]",
            "Alat_Tambahan"                => "$_POST[Desc_alat_tambahan]",
            "Warna_Cetak"                  => "$_POST[warna_cetakan]",
            "Potong_Putus"                 => "$_POST[Ptg_Pts]",
            "Potong_Gantung"               => "$_POST[Ptg_Gantung]",
            "Pon_Garis"                    => "$_POST[Pon_Garis]",
            "Perporasi"                    => "$_POST[Perporasi]",
            "Cutting_Stiker"               => "$_POST[CuttingSticker]",
            "Hekter_Tengah"                => "$_POST[Hekter_Tengah]",
            "Blok"                         => "$_POST[Blok]",
            "Spiral"                       => "$_POST[Spiral]",
            "Qty"                          => "$_POST[qty]",
            "Satuan"                       => "$Satuan",
            "Urgent"                       => "$_POST[urgent]",
            "Proffing"                     => "$_POST[Proffing]",
            "Ditunggu"                     => "$_POST[Ditunggu]",
            "Biaya_Digital"                => "$_POST[b_digital]",
            "Biaya_Kotak"                  => "$_POST[b_kotak]",
            "Biaya_Potong"                 => "$_POST[b_finishing]",
            "Biaya_Large"                  => "$_POST[b_large]",
            "Biaya_Indoor"                 => "$_POST[b_indoor]",
            "Biaya_Xbanner"                => "$_POST[b_xbanner]",
            "Biaya_Lain"                   => "$_POST[b_lain]",
            "Biaya_Laminate"               => "$_POST[b_laminate]"
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

        $sql_pertama =
        "UPDATE
            penjualan
        SET
            kode                    = '$_POST[Kode_Brg]',
            jenis_wo                = '$_POST[wo_yescom]',
            id_yes                  = '$_POST[id_yescom]',
            so_yes                  = '$_POST[so_yescom]',
            client_yes              = '$Nama_Client',
            description             = '$Deskripsi',
            ID_Bahan                = '$_POST[id_bahan]',
            bahan_sendiri           = '$_POST[bahan_sendiri]',
            ukuran                  = '$_POST[ukuran]',
            panjang                 = '$Panjang',
            lebar                   = '$Lebar',
            sisi                    = '$_POST[Sisi]',
            potong                  = '$_POST[Ptg_Pts]',
            potong_gantung          = '$_POST[Ptg_Gantung]',
            pon                     = '$_POST[Pon_Garis]',
            perporasi               = '$_POST[Perporasi]',
            CuttingSticker          = '$_POST[CuttingSticker]',
            Hekter_Tengah           = '$_POST[Hekter_Tengah]',
            Blok                    = '$_POST[Blok]',
            Spiral                  = '$_POST[Spiral]',
            laminate                = '$_POST[Laminating]',
            keterangan              = '$Notes',
            qty                     = '$_POST[qty]',
            satuan                  = '$Satuan',
            urgent                  = '$_POST[urgent]',
            warna_cetak             = '$_POST[warna_cetakan]',
            alat_tambahan           = '$_POST[alat_tambahan]',
            Proffing                = '$_POST[Proffing]',
            ditunggu                = '$_POST[Ditunggu]',
            history                 = CONCAT('$Final_log', history)
        WHERE
            oid				        = '$_POST[id_Order]'
        ";

        if ($conn->multi_query($sql_pertama) === TRUE) {
            echo "Records inserted or Update successfully.";
        } else {
            echo "<b class='text-danger'>ERROR: Could not able to execute<br> $sql_pertama <br>" . mysqli_error($conn) . "</br>";
        }

        if($_POST['no_invoice']!="0") {
            $sql_Price = 
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
                        WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                        WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                        WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                        WHEN ( kode = 'large format' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
                        WHEN ( kode = 'large format' ) and sisi = '1' and qty < 1 THEN ( 1sd2m ) / test
                        ELSE '0'
                    END) as b_lf,
                    (CASE
                        WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * Uk_PxL )
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
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 500 THEN 500_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 250 THEN 250_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 100 THEN 100_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 50 THEN 50_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 20 THEN 20_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 10 THEN 10_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 6 THEN 6sd9_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 3 THEN 3sd5_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 2 THEN 2_lembar_AT
                        WHEN ( kode = 'large format' or kode = 'Xuli' or kode = 'indoor' ) and ID_AT != '31' and test >= 1 THEN 1_lembar_AT
                        ELSE '0'
                    END) as b_AlatTambahan,
                    (CASE
                        WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 50 THEN ( 50m_Cutting * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 10 THEN ( 10m_Cutting * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 3 THEN ( 3sd9m_Cutting * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty >= 1 THEN ( 1sd2m_Cutting * Uk_PxL )
                        WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and qty < 1 THEN ( 1sd2m_Cutting / test )
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
                        WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                        WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'lembar' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2),0)
                        WHEN laminate = 'kilat1' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                        WHEN laminate = 'kilat2' and leminating_kilat and satuan = 'kotak' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2)*4,0)
                        WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'lembar' THEN 750
                        WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'lembar' THEN 1500
                        WHEN laminate = 'doff1'and leminating_doff >=20 and satuan = 'kotak' THEN 750*4
                        WHEN laminate = 'doff2' and leminating_doff >=20 and satuan = 'kotak' THEN 1500*4
                        WHEN laminate = 'doff1' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                        WHEN laminate = 'doff2' and leminating_doff and satuan = 'lembar' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2),0)
                        WHEN laminate = 'doff1' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                        WHEN laminate = 'doff2' and leminating_doff and satuan = 'kotak' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2)*4,0)
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
                        ((penjualan.panjang * penjualan.lebar)/10000) as Uk_PxL,
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

            $data = mysqli_query($conn, $sql_Price);
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
        } else {
            $sql = "SELECT * FROM penjualan limit 1";
        }

    elseif($_POST['jenis_submit']=='acc_penjualan') :

            $Final_log = "
                <tr>
                    <td>$hr, $timestamps</td>
                    <td>". $_SESSION['username'] ." mengubah data</td>
                    <td><b>Status ACC</b> : Y</td>
                </tr>
            ";
            
            // Attempt Update Cancel query execution
            $sql = 
            "UPDATE
                penjualan
            SET
                acc             = 'Y',
                history         =  CONCAT('$Final_log', history)
            WHERE
                oid				= '$_POST[oid]'
            ";

    endif;
    
    if ($conn->multi_query($sql) === TRUE) {
        echo "New records created successfully.";
    } else {
        if (mysqli_query($conn, $sql)){
            echo "Records inserted or Update successfully. $sql";
        } else{
            echo "<b class='text-danger'>ERROR: Could not able to execute<br> $sql <br>" . mysqli_error($conn) . "</br>";
        }
    }
    
    // Close connection
    $conn -> close();
?>