<?php

    session_start();
    require_once "../../function.php";

    $sql = 
        "SELECT
            penjualan.id_yes,
            penjualan.client_yes,
            penjualan.description,
            (CASE
                WHEN penjualan.so_yes != '' THEN penjualan.so_yes
                ELSE '-'
            END) as so_yes,
            penjualan.DateSO_Yes,
            (CASE
                WHEN penjualan.cs_YES != '' THEN penjualan.cs_YES
                ELSE '-'
            END) as cs_YES,
            (CASE
                WHEN penjualan.designer_YES != '' THEN penjualan.designer_YES
                ELSE '-'
            END) as designer_YES,
            penjualan.marketing,
            (CASE
                WHEN penjualan.Shipto_YES != '' THEN penjualan.Shipto_YES
                ELSE '-'
            END) as Shipto_YES,
            penjualan.CS_Generate,
            penjualan.ukuran_jadi,
            penjualan.qty_jadi,
            (CASE
                WHEN penjualan.dead_line != '' THEN penjualan.dead_line
                ELSE '-'
            END) as dead_line,
            penjualan.date_create,
            penjualan.ppn_YES as Tax,
            (penjualan.harga_YES + penjualan.ppn_YES + penjualan.additional_charge_YES ) as Nilai_Jual_Yes,
            (penjualan.b_digital+penjualan.b_xbanner+penjualan.b_lain+penjualan.b_large+penjualan.b_kotak+penjualan.b_laminate+penjualan.b_potong+penjualan.b_indoor) as Nilai_Jual_YesPrint
        FROM
            penjualan
        WHERE
            penjualan.oid = '$_POST[ID_Order]'
    ";
    $result = $conn_OOP -> query($sql);
    if ($result->num_rows > 0) :
        $row = $result->fetch_assoc();

        if($row['dead_line']!="-") {
            $deadline = date("d M Y",strtotime($row['dead_line']));
        } else {
            $deadline = "-";
        }

        $selisih = $row['Nilai_Jual_Yes'] - $row['Nilai_Jual_YesPrint'];

        if( $selisih > 0 ) {
            $nilai_selisih = "<span style='color:green'> ( Surplus + ". number_format($selisih) ." )</span>";
        } else {
            $nilai_selisih = "<span style='color:red'> ( Minus - ". number_format($selisih) ." )</span>";
        }

        $DateSO_Yes = new DateTime($row['DateSO_Yes']);
        $date_create = new DateTime($row['date_create']);

    endif;

?>

<h3 class='title_form'><?= 'Preview YES ID No. ' . $row['id_yes'] ?></h3>

<div class='row'>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:175px'>ID Yes</td>
                <td><?= $row['id_yes'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Date Send</td>
                <td><?= date("d M Y h:i",strtotime($row['date_create'])) . $DateSO_Yes->diff($date_create)->format("<b><i>( %d Hari, %h Jam : %i Menit )</i></b>"); ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Client</td>
                <td><?= $row['client_yes'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Deskripsi</td>
                <td><?= $row['description'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Ukuran</td>
                <td><?= $row['ukuran_jadi'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Qty</td>
                <td><?= $row['qty_jadi'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Deadline</td>
                <td><?= $deadline ?></td>
            </tr>
            <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="CS" || $_SESSION['level']=="accounting" ) : ?>
            <tr>
                <td style='width:175px'>@Harga Jual YES</td>
                <td><?= number_format($row['Nilai_Jual_Yes']) . '( Tax ' .number_format($row['Tax']) . ' )' ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="col-6">
        <table class='table-form'>
            <tr>
                <td style='width:175px'>SO YES</td>
                <td><?= $row['so_yes'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>SO Date</td>
                <td><?= date("d M Y h:i",strtotime($row['DateSO_Yes'])) ?></td>
            </tr>
            <tr>
                <td style='width:175px'>AE Yes</td>
                <td><?= $row['marketing'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>CS Yes</td>
                <td><?= $row['cs_YES'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>DS Yes</td>
                <td><?= $row['designer_YES'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>CS Generete Data</td>
                <td><?= $row['CS_Generate'] ?></td>
            </tr>
            <tr>
                <td style='width:175px'>Ship To</td>
                <td><?= $row['Shipto_YES'] ?></td>
            </tr>
            <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="CS" || $_SESSION['level']=="accounting" ) : ?>
            <tr>
                <td style='width:175px'>@Harga Jual YESPrint</td>
                <td><?= number_format($row['Nilai_Jual_YesPrint']) . $nilai_selisih ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>