function ajaxReq() {
  if (window.XMLHttpRequest) {
    return new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    return new ActiveXObject('Microsoft.XMLHTTP');
  } else {
    alert('Browser does not support XMLHTTP.');
    return false;
  }
}

function loading() {
  document.getElementById('ajax_load').innerHTML = "<div id='loading_status' style='text-align:center'><img src='../images/loading_12.gif' style='width:15%;'></div>";
}
