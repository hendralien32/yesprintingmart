$(document).ready(function() {
    onload();
});

function onload() {
    $('#loader').show();
    var nama_user          = $('#nama_user').val();
    var show_delete;       if($('#Check_box').prop("checked") == true) { show_delete = "T"; } else { show_delete = "A"; }

    $.ajax({
        type: "POST",
        data: {data:nama_user, show_delete:show_delete},
        url: "Ajax/database_user_ajax.php",
        cache: false,
        success: function(data){
            $('#loader').hide();
            $("#list_DatabaseUser").html(data);
            return false;
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}