$(document).ready(function() {
	$('form').on('submit', function(event){
		event.preventDefault();
		var formData = new FormData($('form')[0]);
 
		$('.msg').hide();
		$('.progress').show();
		
		$.ajax({
			xhr : function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(e){
					if(e.lengthComputable){
						console.log('Bytes Loaded : ' + e.loaded);
						console.log('Total Size : ' + e.total);
						console.log('Persen : ' + (e.loaded / e.total));
						
						var percent = Math.round((e.loaded / e.total) * 100);
						
						$('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
					}
				});
				return xhr;
			},
			
			type : 'POST',
			url : 'upload.php',
			data : formData,
			processData : false,
			contentType : false,
			success : function(response){
				$('form')[0].reset();
				$('.progress').hide();
				$('.msg').show();
				if(response == ""){
					alert('File gagal di upload');
				}else{
					var msg = 'File berhasil di upload. ID file = ' + response;
					$('.msg').html(msg);
				}
			}
		});
	});
});


$("#add_product").click(function(e){
    e.preventDefault();
    var fdata = new FormData()
    
   fdata.append("product_name",$("product_name").val());
  
    if($("#file")[0].files.length>0)
       fdata.append("file",$("#file")[0].files[0])
    //d = $("#add_new_product").serialize();
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data:fdata,
        contentType: false,
        processData: false, 
        success: function(response)
        {
            //
            alert(response);

        }
    })
});




