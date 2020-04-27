function hideBox () {
    // var result = confirm("Apakah anda mau menutup form ini?");
    // if (result) {
        $('#isidetail').css({"visibility":"hidden"});
        $('#blackout').css({"visibility":"hidden"});
        $('#bagDetail').html();
    // }
}

function hidesubBox () {
    // var result = confirm("Apakah anda mau menutup form ini?");
    // if (result) {
        $('#isidetail_sub').css({"visibility":"hidden"});
        $('#blackout_sub').css({"visibility":"hidden"});
        $('#bagDetail_sub').html();
    // }
}

function showBox() {
    $('#isidetail').css({"visibility":"visible"});
    $('#blackout').css({"visibility":"visible"});
}

function showSubBox() {
    $('#isidetail_sub').css({"visibility":"visible"});
    $('#blackout_sub').css({"visibility":"visible"});
}

function LaodSubForm(id,nid) {

    if( id == "setter_penjualan" ) { 
        var judul = "FORM SETTER PENJUALAN";
    } else {
        var judul = "404 Not Found";
    }

    $.ajax({
        type: "POST",
        data: {data:id, judul_form:judul, ID_Order:nid},
        url: "Form/" + id +"_f.php",
 
        success: function(data){
            showSubBox();
            $("#bagDetail_sub").html(data);
        }
    });
}


function LaodForm(id,nid,Akses_Edit) {

    if( id == "setter_penjualan" ) { 
        var judul = "Form Setter Penjualan";
    } else if( id == "setter_penjualan_cancel" ) { 
        var judul = "Form Cancel";
    } else if( id == "log" ) { 
        var judul = "Form Log";
    } else if( id == "pelunasan_invoice" ) { 
        var judul = "Form Pelunasan Invoice"; 
    } else if( id == "pelunasan_Multi_invoice" ) { 
        var judul = "Form Multi Payment"; 
    } 

    $.ajax({
        type: "POST",
        data: {data:id, judul_form:judul, ID_Order:nid, AksesEdit:Akses_Edit},
        url: "Form/" + id +"_f.php",
 
        success: function(data){
            showBox();
            $("#bagDetail").html(data);

            if( id == "setter_penjualan" ) { 
                AksesEdit();
                ChangeKodeBrg();
                upload_design();
                if($('#client').val()!="") {
                    validasi('client');
                }
                if($('#panjang').val()!="" || $('#lebar').val()!="") {
                    calc_meter();
                }
                if($('#bahan').val()!="") {
                    validasi('bahan');
                }
            } else if( id == "setter_penjualan_cancel" ) { 
                $("#alasan_cancel").focus();
            } else if( id == "setter_penjualan_invoice" || id == "pelunasan_Multi_invoice" ) {
                outstandinglist();
            }

            
        }
    });
}