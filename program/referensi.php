<?
	include "_autoboot.php";
	
	
	$q=mysql_query("
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
	$d=mysql_fetch_array($q);
	
	$q1=mysql_query("
		select
			no_invoice, invoice_date
		from
			penjualan
		where
			no_invoice = '$_POST[si]'
		group by
			no_invoice and invoice_date
		limit 1
	");
	$d1=mysql_fetch_array($q1);
	
	$nomor_akhir = "$d[no_invoice]";
	if($nomor_akhir=='') {$noinv = "100001";} else {
		if($_POST[si]!=null) {
			$noinv = "$_POST[si]";
			$waktu = "$d1[invoice_date]";
		} else {
			$noinv = $d[no_invoice]+1;
			$waktu = date("Y-m-d H:I:s");
		}
	}
	
	$list_yes = "$_POST[idy]";
	
	$reid = explode("," , "$list_yes");
	foreach($reid as $yes) {
		if($yes!="") { $y[] = "$yes"; }
	}
	$aid = implode("','", $y);
	$fix_yes = "'$aid'";
	
	$query = "
		update
			penjualan
		set
			no_invoice = '$noinv',
			invoice_date = '$waktu'
		where
			oid in ($fix_yes)
	";
	
	if($list_yes!="") {mysql_query("$query");}
	
	
	$list_no = "$_POST[idx]";
	
	$reid = explode("," , "$list_no");
	foreach($reid as $no) {
		if($no!="") { $n[] = "$no"; }
	}
	$aid = implode("','", $n);
	$fix_no = "'$aid'";
	
	$query = "
		update
			penjualan
		set
			no_invoice = '',
			invoice_date = '0000-00-00 00:00:00'
		where
			oid in ($fix_no)
	";
	
	
	if($list_yes!="") {echo "List yes = $list_yes<br>";}
	if($list_no!="")  {echo "List no = $list_no<br>";}
	
	echo "$query<br>";
	if($list_no!="")  {mysql_query("$query");}
	
?>



<?php
    session_start();
    
    require_once "../../function.php";

    // echo $_FILES['imageFile']['name']."<br>";
    // echo $_FILES['imageFile']['type']."<br>";
    // echo $_FILES['imageFile']['tmp_name']."<br>";
    // echo $_FILES['imageFile']['error']."<br>";
    // echo $_FILES['imageFile']['size']."<br>";

    if($_POST['jenis_submit']=='Insert' or $_POST['jenis_submit']=='Update') {
        $Deskripsi      = htmlspecialchars($_POST['Deskripsi'],ENT_QUOTES);
        $Notes          = htmlspecialchars($_POST['Notes'],ENT_QUOTES);
        $Satuan         = htmlspecialchars($_POST['Satuan'],ENT_QUOTES);
        $Nama_Client    = htmlspecialchars($_POST['Nama_Client'],ENT_QUOTES);
        $Nama_Bahan     = htmlspecialchars($_POST['Nama_Bahan'],ENT_QUOTES);
    } else {
        
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
            penjualan.img_design as Nama_Image
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
                "Design"                       => "$_POST[Design]"
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
            $mysql_FileValue
            $mysql_ImgValue
            history          =  CONCAT('$Final_log', history)
        WHERE 
            oid = $_POST[ID_Order]
        ;";
        
    } elseif($_POST['jenis_submit']=='Cancel') {

        $Alasan_Cancel     = htmlspecialchars($_POST['Alasan_Cancel'],ENT_QUOTES);

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>[ ". $_SESSION['username'] ." ] Cancel data</td>
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

    } elseif($_POST['jenis_submit']=='create_invoice') {

        $list_yes = "$_POST[idy]";
	
        $reid = explode("," , "$list_yes");
        foreach($reid as $yes) {
            if($yes!="") { $y[] = "$yes"; }
        }
        $aid = implode("','", $y);
        $fix_yes = "'$aid'";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $sql = 
            "SELECT
                oid,
                kode,
                ID_Bahan,
                nama_barang,
                sisi,
                Qty,
                satuan,
                (CASE
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 500 THEN 500_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 250 THEN 250_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 100 THEN 100_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 50 THEN 50_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 20 THEN 20_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 10 THEN 10_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 6 THEN 6_9_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 3 THEN 3_5_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 2 THEN 2_lembar
                    WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 1 THEN 1_lembar
                    WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 50 THEN 50m
                    WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 10 THEN 10m
                    WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 3 THEN 3_9m
                    WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 1 THEN 1_2m
                    ELSE '0'
                END) as harga
            FROM 
                (
                SELECT
                    GROUP_CONCAT(DISTINCT penjualan.oid) AS oid,
                    penjualan.kode,
                    penjualan.ID_Bahan,
                    barang.nama_barang,
                    penjualan.sisi,
                    pricelist.1_lembar,
                    pricelist.2_lembar,
                    pricelist.3_5_lembar,
                    pricelist.6_9_lembar,
                    pricelist.10_lembar,
                    pricelist.20_lembar,
                    pricelist.50_lembar,
                    pricelist.100_lembar,
                    pricelist.250_lembar,
                    pricelist.500_lembar,
                    pricelist.1_2m,
                    pricelist.3_9m,
                    pricelist.10m,
                    pricelist.50m,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT(sum(((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
                        ELSE FORMAT(sum(penjualan.qty),0)
                    END) AS Qty,
                    (CASE
                        WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN 'Meter'
                        ELSE LOWER(penjualan.satuan) 
                    END) AS satuan
                FROM
                    penjualan
                LEFT JOIN 
                    (select barang.id_barang, barang.nama_barang from barang) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang
                LEFT JOIN 
                    (
                    SELECT
                        pricelist.*
                    FROM 
                        pricelist
                    ) pricelist
                ON
                    penjualan.sisi = pricelist.sisi and penjualan.ID_Bahan = pricelist.bahan and penjualan.kode = pricelist.jenis
                WHERE
                    penjualan.oid IN ('$aid')
                ) test
            ";
        }

        // $data = mysqli_query($conn, $test);
        // while($d = mysqli_fetch_array($data)) {
        //     $xxx = 
        //     "UPDATE
        //         penjualan
        //     SET
        //         no_invoice = '88888888'
        //     WHERE
        //         oid LIKE '%$d[oid]%'
        //     ";
        // }

        // $sql = 
        //     "SELECT
        //         oid,
        //         kode,
        //         ID_Bahan,
        //         nama_barang,
        //         sisi,
        //         Qty,
        //         satuan,
        //         (CASE
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 500 THEN 500_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 250 THEN 250_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 100 THEN 100_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 50 THEN 50_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 20 THEN 20_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 10 THEN 10_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 6 THEN 6_9_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 3 THEN 3_5_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 2 THEN 2_lembar
        //             WHEN kode = 'digital' and ( sisi = '1' or sisi = '2' ) and Qty >= 1 THEN 1_lembar
        //             WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 50 THEN 50m
        //             WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 10 THEN 10m
        //             WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 3 THEN 3_9m
        //             WHEN kode = 'large format' or kode = 'Xuli' or kode = 'indoor' and sisi = '1' and Qty >= 1 THEN 1_2m
        //             ELSE '0'
        //         END) as harga
        //     FROM 
        //         (
        //         SELECT
        //             GROUP_CONCAT(penjualan.oid) as oid,
        //             penjualan.kode,
        //             penjualan.ID_Bahan,
        //             barang.nama_barang,
        //             penjualan.sisi,
        //             pricelist.1_lembar,
        //             pricelist.2_lembar,
        //             pricelist.3_5_lembar,
        //             pricelist.6_9_lembar,
        //             pricelist.10_lembar,
        //             pricelist.20_lembar,
        //             pricelist.50_lembar,
        //             pricelist.100_lembar,
        //             pricelist.250_lembar,
        //             pricelist.500_lembar,
        //             pricelist.1_2m,
        //             pricelist.3_9m,
        //             pricelist.10m,
        //             pricelist.50m,
        //             (CASE
        //                 WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN FORMAT(sum(((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3)
        //                 ELSE FORMAT(sum(penjualan.qty),0)
        //             END) as Qty,
        //             (CASE
        //                 WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN 'Meter'
        //                 ELSE LOWER(penjualan.satuan) 
        //             END) as satuan
        //         FROM
        //             penjualan
        //         LEFT JOIN 
        //             (select barang.id_barang, barang.nama_barang from barang) barang
        //         ON
        //             penjualan.ID_Bahan = barang.id_barang
        //         LEFT JOIN 
        //             (
        //             SELECT
        //                 pricelist.*
        //             FROM 
        //                 pricelist
        //             ) pricelist
        //         ON
        //             penjualan.sisi = pricelist.sisi and penjualan.ID_Bahan = pricelist.bahan and penjualan.kode = pricelist.jenis
        //         WHERE
        //             penjualan.oid IN ('$aid')
        //         GROUP BY
        //             penjualan.ID_Bahan, penjualan.sisi, LOWER(penjualan.satuan), LOWER(penjualan.kode)
        //         ) test
        //     ";
        // }

    if (mysqli_query($conn, $sql)){
        echo "Records inserted or Update successfully. <br> $sql";
    } else{
        echo "<b class='text-danger'>ERROR: Could not able to execute<br> $sql <br>" . mysqli_error($conn) . "</b>";
    }
    
    // Close connection
    mysqli_close($conn);



    <?
include "_autoboot.php";
		
	if(!empty($_POST['qty'])){
		if($_POST['satuan']=='lembar' or $_POST['satuan']=='Lembar' or $_POST['satuan']=='lbr') {
			if($_POST['leminate']=="kilat1" or $_POST['leminate']=="doff1") {
				if($_POST['qty']>="20") { $harga_leminate="750"; }
				elseif($_POST['qty']<="19") { $harga_leminate=15000/$_POST['qty']; }
				else { $harga_leminate="0"; }
			} elseif ($_POST['leminate']=="kilat2" or $_POST['leminate']=="doff2") {
				if($_POST['qty']>="10") { $harga_leminate="1500"; }
				elseif($_POST['qty']<="9") { $harga_leminate=15000/$_POST['qty']; }
				else { $harga_leminate="0"; }
			} elseif ($_POST['leminate']=="kilatdingin1" or $_POST['leminate']=="doffdingin1") {
				if($_POST['qty']>="50") { $harga_leminate="4000"; }
				elseif($_POST['qty']<="49") { $harga_leminate="5000"; }
				else { $harga_leminate="0"; }
			} elseif ($_POST['leminate']=="hard_lemit") {
				$harga_leminate="10000";
			} else {
				$harga_leminate="0";
			}
		} elseif($_POST['satuan']=='kotak' or $_POST['satuan']=='Kotak') {
			if($_POST['leminate']=="kilat1" or $_POST['leminate']=="doff1") {
				if($_POST['qty']>="5") { $harga_leminate="3000"; }
				elseif($_POST['qty']<="4") { $harga_leminate=15000/$_POST['qty']; }
				else { $harga_leminate="0"; }
			} elseif ($_POST['leminate']=="kilat2" or $_POST['leminate']=="doff2") {
				if($_POST['qty']>="3") { $harga_leminate="6000"; }
				elseif($_POST['qty']<="2") { $harga_leminate=15000/$_POST['qty']; }
				else { $harga_leminate="0"; }
			} else {
				$harga_leminate="0";
			}
		}
		print "<span style=\"color:green; font-weight:bold; margin-left:10px; cursor:pointer\" onclick='copy_dp($harga_leminate)'>@ Rp. ".number_format($harga_leminate)." / $_POST[satuan]</span>";
	} else {
		$harga_leminate="";
	}

?>
?>

<?php
    session_start();
    
    require_once "../../function.php";

    // echo $_FILES['imageFile']['name']."<br>";
    // echo $_FILES['imageFile']['type']."<br>";
    // echo $_FILES['imageFile']['tmp_name']."<br>";
    // echo $_FILES['imageFile']['error']."<br>";
    // echo $_FILES['imageFile']['size']."<br>";

    if($_POST['jenis_submit']=='Insert' or $_POST['jenis_submit']=='Update') {
        $Deskripsi      = htmlspecialchars($_POST['Deskripsi'],ENT_QUOTES);
        $Notes          = htmlspecialchars($_POST['Notes'],ENT_QUOTES);
        $Satuan         = htmlspecialchars($_POST['Satuan'],ENT_QUOTES);
        $Nama_Client    = htmlspecialchars($_POST['Nama_Client'],ENT_QUOTES);
        $Nama_Bahan     = htmlspecialchars($_POST['Nama_Bahan'],ENT_QUOTES);
    } else {
        
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
            penjualan.img_design as Nama_Image
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
                "Design"                       => "$_POST[Design]"
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
            $mysql_FileValue
            $mysql_ImgValue
            history          =  CONCAT('$Final_log', history)
        WHERE 
            oid = $_POST[ID_Order]
        ;";
        
    } elseif($_POST['jenis_submit']=='Cancel') {

        $Alasan_Cancel     = htmlspecialchars($_POST['Alasan_Cancel'],ENT_QUOTES);

        $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>[ ". $_SESSION['username'] ." ] Cancel data</td>
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

    } elseif($_POST['jenis_submit']=='create_invoice') {

        $list_yes = "$_POST[idy]";
	
        $reid = explode("," , "$list_yes");
        foreach($reid as $yes) {
            if($yes!="") { $y[] = "$yes"; }
        }
        $aid = implode("','", $y);
        $fix_yes = "'$aid'";

        $sql = 
            "SELECT
                oid,
                kode,
                ID_Bahan,
                nama_barang,
                ID_AlatTambahan,
                sisi,
                qty_order,
                satuan,
                qty AS qty_total,
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
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 50 THEN ( 50m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 10 THEN ( 10m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 3 THEN ( 3sd9m * qty ) / test
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and sisi = '1' and qty >= 1 THEN ( 1sd2m * qty ) / test
                    ELSE '0'
                END) as indoor,
                (potong+potong_gantung+pon+perporasi) AS b_potong,
                (CASE
                    WHEN ID_AlatTambahan = '31' and qty >= 500 THEN 500_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 250 THEN 250_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 100 THEN 100_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 50 THEN 50_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 20 THEN 20_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 10 THEN 10_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 6 THEN 6sd9_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 3 THEN 3sd5_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 2 THEN 2_lembar_AT
                    WHEN ID_AlatTambahan = '31' and qty >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_kotak,
                (CASE
                    WHEN ID_AlatTambahan != '32' and test >= 500 THEN 500_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 250 THEN 250_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 100 THEN 100_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 50 THEN 50_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 20 THEN 20_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 10 THEN 10_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 6 THEN 6sd9_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 3 THEN 3sd5_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 2 THEN 2_lembar_AT
                    WHEN ID_AlatTambahan != '32' and test >= 1 THEN 1_lembar_AT
                    ELSE '0'
                END) as b_AlatTambahan
            FROM 
                (
                SELECT
                    penjualan.oid,
                    penjualan.kode,
                    penjualan.ID_Bahan,
                    barang.nama_barang,
                    penjualan.sisi,
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
                    pricelist.1sd2m,
                    pricelist.3sd9m,
                    pricelist.10m,
                    pricelist.50m,
                    pricelist.20_kotak,
                    pricelist.2sd19_kotak,
                    pricelist.1_kotak,
                    barang.ID_AlatTambahan,
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
                    END) AS qty_order
                FROM
                    penjualan
                LEFT JOIN 
                    (SELECT 
                        barang.id_barang,
                        barang.nama_barang,
                        total_qty.qty,
                        total_qty.sisi,
                        total_qty.satuan as Satuan_Order,
                        total_qty.ID_AlatTambahan
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
                            END) as ID_AlatTambahan,
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
                    ) barang
                ON
                    penjualan.ID_Bahan = barang.id_barang
                LEFT JOIN 
                    (
                    SELECT
                        pricelist.*
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
                    barang.ID_AlatTambahan = pricelist1.bahan
                WHERE
                    penjualan.oid IN ('$aid') and
                    penjualan.ID_Bahan = barang.id_barang and
                    penjualan.sisi = barang.sisi and
                    penjualan.satuan = barang.Satuan_Order
                ) table_invoice
                ORDER BY
                   oid
                ASC
            ";
        }

    if (mysqli_query($conn, $sql)){
        echo "Records inserted or Update successfully. <br> $sql";
    } else{
        echo "<b class='text-danger'>ERROR: Could not able to execute<br> $sql <br>" . mysqli_error($conn) . "</b>";
    }
    
    // Close connection
    mysqli_close($conn);
?>

