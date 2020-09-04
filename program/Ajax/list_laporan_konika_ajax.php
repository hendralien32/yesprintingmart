<?php
session_start();
require_once "../../function.php";
?>

<table>
    <tr>
        <th width="8%" rowspan="2">Tanggal</th>
        <th width="20%" colspan="3">Counter Awal</th>
        <th width="12%" colspan="2">Qty Print</th>
        <th width="16%" colspan="3">Error</th>
        <th width="12%" colspan="2">Maintanance</th>
        <th width="6%" rowspan="2">Total Print</th>
        <th width="20%" colspan="3">Counter Akhir</th>
        <th width="12%" colspan="2">Selisih</th>
    </tr>
    <tr>
        <th width="7%">FC</th>
        <th width="7%">BW</th>
        <th width="7%">Total</th>
        <th width="6%">FC</th>
        <th width="6%">BW</th>
        <th width="5%">FC</th>
        <th width="5%">BW</th>
        <th width="6%">%</th>
        <th width="6%">FC</th>
        <th width="6%">BW</th>
        <th width="7%">FC</th>
        <th width="7%">BW</th>
        <th width="7%">Total</th>
        <th width="6%">FC</th>
        <th width="6%">BW</th>
    </tr>
    <?php
    if ($_POST['date_from'] != "" and $_POST['date_to'] == "") {
        $date_validation = "billing_konika.tanggal_billing>='$_POST[date_from]-01' and billing_konika.tanggal_billing <='$_POST[date_from]-31'";
    } elseif ($_POST['date_from'] == "" and $_POST['date_to'] != "") {
        $date_validation = "billing_konika.tanggal_billing>='$_POST[date_to]-01' and billing_konika.tanggal_billing <='$_POST[date_to]-31'";
    } elseif ($_POST['date_from'] != "" and $_POST['date_to'] != "") {
        $date_validation = "billing_konika.tanggal_billing>='$_POST[date_from]-01' and billing_konika.tanggal_billing<='$_POST[date_to]-31'";
    } else {
        $date_validation = "billing_konika.tanggal_billing>='$months-01' and billing_konika.tanggal_billing<='$months-31'";
    }

    $sql =
        "SELECT
			billing_konika.billing_id,
			billing_konika.tanggal_billing,
			billing_konika.FC_awal,
			billing_konika.BW_awal,
			billing_konika.FC_akhir,
			billing_konika.BW_akhir,
            SUM(digital_printing.FC) as FC,
            SUM(digital_printing.BW) as BW,
            SUM(digital_printing.Maintanance_FC) as Maintanance_FC,
            SUM(digital_printing.Maintanance_BW) as Maintanance_BW,
            SUM(digital_printing.error_FC) as error_FC,
            SUM(digital_printing.error_BW) as error_BW
		FROM
            billing_konika
        LEFT JOIN
            (SELECT
                LEFT(digital_printing.tgl_cetak,10) tanggal_cetak,
                (case when digital_printing.color='FC' then (digital_printing.qty_cetak*digital_printing.sisi) end) as FC,
                (case when digital_printing.color='BW' then (digital_printing.qty_cetak*digital_printing.sisi) end) as BW,
                (case when digital_printing.maintanance='Y' and digital_printing.color='FC' then (digital_printing.qty_cetak*digital_printing.sisi) end) as Maintanance_FC,
				(case when digital_printing.maintanance='Y' and digital_printing.color='BW' then (digital_printing.qty_cetak*digital_printing.sisi) end) as Maintanance_BW,
				(case when digital_printing.error>0 and digital_printing.color='FC' then (digital_printing.error*digital_printing.sisi) end) as error_FC,
				(case when digital_printing.error>0 and digital_printing.color='BW' then (digital_printing.error*digital_printing.sisi) end) as error_BW
            FROM
                digital_printing
            ) digital_printing
        ON
            digital_printing.tanggal_cetak = billing_konika.tanggal_billing
		WHERE
			$date_validation
        GROUP BY
            billing_konika.tanggal_billing
		ORDER BY
			billing_konika.tanggal_billing
		DESC
    ";

    $a = "0";

    $result = $conn_OOP->query($sql);
    if ($result->num_rows > 0) :
        while ($d = $result->fetch_assoc()) :
            $total_print = $d['FC'] + $d['BW'] + $d['error_FC'] + $d['error_BW'] + $d['Maintanance_FC'] + $d['Maintanance_BW'];

            $total_error = $d['error_FC'] + $d['error_BW'];
            $total_cetak_maintanance = $d['FC'] + $d['BW'] + $d['Maintanance_FC'] + $d['Maintanance_BW'];

            if ($total_error != "0" and $total_cetak_maintanance != "0") {
                $persen = round((($total_error) / ($total_cetak_maintanance)) * 100, 2);
            } else {
                $persen = "0.00";
            }

            $billing_FC_Akhir = $d['FC_awal'] + $d['FC'] + $d['error_FC'] + $d['Maintanance_FC'];
            $billing_BW_Akhir = $d['BW_awal'] + $d['BW'] + $d['error_BW'] + $d['Maintanance_BW'];
            $selisih_FC = $billing_FC_Akhir - $d['FC_akhir'];
            $selisih_BW = $billing_BW_Akhir - $d['BW_akhir'];

            $style_selisih = "color:red";
            $style_0 = "color:green";
            if ($selisih_FC == "") {
                $style_FC = "$style_0";
            } else {
                $style_FC = "$style_selisih";
            }
            if ($selisih_BW == "") {
                $style_BW = "$style_0";
            } else {
                $style_BW = "$style_selisih";
            }

            echo "
				<tr onclick='LaodSubForm(\"counter_Mesin\", \"$d[billing_id]\")'>
			    	<td class='a-center pointer'><strong>" . date("d M Y", strtotime($d['tanggal_billing'])) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['FC_awal']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['BW_awal']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['FC_awal'] + $d['BW_awal']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['FC']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['BW']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['error_FC']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['error_BW']) . "</strong></td>
			    	<td class='a-center pointer'><strong>$persen %</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['Maintanance_FC']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['Maintanance_BW']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($total_print) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['FC_akhir']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['BW_akhir']) . "</strong></td>
			    	<td class='a-center pointer'><strong>" . number_format($d['FC_akhir'] + $d['BW_akhir']) . "</strong></td>
			    	<td class='a-center pointer'><strong style='$style_FC'>" . number_format($selisih_FC) . "</strong></td>
			    	<td class='a-center pointer'><strong style='$style_BW'>" . number_format($selisih_BW) . "</strong></td>
			    </tr>
            ";

            $sum_FC[]     = $d['FC'];
            $sum_BW[]     = $d['BW'];
            $sum_ErrorFC[]     = $d['error_FC'];
            $sum_ErrorBW[]     = $d['error_BW'];
            $sum_PersenE[]     = $persen;
            $sum_MaintananceFC[]     = $d['Maintanance_FC'];
            $sum_MaintananceBW[]     = $d['Maintanance_BW'];
            $sum_totalCTK[]     = $total_print;
        endwhile;

        $sum_FCx = array_sum($sum_FC);
        $sum_BWx = array_sum($sum_BW);
        $sum_ErrorFCx = array_sum($sum_ErrorFC);
        $sum_ErrorBWx = array_sum($sum_ErrorBW);
        $sum_PersenEx = array_sum($sum_PersenE);
        $sum_MaintananceFCx = array_sum($sum_MaintananceFC);
        $sum_MaintananceBWx = array_sum($sum_MaintananceBW);
        $sum_totalCTKx = array_sum($sum_totalCTK);
    ?>
        <tr>
            <th colspan="4">Total Hitungan Click A4</th>
            <th> <?= number_format($sum_FCx) ?></th>
            <th> <?= number_format($sum_BWx) ?></th>
            <th> <?= number_format($sum_ErrorFCx) ?></th>
            <th> <?= number_format($sum_ErrorBWx) ?></th>
            <th> <?= $sum_PersenEx ?> %</th>
            <th> <?= number_format($sum_MaintananceFCx) ?></th>
            <th> <?= number_format($sum_MaintananceBWx) ?></th>
            <th> <?= number_format($sum_totalCTKx) ?></th>
            <th colspan="5">
        </tr>
        <tr>
            <th colspan="4">Total Hitungan Click A3</th>
            <th> <?= number_format($sum_FCx / 2, 1) ?></th>
            <th> <?= number_format($sum_BWx / 2, 1) ?></th>
            <th> <?= number_format($sum_ErrorFCx / 2, 1) ?></th>
            <th> <?= number_format($sum_ErrorBWx / 2, 1) ?></th>
            <th> <?= $sum_PersenEx ?> %</th>
            <th> <?= number_format($sum_MaintananceFCx / 2, 1) ?></th>
            <th> <?= number_format($sum_MaintananceBWx / 2, 1) ?></th>
            <th> <?= number_format($sum_totalCTKx / 2, 1) ?></th>
            <th colspan="5">
        </tr>
    <?php
    else :

    endif;
    ?>
</table>