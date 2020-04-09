<?php

    require_once "../../function.php";

    $tipe_validasi = isset($_POST['tipe_validasi']) ? $_POST['tipe_validasi'] : ' ';
    $term = isset($_POST['term']) ? $_POST['term'] : ' ';
    

    if($term!="" and $tipe_validasi=="autocomplete_client") {

        $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client, customer.no_telp FROM customer where customer.nama_client LIKE '%$_POST[term]%' and customer.status='A' LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } elseif ($term!="" and $tipe_validasi=="Search_client") {
        $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client, customer.level  FROM customer where customer.nama_client = '$_POST[term]' and customer.status='A'");

        $row = mysqli_fetch_assoc($result);
        
        echo mysqli_num_rows($result);

    } elseif ($term!="" and $tipe_validasi=="Search_bahan") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang = '$_POST[term]'");

        $row = mysqli_fetch_assoc($result);
        
        echo mysqli_num_rows($result);

    } elseif($term!="" and $tipe_validasi=="autocomplete_BahanDigital") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='KRTS' ORDER BY barang.nama_barang LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } elseif($term!="" and $tipe_validasi=="autocomplete_BahanLF") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='LF' ORDER BY barang.nama_barang LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } elseif($tipe_validasi=="AutoCalc_Price") {

        $sql_query =
            "SELECT
                bahan as ID_Bahan,
                Qty_Invoice,
                Qty_Sekarang
            FROM
                (   
                    SELECT
                        pricelist.bahan,
                        pricelist.sisi,
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
                        pricelist.1_kotak,
                        COALESCE(test.Qty_Invoice,0) as Qty_Invoice,
                        COALESCE(test.Qty_Invoice,0) + $_POST[Qty] as Qty_Sekarang,
                        total_laminate.leminating_kilat,
                        total_laminate.leminating_doff
                    FROM
                        pricelist
                    LEFT JOIN
                        (
                            SELECT
                                penjualan.oid,
                                sum(penjualan.qty) as Qty_Invoice,
                                penjualan.ID_Bahan
                            FROM
                                penjualan
                            WHERE
                                penjualan.no_invoice = $_POST[no_invoice] and
                                penjualan.ID_Bahan = $_POST[ID_Bahan] and
                                penjualan.oid != '$_POST[ID_Order]'
                            GROUP BY
                                penjualan.ID_Bahan, penjualan.sisi, penjualan.satuan
                        ) test
                    ON
                        pricelist.bahan = test.ID_Bahan
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
                            penjualan.no_invoice = $_POST[no_invoice] and
                            penjualan.oid != '$_POST[ID_Order]'
                        GROUP BY
                            penjualan.ID_Bahan
                        ) total_laminate
                    ON
                        pricelist.bahan = total_laminate.ID_Bahan
                    WHERE
                        pricelist.bahan  = $_POST[ID_Bahan] and
                        pricelist.sisi   = $_POST[Sisi]
                ) auto_check
        ";
  
        $arr_data['notes']= $sql_query;

        // $arr_data['b_digital']= "888";
        // $arr_data['b_kotak']= "888";
        // $arr_data['b_finishing']= "888";
        // $arr_data['b_large']= "888";
        // $arr_data['b_indoor']= "888";
        // $arr_data['b_xbanner']= "888";
        // $arr_data['b_laminate']= "888";
    
        echo json_encode($arr_data);
    }
?>  