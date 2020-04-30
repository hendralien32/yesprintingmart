<?php
    session_start();
    require_once "../../function.php";

    if($_POST['type_client']!="") {
        $add_where = "and customer.level_client ='$_POST[type_client]'";
    } elseif($_POST['data']!="") {
        $add_where = "and ( customer.nama_client LIKE '%$_POST[data]%' or customer.no_telp LIKE '%$_POST[data]%' or customer.email LIKE '%$_POST[data]%' )";
    } else {
        $add_where = "";
    }
?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
    <table>
        <tbody>
            <tr>
                <th width="1%">#</th>
                <th width="8%" id="cid">ID Client</th>
                <th width="25%" id="client">Client</th>
                <th width="13%">No Telp</th>
                <th width="12%">Email</th>
                <th width="40%">Deskripsi</th>
                <th width="1%"></th>
            </tr>
            <?php
                $sql =
                "SELECT
                    customer.cid,
                    customer.nama_client,
                    customer.no_telp,
                    customer.email,
                    customer.alamat_kantor as deskripsi,
                    customer.level_client,
                    customer.status_client,
                    customer.special
                FROM
                    customer
                WHERE
                    customer.status_client = '$_POST[show_delete]'
                    $add_where
                ORDER BY
                    customer.cid
                DESC
                ";
                $no = 0;
                $result = $conn_OOP -> query($sql);
                if ($result->num_rows > 0) :
                    while ($row = $result->fetch_assoc()) :
                        $handphone = $row['no_telp'];
                        if(strlen($handphone)=="7") {
                            $telp_1=substr($handphone,0,3);
                            $telp_2=substr($handphone,3,4);
                            $no_telp="(061) ". $telp_1 . "-" .$telp_2;
                        } elseif(strlen($handphone)=="10") {
                            $telp_1=substr($handphone,0,4);
                            $telp_2=substr($handphone,4,3);
                            $telp_3=substr($handphone,7,3);
                            $no_telp=$telp_1 . "-". $telp_2 . "-" .$telp_3;
                        } elseif(strlen($handphone)=="11") {
                            $telp_1=substr($handphone,0,4);
                            $telp_2=substr($handphone,4,4);
                            $telp_3=substr($handphone,8,3);
                            $no_telp=$telp_1 . "-". $telp_2 . "-" .$telp_3;
                        }  elseif(strlen($handphone)=="12") {
                            $telp_1=substr($handphone,0,4);
                            $telp_2=substr($handphone,4,4);
                            $telp_3=substr($handphone,8,4);
                            $no_telp=$telp_1 . "-". $telp_2 . "-" .$telp_3;
                        } else {
                            $no_telp="$handphone";
                        }
                        
                        $no++;
                        $CID = sprintf("%05d",$row['cid']);

                        if($row['level_client']=="D1") : $status = "#228B22";
                        elseif($row['level_client']=="D2") : $status = "#4169E1";
                        elseif($row['level_client']=="D3") : $status = "#FF4500";
                        elseif($row['level_client']=="D4") : $status = "#FF0000";
                        else : $status = "#000000";
                        endif;

                        if($row['special']=="Y") {
                            $special = "<i class='fad fa-crown'></i>";
                        } else {
                            $special = "";
                        }
                        
                        if($row['status_client']=="T") :
                            $icon = "<i class='fas fa-undo-alt text-success'></i>";
                        else :
                            $icon = "<i class='far fa-trash-alt text-danger'></i>";
                        endif;

                        if($_SESSION['level']=="admin" or $_SESSION['level']=="CS" or $_SESSION['level']=="accounting") :
                            $edit = "LaodForm(\"database_client\", \"". $row['cid'] ."\")";
                        endif;

                        echo "
                        <tr class='pointer' onclick='". $edit ."'>
                            <td>$no</td>
                            <td>C-$CID</td>
                            <td><b style='color:$status;'>▐</b> $row[nama_client] $special</td>
                            <td>$no_telp</td>
                            <td>$row[email]</td>
                            <td>$row[deskripsi]</td>
                            <td class='pointer' ondblclick='hapus(\"". $row['cid'] ."\", \"". $row['nama_client'] ."\", \"". $row['status_client'] ."\")'>$icon</td>
                        </tr>
                        ";
                    endwhile;
                else :
                    echo "
                        <tr>
                            <td colspan='7'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
                        </tr>
                    ";
                endif;
            ?>
        </tbody>
    </table>