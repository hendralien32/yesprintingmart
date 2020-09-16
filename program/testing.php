<? include "_autoboot.php"; ?>

<table>
    <tr>
        <th width="2%">#</th>
        <th width="25%">Nama</th>
        <th width="10%">Kode</th>
        <th width="10%">jenis</th>
        <th width="9%">Min Stock</th>
        <th width="9%">Stock Lama</th>
        <th width="9%">Stock Masuk</th>
        <th width="9%">Stock Keluar</th>
        <th width="9%">Sisa Stock</th>
    </tr>

    <?
    	if($_POST[Cari_jenis]!="0") { $Cari_jenis = "barang.jenis_barang='$_POST[Cari_jenis]'"; 
    	} else { $Cari_jenis = "barang.jenis_barang!='' "; }

	    $query = "
			SELECT
				barang.id_barang,
				barang.nama_barang,
				barang.jenis_barang,
				barang.kode_barang,
				barang.min_stock,
				barang.satuan,
				flow_barang.barang_masuk,
				digital_printing.barang_keluar,
				old_digital_printing.barang_keluar_lama,
				old_flow_barang.barang_masuk_lama
			FROM
				barang
			left join 
				(select flow_barang.kode_barang, flow_barang.tanggal_masuk, sum(flow_barang.barang_masuk) as barang_masuk from flow_barang where left(flow_barang.tanggal_masuk, 7)>='$_POST[Dari_Bulan]' and left(flow_barang.tanggal_masuk, 7) <='$_POST[Ke_Bulan]' group by flow_barang.kode_barang) flow_barang
			on
				barang.kode_barang = flow_barang.kode_barang
			left join 
				(select flow_barang.kode_barang, flow_barang.tanggal_masuk, sum(flow_barang.barang_masuk) as barang_masuk_lama from flow_barang where left(flow_barang.tanggal_masuk, 7)<'$_POST[Dari_Bulan]' group by flow_barang.kode_barang) old_flow_barang
			on
				barang.kode_barang = old_flow_barang.kode_barang
			left join 
				(select digital_printing.kode_bahan, digital_printing.tgl_cetak, sum(digital_printing.qty_cetak+(digital_printing.qty_etc*2)+(digital_printing.jam*2)) as barang_keluar from digital_printing where left(digital_printing.tgl_cetak, 7)>='$_POST[Dari_Bulan]' and left(digital_printing.tgl_cetak, 7) <='$_POST[Ke_Bulan]' group by digital_printing.kode_bahan) digital_printing
			on
				barang.kode_barang = digital_printing.kode_bahan
			left join 
				(select digital_printing.kode_bahan, digital_printing.tgl_cetak, sum(digital_printing.qty_cetak+(digital_printing.qty_etc*2)+(digital_printing.jam*2)) as barang_keluar_lama from digital_printing where left(digital_printing.tgl_cetak, 7)<'$_POST[Dari_Bulan]'group by digital_printing.kode_bahan) old_digital_printing
			on
				barang.kode_barang = old_digital_printing.kode_bahan
			where
				$Cari_jenis
			order BY
				barang.nama_barang
			asc
		";
		
		$q = mysql_query("$query");
		
		$n=0;
		while ($d=mysql_fetch_array($q)) {
			
			$n++;

			if($d[jenis_barang]=="SPRT") { $jenis_barang="Sparepart";}
			elseif($d[jenis_barang]=="LF") { $jenis_barang="Bahan Large Format";}
			elseif($d[jenis_barang]=="KRTS") { $jenis_barang="Kertas";}
			elseif($d[jenis_barang]=="TNT") { $jenis_barang="Tinta & Tonner";}
			elseif($d[jenis_barang]=="ETC") { $jenis_barang="Lain-Lain";}
			else { $jenis_barang="ERROR";}

			$barang_keluar=$d[barang_keluar]/2;
			$barang_keluar_lama=$d[barang_keluar_lama]/2;
			$sisa_stock_lama=$d[barang_masuk_lama]-$barang_keluar_lama;
			$sisa_stock=$d[barang_masuk]-$barang_keluar+$sisa_stock_lama;

			if($sisa_stock<$d[min_stock]) { $alert="style='background-color:#e74c3c; color:white;'"; }
			else { $alert=""; }

			echo "
				<tr>
					<td $alert>$n</td>
					<td $alert>$d[nama_barang]</td>
					<td $alert>$d[kode_barang]</td>
					<td $alert>$jenis_barang</td>
					<td $alert>". number_format($d[min_stock]) ." $d[satuan]</td>
					<td $alert>". number_format($sisa_stock_lama) ." $d[satuan]</td>
					<td $alert>". number_format($d[barang_masuk]) ." $d[satuan]</td>
					<td $alert>". number_format($barang_keluar) ." $d[satuan]</td>
					<td $alert><b>". number_format($sisa_stock) ." $d[satuan]</b></td>
				</tr>
			";
		}
    ?>
</table>