function submit(id) {

    if($('#kode_barng').val()=="") {
        alert("Kode Barang harus di pilih")
        return false;
    } else if($('#client').val()=="") {
        alert("Nama Client tidak Boleh kosong")
        return false;
    } else if($('#validasi_client').val()<1) {
        alert("Nama Client Belum terdaftar & Minta Customer Service Untuk Mendaftarkan Nama Client Tersebut")
        return false;
    } else if($('#validasi_bahan').val()<1) {
        alert("Nama Bahan tidak ada dalam Daftar Stock Barang")
        return false;
    }

    var ID_User         = $('#id_user').val();
    var Kode_Brg        = $('#kode_barng').val().split('.');
    var Nama_Client     = $('#client').val();
    var ID_Client       = $('#id_client').val();
    var Deskripsi       = $('#deskripsi').val();
    var Ukuran          = $('#ukuran').val();
    var Panjang         = $('#panjang').val();
    var Lebar           = $('#lebar').val();
    var Sisi;           if($('#satu_sisi').prop("checked") == true) { Sisi = "1"; } else { Sisi = "2"; }
    var ID_Bahan        = $('#id_bahan').val();
    var Nama_Bahan      = $('#bahan').val();
    var Notes           = $('#notes').val();
    var Laminating      = $('#laminating').val().split('.');
    var alat_tambahan   = $('#alat_tambahan').val().split('.');
    var Ptg_Pts;        if($('#Ptg_Pts').prop("checked") == true) { Ptg_Pts = "Y"; } else { Ptg_Pts = "N"; }
    var Ptg_Gantung;    if($('#Ptg_Gantung').prop("checked") == true) { Ptg_Gantung = "Y"; } else { Ptg_Gantung = "N"; }
    var Pon_Garis;      if($('#Pon_Garis').prop("checked") == true) { Pon_Garis = "Y"; } else { Pon_Garis = "N"; }
    var Perporasi;      if($('#Perporasi').prop("checked") == true) { Perporasi = "Y"; } else { Perporasi = "N"; }
    var CuttingSticker; if($('#CuttingSticker').prop("checked") == true) { CuttingSticker = "Y"; } else { CuttingSticker = "N"; }
    var Hekter_Tengah;  if($('#Hekter_Tengah').prop("checked") == true) { Hekter_Tengah = "Y"; } else { Hekter_Tengah = "N"; }
    var Blok;           if($('#Blok').prop("checked") == true) { Blok = "Y"; } else { Blok = "N"; }
    var Spiral;         if($('#Spiral').prop("checked") == true) { Spiral = "Y"; } else { Spiral = "N"; }
    var Qty             = $('#qty').val();
    var Satuan          = $('#satuan').val();
    var Proffing;       if($('#proffing').prop("checked") == true) { Proffing = "Y"; } else { Proffing = "N"; }
    var Ditunggu;       if($('#ditunggu').prop("checked") == true) { Ditunggu = "Y"; } else { Ditunggu = "N"; }
    var Design;         if($('#Design').prop("checked") == true) { Design = "Y"; } else { Design = "N"; }

    if(Laminating[1]== null) {
        deskripsi_Laminating = "";
    } else {
        deskripsi_Laminating = Laminating[1];
    }

    if(alat_tambahan[1]== null) {
        deskripsi_alat_tambahan = "";
    } else {
        deskripsi_alat_tambahan = alat_tambahan[1];
    }

    var fdata = new FormData()
    
    fdata.append("ID_User",ID_User);


    $.ajax({
        // xhr : function() {
        //     var xhr = new window.XMLHttpRequest();
        //     xhr.upload.addEventListener('progress', function(e){
        //         if(e.lengthComputable){
        //             console.log('Bytes Loaded : ' + e.loaded);
        //             console.log('Total Size : ' + e.total);
        //             console.log('Persen : ' + (e.loaded / e.total));
                    
        //             var percent = Math.round((e.loaded / e.total) * 100);
                    
        //             $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
        //         }
        //     });
        //     return xhr;
        // },

        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        cache: false,
		processData : false,
		contentType : false,
        data: {
            ID_User             : ID_User, 
            Kode_Brg            : Kode_Brg[0], 
            Desc_Kode_Brg       : Kode_Brg[1], 
            ID_Client           : ID_Client,
            Nama_Client         : Nama_Client,
            Deskripsi           : Deskripsi,
            Ukuran              : Ukuran,
            Panjang             : Panjang,
            Lebar               : Lebar,
            Sisi                : Sisi,
            ID_Bahan            : ID_Bahan,
            Nama_Bahan          : Nama_Bahan,
            Notes               : Notes,
            Laminating          : Laminating[0],
            Desc_Laminating     : deskripsi_Laminating,
            alat_tambahan       : alat_tambahan[0],
            Desc_alat_tambahan  : deskripsi_alat_tambahan,
            Ptg_Pts             : Ptg_Pts,
            Ptg_Gantung         : Ptg_Gantung,
            Pon_Garis           : Pon_Garis,
            Perporasi           : Perporasi,
            CuttingSticker      : CuttingSticker,
            Hekter_Tengah       : Hekter_Tengah,
            Blok                : Blok,
            Spiral              : Spiral,
            Qty                 : Qty,
            Satuan              : Satuan,
            Proffing            : Proffing,
            Ditunggu            : Ditunggu,
            Design              : Design,
            jenis_submit        : id
        },
        beforeSend: function(){
            $('#submitBtn').attr("disabled","disabled");
        },
        success: function(data){
            alert("Data Berhasil Di input ke database !")
            $('.progress').hide();
            $("#Result").html(data);
            // hideBox();
            // onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}

function cancel(id) {
    if($('#alasan_cancel').val()=="") {
        alert("Alasan Cancel tidak boleh kosong")
        return false;
    }

    var ID_Order         = $('#id_order').val();
    var Alasan_Cancel    = $('#alasan_cancel').val();

    $.ajax({
        type: "POST",
        url: "progress/setter_penjualan_prog.php",
        data: {
            ID_Order        : ID_Order, 
            Alasan_Cancel   : Alasan_Cancel,
            jenis_submit    : id
        },
        beforeSend: function(){
            $('#submitBtn').attr("disabled","disabled");
        },
        success: function(data){
            alert("Data berhasil di Cancel !")
            //$("#bagDetail").html(data);
            hideBox();
            onload();
            return false;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $("#bagDetail").html(XMLHttpRequest);
        }
    }); 
}