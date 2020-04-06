<?php

    require_once "../../function.php";

    if($_POST['term']!="" and $_POST['tipe_validasi']=="autocomplete_client") {

        $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client, customer.no_telp FROM customer where customer.nama_client LIKE '%$_POST[term]%' and customer.status='A' LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } elseif ($_POST['term']!="" and $_POST['tipe_validasi']=="Search_client") {
        $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client, customer.level  FROM customer where customer.nama_client = '$_POST[term]' and customer.status='A'");

        $row = mysqli_fetch_assoc($result);
        
        echo mysqli_num_rows($result);

    } elseif ($_POST['term']!="" and $_POST['tipe_validasi']=="Search_bahan") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang = '$_POST[term]'");

        $row = mysqli_fetch_assoc($result);
        
        echo mysqli_num_rows($result);

    } elseif($_POST['term']!="" and $_POST['tipe_validasi']=="autocomplete_BahanDigital") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='KRTS' ORDER BY barang.nama_barang LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } elseif($_POST['term']!="" and $_POST['tipe_validasi']=="autocomplete_BahanLF") {
        $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='LF' ORDER BY barang.nama_barang LIMIT 15");
        
        if( mysqli_num_rows($result) > 0 ) {
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            echo json_encode($json);
        }
    } 
?>  