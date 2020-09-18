<?php
session_start();
require_once "../../function.php";

$sql =
    "SELECT
        wo_list.wio,
        wo_list.kode,
        wo_list.wo_color,
        wo_list.send_via,
        wo_list.marketing,
        wo_list.id,
        wo_list.so,
        wo_list.client,
        wo_list.project,
        wo_list.ID_Bahan,
        barang.nama_barang,
        wo_list.ukuran,
        wo_list.ukuran_jadi,
        wo_list.panjang,
        wo_list.lebar,
        wo_list.cetak,
        (CASE
                WHEN wo_list.potong = 'Y' THEN 'Y'
                WHEN wo_list.potong = 'N' THEN 'N'
                ELSE 'N'
        END) as potong,
        (CASE
                WHEN wo_list.potong_gantung = 'Y' THEN 'Y'
                WHEN wo_list.potong_gantung = 'N' THEN 'N'
                ELSE 'N'
        END) as potong_gantung,
        (CASE
                WHEN wo_list.pon = 'Y' THEN 'Y'
                WHEN wo_list.pon = 'N' THEN 'N'
                ELSE 'N'
        END) as pon,
        (CASE
                WHEN wo_list.perporasi = 'Y' THEN 'Y'
                WHEN wo_list.perporasi = 'N' THEN 'N'
                ELSE 'N'
        END) as perporasi,
        (CASE
                WHEN wo_list.CuttingSticker = 'Y' THEN 'Y'
                WHEN wo_list.CuttingSticker = 'N' THEN 'N'
                ELSE 'N'
        END) as CuttingSticker,
        (CASE
                WHEN wo_list.Hekter_Tengah = 'Y' THEN 'Y'
                WHEN wo_list.Hekter_Tengah = 'N' THEN 'N'
                ELSE 'N'
        END) as Hekter_Tengah,
        (CASE
                WHEN wo_list.Blok = 'Y' THEN 'Y'
                WHEN wo_list.Blok = 'N' THEN 'N'
                ELSE 'N'
        END) as Blok,
        (CASE
                WHEN wo_list.Spiral = 'Y' THEN 'Y'
                WHEN wo_list.Spiral = 'N' THEN 'N'
                ELSE 'N'
        END) as Spiral,
        wo_list.leminate,
        wo_list.finishing,
        wo_list.qty,
        wo_list.qty_jadi,
        wo_list.satuan,
        wo_list.urgent,
        wo_list.send_by,
        wo_list.warna,
        wo_list.alat_tambahan,
        wo_list.date_create,
        wo_list.generate,
        wo_list.bahan_sendiri,
        (CASE
                WHEN wo_list.Proffing = 'Y' THEN 'Y'
                WHEN wo_list.Proffing = 'N' THEN 'N'
                ELSE 'N'
        END) as Proffing,
        (CASE
                WHEN wo_list.Ditunggu = 'Y' THEN 'Y'
                WHEN wo_list.Ditunggu = 'N' THEN 'N'
                ELSE 'N'
        END) as Ditunggu
    FROM
        wo_list
    LEFT JOIN 
        (select barang.id_barang, barang.nama_barang from barang) barang
    ON
        wo_list.ID_Bahan = barang.id_barang  
    WHERE
        wo_list.wio = '$_POST[ID_Order]'
    ";

$result = $conn_OOP->query($sql);

if ($result->num_rows > 0) :
    $row = $result->fetch_assoc();
endif;

$sql_Server =
    "SELECT
        workorder.so_date,
        workorder.deadline,
        workorder.additional_charge,
        workorder.harga,
        workorder.ppn,
        workorder.ds,
        workorder.cs,
        workorder.shipto
    FROM
        workorder
    WHERE
        workorder.idorder = '$_POST[AksesEdit]'
    ";

// Yesprintingmart
// $result_Server = $conn_OOP -> query($sql_Server);

// Yescom
$result_Server = $conn_Server->query($sql_Server);

if ($result_Server->num_rows > 0) :
    $row_Server = $result_Server->fetch_assoc();
endif;

$client            = htmlspecialchars($row['client'], ENT_QUOTES);
$project        = htmlspecialchars($row['project'], ENT_QUOTES);
$finishing        = htmlspecialchars(nl2br($row['finishing']), ENT_QUOTES);
$qty_jadi        = htmlspecialchars($row['qty_jadi'], ENT_QUOTES);
$ukuran_jadi    = htmlspecialchars($row['ukuran_jadi'], ENT_QUOTES);
$shipto         = htmlspecialchars(nl2br($row_Server['shipto']), ENT_QUOTES);

$generator = "$row[wio]*_*$row[kode]*_*$row[wo_color]*_*$row[send_via]*_*$row[marketing]*_*$row[id]*_*$row[so]*_*$client*_*$project*_*$row[ID_Bahan]*_*$row[ukuran]*_*$ukuran_jadi*_*$row[panjang]*_*$row[lebar]*_*$row[cetak]*_*$row[potong]*_*$row[potong_gantung]*_*$row[pon]*_*$row[perporasi]*_*$row[CuttingSticker]*_*$row[Hekter_Tengah]*_*$row[Blok]*_*$row[Spiral]*_*$row[leminate]*_*$finishing*_*$row[qty]*_*$qty_jadi*_*$row[satuan]*_*$row[urgent]*_*$row[send_by]*_*$row[warna]*_*$row[alat_tambahan]*_*$row[date_create]*_*$timestamps*_*$row_Server[so_date]*_*$row_Server[deadline]*_*$row_Server[additional_charge]*_*$row_Server[harga]*_*$row_Server[ppn]*_*$row_Server[ds]*_*$row_Server[cs]*_*$shipto*_*$row[Proffing]*_*$row[Ditunggu]*_*$row[nama_barang]*_*$row[bahan_sendiri]";
$generator_code = str_rot13($generator);

if ($_POST['ID_Order'] != "0") {
    $number_generator = $row['generate'] + 1;

    $Final_log = "
            <tr>
                <td>$hr, $timestamps</td>
                <td>" . $_SESSION['username'] . " Generate data</td>
                <td><b>Generate</b> : $row[generate] <i class=\"far fa-angle-double-right\"></i> $number_generator</td>
            </tr>
        ";

    $query =
        "UPDATE
            wo_list 
        set 
            generate        = '$number_generator', 
            generate_date   = '$timestamps', 
            log             =  CONCAT('$Final_log', log)
        where 
            wio = '$_POST[ID_Order]'
        ";

    if ($conn_OOP->multi_query($query) === TRUE) {
?>

        <div id='generator_container'>
            <h3 class='title_form'><?= $_POST['judul_form'] . "<br>WID : " . $_POST['ID_Order'] . " & No ID : " . $_POST['AksesEdit'] ?></h3>
            <textarea id="generator_select" readonly>////// ACTION START ---->>><?= $generator_code ?><<<---- ACTION END //////</textarea>
            <button type="button" id="button_copy" onclick="Copy_text()"><i class="fas fa-copy"></i> Copy Code</button>
        </div>

<?php
    } else {
        echo "ERROR Update Generate Data";
    }
}
?>