<?php
session_start();
require_once "../../function.php";

if ($_POST['search'] != "") {
    $add_where = "and ( wo_list.id LIKE '%$_POST[search]%' or wo_list.client LIKE '%$_POST[search]%' or wo_list.project LIKE '%$_POST[search]%' or wo_list.so LIKE '%$_POST[search]%' )";
} else {
    if ($_POST['warna_wo'] != '') :
        $show_WO_Color = "and wo_list.wo_color = '$_POST[warna_wo]'";
    else :
        $show_WO_Color = "";
    endif;

    if ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( wo_list.date_create, 10 )>='$_POST[Dari_Tanggal]' and LEFT( wo_list.date_create, 10 )<='$_POST[Ke_Tanggal]') $show_WO_Color";
    elseif ($_POST['Dari_Tanggal'] != "" and $_POST['Ke_Tanggal'] == "") :
        $add_where = "and (LEFT( wo_list.date_create, 10 )='$_POST[Dari_Tanggal]') $show_WO_Color";
    elseif ($_POST['Dari_Tanggal'] == "" and $_POST['Ke_Tanggal'] != "") :
        $add_where = "and (LEFT( wo_list.date_create, 10 )='$_POST[Ke_Tanggal]') $show_WO_Color";
    else :
        $add_where = "$show_WO_Color";
    endif;
}

if ($_POST['Check_box'] == 'Y') :
    $show_delete = "deleted";
else :
    $show_delete = "";
endif;
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
    <tbody>
        <tr>
            <th width="1%">#</th>
            <th width="3%">WID</th>
            <th width="3%">K</th>
            <th width="9%">Date</th>
            <th width="6%">ID</th>
            <th width="6%">SO</th>
            <th width="3%">Color</th>
            <th width="45%">Client - Deskripsi</th>
            <th width="7%">Generator</th>
            <th width="8%">CS</th>
            <th width="7%"></th>
        </tr>
        <?php
        $sql =
            "SELECT
                    wo_list.wio,
                    wo_list.kode,
                    LEFT(wo_list.kode, 1) as code,
                    wo_list.wo_color,
                    wo_list.id,
                    wo_list.so,
                    wo_list.client,
                    wo_list.project,
                    wo_list.cetak,
                    wo_list.warna,
                    LEFT(wo_list.date_create,10) as Tanggal,
                    wo_list.generate,
                    wo_list.send_by,
                    wo_list.status,
                    wo_list.akses_edit
                FROM
                    wo_list
                WHERE
                    wo_list.status = '$show_delete'
                    $add_where
                ORDER BY
                    wo_list.wio
                DESC
                ";
        $no = 0;
        $result = $conn_OOP->query($sql);
        if ($result->num_rows > 0) :
            while ($row = $result->fetch_assoc()) :
                $no++;
                $kode_class = str_replace(" ", "_", $row['kode']);

                if ($row['wo_color'] == "Kuning") :
                    $status = "#eed428";
                else :
                    $status = "#00ab34";
                endif;

                if ($row['warna'] == "FC") :
                    $warna = "style='color:white; padding:3px 8px 2px 8px; background-color:#f15a2c'";
                elseif ($row['warna'] == "BW") :
                    $warna = "style='color:white; padding:3px 8px 2px 8px; background-color:grey'";
                endif;

                if ($row['akses_edit'] == "Y") :
                    if ($_SESSION['level'] == "admin_yes" or $_SESSION['level'] == "admin") {
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"Y\", \"" . $row['wio'] . "\")'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "Y";
                    } else {
                        $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-open-alt'></i></span>";
                        $Akses_Edit = "$row[akses_edit]";
                    }
                else :
                    if ($_SESSION['level'] == "admin_yes" or $_SESSION['level'] == "admin") {
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"" . $row['wio'] . "\")'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "Y";
                    } else {
                        $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"" . $row['wio'] . "\")'><i class='fad fa-lock-alt'></i></span>";
                        $Akses_Edit = "$row[akses_edit]";
                        // $Akses_Edit = "Y";
                    }
                endif;

                if ($_SESSION['level'] == "admin_yes" or $_SESSION['level'] == "admin") :
                    if ($row['status'] == "deleted") :
                        $icon = "<i class='fas fa-undo-alt text-success'></i>";
                    else :
                        $icon = "<i class='far fa-trash-alt text-danger'></i>";
                    endif;

                    $Delete_icon = "<span class='icon_status' ondblclick='hapus(\"" . $row['wio'] . "\", \"" . $row['wio'] . "\", \"" . $row['status'] . "\")'>$icon</span>";
                else :
                    $Delete_icon = "";
                endif;

                $edit = "LaodForm(\"WO_List_yescom\", \"" . $row['wio'] . "\", \"" . $Akses_Edit . "\")";

                echo "
                        <tr class='pointer'>
                            <td>$no</td>
                            <td onclick='" . $edit . "' style='cursor:pointer'>$row[wio]</td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><center><span class='KodeProject " . $kode_class . "'>" . strtoupper($row['code']) . "</span></center></td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><center>" . date("d M Y", strtotime($row['Tanggal'])) . "</center></td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><center>$row[id]</center></td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><center>$row[so]</center></td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><span " . $warna . ">$row[warna]</span></td>
                            <td onclick='" . $edit . "' style='cursor:pointer'><b style='color:$status;'>▐</b> $row[client] - $row[project]</td>
                            <td onclick='LaodSubForm(\"generator_WoList\", \"" . $row['wio'] . "\", \"$row[id]\")'><center><input type='button' class='generate_button' value='Generate - $row[generate]'></center></td>
                            <td>$row[send_by]</td>
                            <td>
                            $icon_akses_edit
                            $Delete_icon
                            <span class='icon_status' onclick='LaodForm(\"log\", \"" . $row['wio'] . "\", \"wo_list\")'><i class='fad fa-file-alt'></i></span>
                            </td>
                        </tr>
                        ";
            endwhile;
        else :
            echo "
                        <tr>
                            <td colspan='11'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
        endif;
        ?>
    </tbody>
</table>

<div id="result"></div>