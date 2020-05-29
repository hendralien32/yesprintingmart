<?php
    session_start();
    require_once '../../function.php';

    if($_POST['search']!="") {
        $add_where = "and ( penjualan.description LIKE '%$_POST[data]%' or penjualan.client_yes LIKE '%$_POST[data]%' or penjualan.id_yes LIKE '%$_POST[data]%' or penjualan.so_yes LIKE '%$_POST[data]%')";
    } else {
        if($_POST['Dari_Tanggal']!="" and $_POST['Ke_Tanggal']!="") :
            $add_where="and (LEFT( penjualan.waktu, 10 )>='$_POST[Dari_Tanggal]' and LEFT( penjualan.waktu, 10 )<='$_POST[Ke_Tanggal]')";
        elseif($_POST['Dari_Tanggal']!="" and $_POST['Ke_Tanggal']=="") :
            $add_where="and (LEFT( penjualan.waktu, 10 )='$_POST[Dari_Tanggal]')";
        elseif($_POST['Dari_Tanggal']=="" and $_POST['Ke_Tanggal']!="") :
            $add_where="and (LEFT( penjualan.waktu, 10 )='$_POST[Ke_Tanggal]')";
        else :
            $add_where = "";
        endif;
    }

    $cari_keyword = $_POST['search'];
    $bold_cari_keyword = "<strong style='text-decoration:underline'>".$_POST['search']."</strong>";

?>

