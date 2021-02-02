$(document).ready(function () {
    onload();
  });
  
  function onload() {
    $("#loader").show();
  
    $.ajax({
      type: "POST",
      data: {
      },
      url: "Ajax/support_ajax.php",
      cache: false,
      success: function (data) {
        $("#loader").hide();
        $("#faq_wrapper").html(data);
        return false;
      },
      error: function (xhr, status, error) {
        alert(xhr.responseText);
      },
    });
  }