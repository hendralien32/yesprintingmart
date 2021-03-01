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

// munculkan widget tanggal
function widget() {
  var now = moment();
  var tanggal = now.lang('id').tz('Asia/Jakarta').format('dddd, Do MMM YYYY');
  var waktu = now.lang('id').tz('Asia/Jakarta').format('[Jam : ]LTS');

  document.getElementById('text').innerHTML = "<b class='tanggal'>" + tanggal + "</b><br><b class='waktu'>" + waktu + '</b>';
}

setInterval(widget, 50);

// munculkan image loading saat lagi proses load data
function loading() {
  document.getElementById('ajax_load').innerHTML = "<div id='loading_status' style='text-align:center'><img src='../images/loading_12.gif' style='width:15%;'></div>";
}

// munculkan list kekurangan stock digital printing di navbar
const icon_navbar = document.querySelectorAll('.icon');

icon_navbar[1].addEventListener('click', function (e) {
  const div_class = document.getElementById('notif_display');

  if (div_class.classList.contains('display-none')) {
    div_class.classList.replace('display-none', 'display-show');

    const xhr = ajaxReq();
    let url = 'progress/notif_prog.php';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.send();

    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        try {
          div_class.innerHTML = xhr.responseText;
        } catch (error) {
          throw Error;
        }
      }
    };
  } else {
    div_class.classList.replace('display-show', 'display-none');
  }

  e.preventDefault();
});

// menghilangkan background hitam
function alert_box() {
  const bg_blackOut = document.getElementById('blackout');
  const alert_box = document.getElementById('alert_box');

  bg_blackOut.addEventListener('click', function (e) {
    bg_blackOut.classList.replace('display-show', 'display-none');
    alert_box.innerHTML = '';
    alert_box.style.display = 'none';
  });
}

//menghilangkan alert box
function hideAlertBox() {
  const bg_blackOut = document.getElementById('blackout');
  const alert_box = document.getElementById('alert_box');

  bg_blackOut.classList.replace('display-show', 'display-none');
  alert_box.innerHTML = '';
  alert_box.style.display = 'none';
}

// function tClose() {
//   const cancelBtn = document.getElementById('closeBtn');
//   const bg_blackOut = document.getElementById('blackout');
//   const cLightbox = document.getElementById('content-lightbox');

//   cancelBtn.addEventListener('click', function (e) {
//     bg_blackOut.classList.replace('display-show', 'display-none');
//     cLightbox.innerHTML = '';
//     cLightbox.style.display = 'none';
//   });
// }