<center><img src="../images/0_4Gzjgh9Y7Gu8KEtZ.gif" width="150px" id="loader" style="display:none;"></center>
<table>
     <tbody>
        <tr>
            <th width="2%">#</th>
            <th width="7%">Tanggal</th>
            <th width="5%">ID</th>
            <th width="6%">SO</th>
            <th width="3%">K</th>
            <th width="38%">Client - Description</th>
            <th width="9%">Detail Icon</th>
            <th width="3%">S</th>
            <th width="10%">Bahan</th>
            <th width="8%">Qty</th>
            <th width="5%">Setter</th>
            <th width="4%"></th>
        </tr>
    </tbody>
    <?php
        $sql = 
        "SELECT
            penjualan.oid,
            penjualan.kode as kode_barang,
            penjualan.sisi,
            (CASE
                WHEN penjualan.sisi = '1' THEN 'satu'
                WHEN penjualan.sisi = '2' THEN 'dua'
                ELSE ''
            END) as css_sisi,
            (CASE
                WHEN penjualan.status = 'selesai' THEN 'Y'
                WHEN penjualan.status = '' THEN 'N'
                ELSE ''
            END) as Finished,
            (CASE
                WHEN penjualan.no_invoice != '' THEN 'Y'
                ELSE 'N'
            END) as no_invoice,
            (CASE
                WHEN barang.id_barang > 0 THEN barang.nama_barang
                ELSE penjualan.bahan
            END) as bahan,
            CONCAT(penjualan.qty, ' ' ,penjualan.satuan) as qty,
            (CASE
                WHEN penjualan.panjang > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                WHEN penjualan.lebar > 0 THEN CONCAT('Uk. ', penjualan.panjang, ' X ', penjualan.lebar, ' Cm')
                ELSE ''
            END) as ukuran,
            penjualan.ditunggu,
            penjualan.acc,
            penjualan.description,
            LEFT( penjualan.waktu, 10 ) as tanggal,
            LEFT(penjualan.kode, 1) as code,
            (CASE
                WHEN setter.nama != '' THEN setter.nama
                ELSE sales.nama
            END) as Nama_Setter,
            penjualan.cancel,
            (CASE
                WHEN penjualan.akses_edit = 'Y' THEN 'Y'
                WHEN penjualan.akses_edit = 'N' THEN 'N'
                ELSE 'N'
            END) as akses_edit,
            (CASE
                WHEN penjualan.acc = 'Y' THEN 'Y'
                WHEN penjualan.acc = 'N' THEN 'N'
                ELSE 'N'
            END) as acc,
            penjualan.jenis_wo,
            penjualan.client_yes,
            penjualan.id_yes,
            penjualan.so_yes
        from
            penjualan
        LEFT JOIN 
            (select barang.id_barang, barang.nama_barang from barang) barang
        ON
            penjualan.ID_Bahan = barang.id_barang  
        LEFT JOIN 
            (select pm_user.uid, pm_user.nama from pm_user) setter
        ON
            penjualan.setter = setter.uid  
        LEFT JOIN 
            (select pm_user.uid, pm_user.nama from pm_user) sales
        ON
            penjualan.sales = sales.uid  
        where
            penjualan.oid != '' and
            penjualan.client = '1' and 
            penjualan.cancel != 'Y'
            $add_where
        order by
            penjualan.oid
        desc
    ";

    $no = 0;

    // Perform query
    $result = $conn_OOP->query($sql);

    if ($result->num_rows > 0) :
        // output data of each row
        while($d = $result->fetch_assoc()) :
            $no++;
            $kode_class = str_replace(" ","_",$d['kode_barang']);
            
            if($d['jenis_wo']=="Kuning") : 
                $status = "#eed428";
            else : 
                $status = "#00ab34";
            endif;

            $array_kode = array( "ditunggu", "Finished", "acc", "no_invoice" );
            foreach($array_kode as $kode) {
                if($d[$kode]!="" && $d[$kode]!="N") : ${'check_'.$kode} = "active";
                else : ${'check_'.$kode} = "deactive";
                endif;
            }
            
            if($_SESSION['level']=="admin_yes" or $_SESSION['level']=="admin") :
                if($d['cancel']=="Y") :
                    $icon = "<i class='fas fa-undo-alt text-success'></i>";
                else :
                    $icon = "<i class='far fa-trash-alt text-danger'></i>";
                endif;

                $Delete_icon = "<span class='icon_status' ondblclick='hapus(\"". $d['oid'] ."\", \"". $d['oid'] ."\", \"". $d['cancel'] ."\")'>$icon</span>";
                $css_cancel = "";
            else :
                $Delete_icon ="";
                $css_cancel = "cancel";
            endif;

            if($d['akses_edit']=="Y") :
                if($_SESSION["level"] == "admin") { 
                    $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"Y\", \"". $d['oid'] ."\")'><i class='fad fa-lock-open-alt'></i></span>";
                    $Akses_Edit = "Y";
                } else { 
                    $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-open-alt'></i></span>";
                    $Akses_Edit = "$d[akses_edit]";
                }
            else :
                if($_SESSION["level"] == "admin") { 
                    $icon_akses_edit = "<span class='icon_status pointer' ondblclick='akses(\"N\", \"". $d['oid'] ."\")'><i class='fad fa-lock-alt'></i></span>";
                    $Akses_Edit = "Y";
                } else { 
                    $icon_akses_edit = "<span class='icon_status'><i class='fad fa-lock-alt'></i></span>";
                    $Akses_Edit = "$d[akses_edit]";
                }
            endif;

            $edit = "LaodForm(\"penjualan_yescom\", \"". $d['oid'] ."\", \"". $Akses_Edit ."\")";

            if($d['acc']=="N") :
                $acc = "acc_progress(\"". $d['id_yes'] . "/" . $d['so_yes'] . " | " . $d['client_yes'] . " - " . $d['description'] ."\", \"". $d['oid'] ."\")";
                $pointer = "pointer";
            else :
                $acc = "";
                $pointer = "";
            endif;

            echo "
            <tr>
                <td onclick='". $edit ."' class='pointer'>$no</td>
                <td onclick='". $edit ."' class='pointer'>". date("d M Y",strtotime($d['tanggal'] ))."</td>
                <td onclick='". $edit ."' class='pointer'><center>". str_ireplace($cari_keyword,$bold_cari_keyword,$d['id_yes']) ."</center></td>
                <td onclick='". $edit ."' class='pointer'><center>". str_ireplace($cari_keyword,$bold_cari_keyword,$d['so_yes']) ."</center></td>
                <td onclick='". $edit ."' class='pointer'><Center><span class='KodeProject ".$kode_class."'>". strtoupper($d['code']) ."</span></Center></td>
                <td onclick='". $edit ."' class='pointer'><b style='color:$status;'>▐</b> <strong>". str_ireplace($cari_keyword,$bold_cari_keyword,$d['client_yes']) ."</strong> - ". str_ireplace($cari_keyword,$bold_cari_keyword,$d['description']) ." $d[ukuran]</td>
                <td>
                    <center>
                        <span class='icon_status $pointer' ondblclick='$acc'><i class='fas fa-thumbs-up ". $check_acc ."'></i></span>
                        $icon_akses_edit
                        <span class='icon_status'><i class='fas fa-check-double ". $check_Finished ."'></i></span>
                        <span class='icon_status'><i class='fas fa-user-clock ". $check_ditunggu ."'></i></span>
                        <span class='icon_status'><i class='fas fa-receipt ". $check_no_invoice ."'></i>
                    </center>
                </td>
                <td onclick='". $edit ."' class='pointer'><center><span class='$d[css_sisi] KodeProject'>$d[sisi]</span></center></td>
                <td onclick='". $edit ."' class='pointer'>$d[bahan]</td>
                <td onclick='". $edit ."' class='pointer'>$d[qty]</td>
                <td onclick='". $edit ."' class='pointer'>$d[Nama_Setter]</td>
                <td>
                    $Delete_icon
                    <span class='icon_status' onclick='LaodForm(\"log\", \"". $d['oid'] ."\")'><i class='fad fa-file-alt'></i></span>
                 </td>
            </tr>
            ";
        
        endwhile;
    else :
        echo "
            <tr>
                <td colspan='13'><center><b><i class='far fa-empty-set'></i> Data Tidak Ditemukan <i class='far fa-empty-set'></i></b></center></td>
            </tr>
        ";
    endif;
    ?>
</table>