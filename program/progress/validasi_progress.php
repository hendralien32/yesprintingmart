<?php

require_once "../../function.php";

$tipe_validasi = isset($_POST['tipe_validasi']) ? $_POST['tipe_validasi'] : ' ';
$term = isset($_POST['term']) ? $_POST['term'] : ' ';


if ($term != "" and $tipe_validasi == "autocomplete_client") {

    $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client, customer.no_telp FROM customer where customer.nama_client LIKE '%$_POST[term]%' and customer.status_client='A' LIMIT 15");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = $row;
        }
        echo json_encode($json);
    }
} elseif ($term != "" and $tipe_validasi == "Search_client") {
    $result = mysqli_query($conn, "SELECT customer.cid, customer.nama_client FROM customer where customer.nama_client = '$_POST[term]'");

    $row = mysqli_fetch_assoc($result);

    echo mysqli_num_rows($result);
} elseif ($term != "" and $tipe_validasi == "Search_username") {
    $result = mysqli_query($conn, "SELECT pm_user.uid, pm_user.username FROM pm_user where pm_user.username = '$_POST[term]'");

    $row = mysqli_fetch_assoc($result);

    echo mysqli_num_rows($result);
} elseif ($term != "" and $tipe_validasi == "Search_bahan") {
    $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang = '$_POST[term]'");

    $row = mysqli_fetch_assoc($result);

    echo mysqli_num_rows($result);
} elseif ($term != "" and $tipe_validasi == "Search_bahanFC") {
    $result = mysqli_query(
        $conn,
        "SELECT 
            pricelist.price_id,
            barang.nama_barang 
        FROM 
            pricelist 
        LEFT JOIN
            (
                SELECT
                    barang.nama_barang,
                    barang.id_barang
                FROM
                    barang
            ) barang
        ON
            barang.id_barang = pricelist.bahan
        where 
            barang.nama_barang = '$_POST[term]' and 
            pricelist.sisi = '$_POST[Sisi]' and 
            pricelist.warna = '$_POST[Warna]' and 
            pricelist.jenis = '$_POST[kode_barng]'
        "
    );

    $row = mysqli_fetch_assoc($result);

    echo mysqli_num_rows($result);
} elseif ($term != "" and $tipe_validasi == "autocomplete_BahanDigital") {
    $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='KRTS' ORDER BY barang.nama_barang LIMIT 15");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = $row;
        }
        echo json_encode($json);
    }
} elseif ($term != "" and $tipe_validasi == "autocomplete_BahanLF") {
    $result = mysqli_query($conn, "SELECT barang.id_barang, barang.nama_barang FROM barang where barang.nama_barang LIKE '%$_POST[term]%' and barang.jenis_barang='LF' ORDER BY barang.nama_barang LIMIT 15");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = $row;
        }
        echo json_encode($json);
    }
} elseif ($tipe_validasi == "AutoCalc_Price") {
    if ($_POST['Kode_Brg'] == "large format" or $_POST['Kode_Brg'] == "indoor" or $_POST['Kode_Brg'] == "Xuli") :
        $ukuran  = (($_POST['Panjang'] * $_POST['Lebar']) / 10000);
        $qty = ($ukuran * $_POST['Qty']);
    else :
        $ukuran = 0;
        $qty = $_POST['Qty'];
    endif;

    if ($_POST['Laminating'] == "kilat1") : $Qty_Kilat = $_POST['Qty'] * 1;
    elseif ($_POST['Laminating'] == "kilat2") : $Qty_Kilat = $_POST['Qty'] * 2;
    else : $Qty_Kilat = 0;
    endif;

    if ($_POST['Laminating'] == "doff1") : $Qty_Doff = $_POST['Qty'] * 1;
    elseif ($_POST['Laminating'] == "doff2") : $Qty_Doff = $_POST['Qty'] * 2;
    else : $Qty_Doff = 0;
    endif;

    if ($_POST['alat_tambahan'] == "KotakNC") :
        $ID_AT = 31;
    elseif ($_POST['alat_tambahan'] == "RU_60") :
        $ID_AT = 65;
    elseif ($_POST['alat_tambahan'] == "RU_80") :
        $ID_AT = 66;
    elseif ($_POST['alat_tambahan'] == "RU_85") :
        $ID_AT = 67;
    elseif ($_POST['alat_tambahan'] == "Tripod") :
        $ID_AT = 68;
    elseif ($_POST['alat_tambahan'] == "Ybanner") :
        $ID_AT = 32;
    else :
        $ID_AT = 0;
    endif;

    if ($_POST['ID_CuttingStiker'] == "71") : $ID_CuttingStiker = 71;
    else : $ID_CuttingStiker = 0;
    endif;

    if ($_POST['Ptg_Pts'] == "Y" && ($_POST['Satuan'] == 'Lembar' || $_POST['Satuan'] == 'lembar')) : $Ptg_Pts = 500;
    elseif ($_POST['Ptg_Pts'] == "Y" && ($_POST['Satuan'] == 'Kotak' || $_POST['Satuan'] == 'kotak')) : $Ptg_Pts = 2000;
    else : $Ptg_Pts = 0;
    endif;

    if ($_POST['Ptg_Gantung'] == "Y") : $Ptg_Gantung = 500;
    else : $Ptg_Gantung = 0;
    endif;

    if ($_POST['Pon_Garis'] == "Y") : $Pon_Garis = 500;
    else : $Pon_Garis = 0;
    endif;

    if ($_POST['Perporasi'] == "Y") : $Perporasi = 500;
    else : $Perporasi = 0;
    endif;

    $potong = $Ptg_Pts + $Ptg_Gantung + $Pon_Garis + $Perporasi;

    $sql_query =
        "SELECT
                oid,
                kode,
                ID_Bahan_Order,
                Qty_FINAL,
                (CASE
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 500 THEN 500_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 250 THEN 250_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 100 THEN 100_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 50 THEN 50_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 20 THEN 20_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 10 THEN 10_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 6 THEN 6sd9_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 3 THEN 3sd5_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 2 THEN 2_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'lembar' and Qty_FINAL >= 1 THEN 1_lembar
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'kotak' and Qty_FINAL >= 20 THEN 20_kotak
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'kotak' and Qty_FINAL >= 2 THEN 2sd19_kotak
                    WHEN kode = 'digital' and ( Sisi_Order = '1' or Sisi_Order = '2' ) and Satuan_Order = 'kotak' and Qty_FINAL >= 1 THEN 1_kotak
                    ELSE '0'
                END) as b_digital,
                (CASE
                    WHEN ( kode = 'large format' ) and Sisi_Order = '1' and Qty_FINAL >= 50 THEN ( 50m * $ukuran )
                    WHEN ( kode = 'large format' ) and Sisi_Order = '1' and Qty_FINAL >= 10 THEN ( 10m * $ukuran )
                    WHEN ( kode = 'large format' ) and Sisi_Order = '1' and Qty_FINAL >= 3 THEN ( 3sd9m * $ukuran )
                    WHEN ( kode = 'large format' ) and Sisi_Order = '1' and Qty_FINAL >= 1 THEN ( 1sd2m * $ukuran )
                    WHEN ( kode = 'large format' ) and Sisi_Order = '1' and Qty_FINAL < 1 THEN ( 1sd2m ) / test
                    ELSE '0'
                END) as b_lf,
                (CASE
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and Sisi_Order = '1' and Qty_FINAL >= 50 THEN COALESCE(( 50m * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and Sisi_Order = '1' and Qty_FINAL >= 10 THEN COALESCE(( 10m * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and Sisi_Order = '1' and Qty_FINAL >= 3 THEN COALESCE(( 3sd9m * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and Sisi_Order = '1' and Qty_FINAL >= 1 THEN COALESCE(( 1sd2m * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' ) and Sisi_Order = '1' and Qty_FINAL < 1 THEN COALESCE(( 1sd2m / test),0)
                    ELSE '0'
                END) as indoor,
                (CASE
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and Satuan_Order = 'lembar' THEN 750
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and Satuan_Order = 'lembar' THEN 1500
                    WHEN laminate = 'kilat1'and leminating_kilat >=20 and Satuan_Order = 'kotak' THEN 750*4
                    WHEN laminate = 'kilat2' and leminating_kilat >=20 and Satuan_Order = 'kotak' THEN 1500*4
                    WHEN laminate = 'kilat1' and leminating_kilat and Satuan_Order = 'lembar' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat),0)
                    WHEN laminate = 'kilat2' and leminating_kilat and Satuan_Order = 'lembar' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2),0)
                    WHEN laminate = 'kilat1' and leminating_kilat and Satuan_Order = 'kotak' and leminating_kilat <=19 THEN ROUND((15000 / leminating_kilat)*4,0)
                    WHEN laminate = 'kilat2' and leminating_kilat and Satuan_Order = 'kotak' and leminating_kilat <=19 THEN ROUND(((15000 / leminating_kilat)*2)*4,0)
                    WHEN laminate = 'doff1'and leminating_doff >=20 and Satuan_Order = 'lembar' THEN 750
                    WHEN laminate = 'doff2' and leminating_doff >=20 and Satuan_Order = 'lembar' THEN 1500
                    WHEN laminate = 'doff1'and leminating_doff >=20 and Satuan_Order = 'kotak' THEN 750*4
                    WHEN laminate = 'doff2' and leminating_doff >=20 and Satuan_Order = 'kotak' THEN 1500*4
                    WHEN laminate = 'doff1' and leminating_doff and Satuan_Order = 'lembar' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff),0)
                    WHEN laminate = 'doff2' and leminating_doff and Satuan_Order = 'lembar' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2),0)
                    WHEN laminate = 'doff1' and leminating_doff and Satuan_Order = 'kotak' and leminating_doff <=19 THEN ROUND((15000 / leminating_doff)*4,0)
                    WHEN laminate = 'doff2' and leminating_doff and Satuan_Order = 'kotak' and leminating_doff <=19 THEN ROUND(((15000 / leminating_doff)*2)*4,0)
                    WHEN laminate = 'hard_lemit' THEN 10000
                    WHEN laminate = 'laminating_floor' and ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) THEN ( 40000 * $ukuran )
                    WHEN laminate = 'laminating_floor' and kode = 'digital' THEN 6300
                    WHEN ( laminate = 'kilatdingin1' or laminate = 'doffdingin1' ) and kode = 'digital' and Satuan_Order = 'lembar' THEN 5000
                    ELSE '0'
                END) as b_laminate,
                (CASE
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 500 THEN 500_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 250 THEN 250_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 100 THEN 100_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 50 THEN 50_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 20 THEN 20_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 10 THEN 10_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 6 THEN 6sd9_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 3 THEN 3sd5_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 2 THEN 2_lembar_AT
                    WHEN kode = 'digital' and ID_AT = '31' and Qty_FINAL >= 1 THEN 1_lembar_AT
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
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and Qty_FINAL >= 50 THEN COALESCE(( 50m_Cutting * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and Qty_FINAL >= 10 THEN COALESCE(( 10m_Cutting * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and Qty_FINAL >= 3 THEN COALESCE(( 3sd9m_Cutting * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and Qty_FINAL >= 1 THEN COALESCE(( 1sd2m_Cutting * $ukuran ),0)
                    WHEN ( kode = 'Xuli' or kode = 'indoor' or kode = 'large format' ) and ID_Cutting = '71' and Qty_FINAL < 1 THEN COALESCE(( 1sd2m_Cutting / test ),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 500 THEN COALESCE((500_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 250 THEN COALESCE((250_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 100 THEN COALESCE((100_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 50 THEN COALESCE((50_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 20 THEN COALESCE((20_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 10 THEN COALESCE((10_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 6 THEN COALESCE((6sd9_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 3 THEN COALESCE((3sd5_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 2 THEN COALESCE((2_lembar_Cutting + potong),0)
                    WHEN kode = 'digital' and ID_Cutting = '71' and test >= 1 THEN COALESCE((1_lembar_Cutting + potong),0)
                    ELSE COALESCE(potong,0)
                END) as b_potong
            FROM
                (   
                    SELECT
                        penjualan.oid,
                        (CASE
                            WHEN penjualan.satuan != '$_POST[Satuan]' THEN '$_POST[Satuan]'
                            ELSE penjualan.satuan
                        END) as Satuan_Order,
                        (COALESCE(invoice.Qty,0) + $qty) as Qty_FINAL,
                        (COALESCE(invoice.test,0) + $_POST[Qty]) as test,
                        (CASE
                            WHEN penjualan.sisi != '$_POST[Sisi]' THEN '$_POST[Sisi]'
                            ELSE penjualan.sisi
                        END) as Sisi_Order,
                        (CASE
                            WHEN penjualan.ID_Bahan != '$_POST[ID_Bahan]' THEN '$_POST[ID_Bahan]'
                            ELSE penjualan.ID_Bahan
                        END) as ID_Bahan_Order,
                        (CASE
                            WHEN penjualan.kode != '$_POST[Kode_Brg]' THEN '$_POST[Kode_Brg]'
                            ELSE penjualan.kode
                        END) as kode,
                        penjualan.ID_Bahan,
                        penjualan.sisi,
                        (CASE
                            WHEN penjualan.laminate != '$_POST[Laminating]' THEN '$_POST[Laminating]'
                            ELSE penjualan.laminate
                        END) as laminate,
                        (COALESCE(invoice.leminating_kilat,0) + $Qty_Kilat) as leminating_kilat,
                        (COALESCE(invoice.leminating_doff,0) + $Qty_Doff) as leminating_doff,
                        (CASE
                            WHEN penjualan.alat_tambahan != '' THEN '$ID_AT'
                            ELSE '$ID_AT'
                        END) as ID_AT,
                        (CASE
                            WHEN penjualan.CuttingSticker != '' THEN '$ID_CuttingStiker'
                            ELSE '$ID_CuttingStiker'
                        END) as ID_Cutting,
                        (CASE
                            WHEN penjualan.potong != '' THEN '$potong'
                            WHEN penjualan.potong_gantung != '' THEN '$potong'
                            WHEN penjualan.pon != '' THEN '$potong'
                            WHEN penjualan.perporasi != '' THEN '$potong'
                            ELSE '$potong'
                        END) as potong
                    FROM
                        penjualan
                    LEFT JOIN
                        (
                            SELECT
                                penjualan.no_invoice,
                                (CASE
                                    WHEN penjualan.kode = 'large format' or penjualan.kode = 'indoor' or penjualan.kode = 'Xuli' THEN sum(FORMAT((((penjualan.panjang * penjualan.lebar)/10000)  * penjualan.qty),3))
                                    ELSE sum(FORMAT(penjualan.qty,0))
                                END) as Qty,
                                sum(penjualan.qty) as test,
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
                                penjualan.oid != $_POST[ID_Order] and
                                penjualan.ID_Bahan = $_POST[ID_Bahan] and
                                penjualan.satuan = '$_POST[Satuan]'
                            GROUP BY
                                penjualan.no_invoice
                        ) invoice
                    ON 
                        penjualan.no_invoice = invoice.no_invoice
                    WHERE
                        penjualan.oid = $_POST[ID_Order]
                ) auto_check
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
                    auto_check.Sisi_Order = pricelist.sisi and auto_check.ID_Bahan_Order = pricelist.bahan and auto_check.kode = pricelist.jenis 
                LEFT JOIN 
                    (
                        SELECT
                            pricelist.sisi,
                            pricelist.bahan,
                            pricelist.jenis,
                            pricelist.1_lembar AS 1_lembar_AT,
                            pricelist.2_lembar AS 2_lembar_AT,
                            pricelist.3sd5_lembar AS 3sd5_lembar_AT,
                            pricelist.6sd9_lembar AS 6sd9_lembar_AT,
                            pricelist.10_lembar AS 10_lembar_AT,
                            pricelist.20_lembar AS 20_lembar_AT,
                            pricelist.50_lembar AS 50_lembar_AT,
                            pricelist.100_lembar AS 100_lembar_AT,
                            pricelist.250_lembar AS 250_lembar_AT,
                            pricelist.500_lembar AS 500_lembar_AT
                        FROM 
                            pricelist
                    ) pricelist1
                ON
                    auto_check.ID_AT = pricelist1.bahan
                LEFT JOIN 
                    (
                        SELECT
                            pricelist.sisi,
                            pricelist.bahan,
                            pricelist.jenis,
                            pricelist.1_lembar AS 1_lembar_Cutting,
                            pricelist.2_lembar AS 2_lembar_Cutting,
                            pricelist.3sd5_lembar AS 3sd5_lembar_Cutting,
                            pricelist.6sd9_lembar AS 6sd9_lembar_Cutting,
                            pricelist.10_lembar AS 10_lembar_Cutting,
                            pricelist.20_lembar AS 20_lembar_Cutting,
                            pricelist.50_lembar AS 50_lembar_Cutting,
                            pricelist.100_lembar AS 100_lembar_Cutting,
                            pricelist.250_lembar AS 250_lembar_Cutting,
                            pricelist.500_lembar AS 500_lembar_Cutting,
                            pricelist.1sd2m AS 1sd2m_Cutting,
                            pricelist.3sd9m AS 3sd9m_Cutting,
                            pricelist.10m AS 10m_Cutting,
                            pricelist.50m AS 50m_Cutting
                        FROM 
                            pricelist
                    ) Pricelist_Cutting
                ON
                    auto_check.ID_Cutting = Pricelist_Cutting.bahan and auto_check.kode = Pricelist_Cutting.jenis 
            GROUP BY
                oid
        ";

    $result = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $arr_data['b_digital'] = round($row['b_digital']);
        $arr_data['b_kotak'] = round($row['b_kotak']);
        $arr_data['b_finishing'] = round($row['b_potong']);
        $arr_data['b_large'] = round($row['b_lf']);
        $arr_data['b_indoor'] = round($row['indoor']);
        $arr_data['b_xbanner'] = round($row['b_AlatTambahan']);
        $arr_data['b_laminate'] = round($row['b_laminate']);
    }

    // echo "$sql_query";
    echo json_encode($arr_data);
} elseif ($tipe_validasi == "Auto_YesOrder_Data") {
    $sql =
        "SELECT 
            workorder.so,
            workorder.client,
            workorder.project,
            workorder.ukuran,
            workorder.bahan,
            (CASE
                WHEN workorder.cetak = '1/0' THEN '1'
                WHEN workorder.cetak = '2/0' THEN '1'
                WHEN workorder.cetak = '3/0' THEN '1'
                WHEN workorder.cetak = '4/0' THEN '1'
                WHEN workorder.cetak = '1/1' THEN '2'
                WHEN workorder.cetak = '2/1' THEN '2'
                WHEN workorder.cetak = '3/1' THEN '2'
                WHEN workorder.cetak = '4/1' THEN '2'
                WHEN workorder.cetak = '2/2' THEN '2'
                WHEN workorder.cetak = '3/2' THEN '2'
                WHEN workorder.cetak = '4/2' THEN '2'
                WHEN workorder.cetak = '3/3' THEN '2'
                WHEN workorder.cetak = '4/3' THEN '2'
                WHEN workorder.cetak = '4/4' THEN '2'
                ELSE '1'
            END) as sisi,
            CONCAT(workorder.qty, ' ', workorder.satuan) as qty,
            workorder.ae,
            workorder.finishing
        FROM 
            workorder 
        where 
            workorder.idorder = '$_POST[ID_YES]'
        ";
    // $result = $conn_Server -> query($sql);
    $result = $conn_OOP->query($sql);
    if ($result->num_rows > 0) :
        while ($row = $result->fetch_assoc()) :
            $arr_data['so'] = "$row[so]";
            $arr_data['client'] = "$row[client]";
            $arr_data['project'] = "$row[project]";
            $arr_data['ukuran'] = "$row[ukuran]";
            $arr_data['bahan'] = "$row[bahan]";
            $arr_data['sisi'] = "$row[sisi]";
            $arr_data['qty'] = "$row[qty]";
            $arr_data['ae'] = "$row[ae]";
            $arr_data['finishing'] = "$row[finishing]";
        endwhile;
        echo json_encode($arr_data);
    endif;
}
