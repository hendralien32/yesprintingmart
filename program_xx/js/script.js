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
    let variable;
    variable = 'showPage=kekuranganStockDP';

    div_class.classList.replace('display-none', 'display-show');

    const xhr = ajaxReq();
    let url = 'progress/notif_prog.php';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.send(variable);

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
  const cLightbox = document.getElementById('content-lightbox');

  bg_blackOut.addEventListener('click', function (e) {
    bg_blackOut.classList.replace('display-show', 'display-none');
    alert_box.innerHTML = '';
    cLightbox.innerHTML = '';
    alert_box.style.display = 'none';
    cLightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
  });
}

//menghilangkan alert box
function hideLightBox() {
  const bg_blackOut = document.getElementById('blackout');
  const alert_box = document.getElementById('alert_box');
  const cLightbox = document.getElementById('content-lightbox');

  bg_blackOut.addEventListener('click', function (e) {
    bg_blackOut.classList.replace('display-show', 'display-none');
    alert_box.innerHTML = '';
    cLightbox.innerHTML = '';
    alert_box.style.display = 'none';
    cLightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
  });
}

function tClose() {
  const cancelBtn = document.getElementById('closeBtn');
  const bg_blackOut = document.getElementById('blackout');
  const cLightbox = document.getElementById('content-lightbox');
  const alert_box = document.getElementById('alert_box');

  cancelBtn.addEventListener('click', function (e) {
    bg_blackOut.classList.replace('display-show', 'display-none');
    alert_box.innerHTML = '';
    cLightbox.innerHTML = '';
    alert_box.style.display = 'none';
    cLightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
  });
}

function formLoad(tipe, id) {
  if (tipe == 'SalesOrder') {
    var judul = 'Form Add Sales Order';
    var icon = 'fas fa-file-plus';
  } else {
    var judul = '404 Not Found';
    var icon = 'fas fa-file-plus';
  }

  const bg_blackOut = document.getElementById('blackout');
  const ctLightBox = document.getElementById('content-lightbox');

  bg_blackOut.classList.replace('display-none', 'display-show');
  document.body.style.overflow = 'hidden';
  ctLightBox.style.display = 'block';

  ctLightBox.innerHTML = `<div class='topForm'><span id='titleForm'><i class='${icon}'></i> ${judul} </span><span id='closeBtn' class='pointer'><i class='fas fa-window-close'></i></span></div><div id='lightBoxContent'></div>`;

  tClose();

  let variable;
  variable = 'oid=' + id;

  const xhr = ajaxReq();
  let url = 'form/' + tipe + '_f.php';
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
  xhr.send(variable);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      try {
        document.getElementById('lightBoxContent').innerHTML = xhr.responseText;
      } catch (error) {
        throw Error;
      }
    }
  };
}
