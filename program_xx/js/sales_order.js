window.addEventListener('load', function () {
  loading();
  hideLightBox();
  loadList();
});

const tSearch = document.getElementById('button-search');

tSearch.onclick = function () {
  const div_class = document.getElementById('plugin-search'),
    div_search = document.getElementById('button-search');

  if (div_class.classList.contains('display-none')) {
    div_search.innerHTML = "<i class='fas fa-search-minus'></i>";
    div_class.classList.replace('display-none', 'display-show');
  } else {
    div_search.innerHTML = "<i class='fas fa-search-plus'></i>";
    div_class.classList.replace('display-show', 'display-none');
  }
};

//fungsi tombol cancel
function tCancel() {
  const cancelBtn = document.getElementById('cancel');
  const bg_blackOut = document.getElementById('blackout');
  const alert_box = document.getElementById('alert_box');

  cancelBtn.addEventListener('click', function (e) {
    bg_blackOut.classList.replace('display-show', 'display-none');
    alert_box.innerHTML = '';
    alert_box.style.display = 'none';
  });
}

function loadList() {
  const search_client = document.getElementById('search_client').value,
    search_data = document.getElementById('search_data').value,
    search_drTgl = document.getElementById('search_drTgl').value,
    search_keTgl = document.getElementById('search_keTgl').value,
    search_limit = document.getElementById('search_limit').value,
    search_Setter = document.getElementById('ID_Setter').value;

  let variable;
  variable = 'client=' + search_client + '&data=' + search_data + '&drTgl=' + search_drTgl + '&keTgl=' + search_keTgl + '&limit=' + search_limit + '&Setter=' + search_Setter;

  const xhr = ajaxReq();
  let url = 'ajax/sales_order_ajax.php';
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
  xhr.send(variable);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      try {
        document.getElementById('ajax_load').innerHTML = xhr.responseText;

        const jumlah_order = document.querySelector('span#jumlah_order').innerHTML;
        document.getElementById('right_title').innerHTML = jumlah_order;
      } catch (error) {
        throw Error;
      }
    }
  };
}

function SearchData() {
  const search_client = document.getElementById('search_client').value,
    search_data = document.getElementById('search_data').value;

  if (search_client != '' || search_data != '') {
    document.getElementById('search_drTgl').value = '';
    document.getElementById('search_keTgl').value = '';
  }

  loading();
  loadList();
}

function SearchDate() {
  loading();
  loadList();
}

function SearchSetter() {
  const Setter_ID = document.getElementById('search_Setter').value;
  document.getElementById('ID_Setter').value = Setter_ID;
  loading();
  loadList();
}

function finished(id, status) {
  const bg_blackOut = document.getElementById('blackout');
  const alert_box = document.getElementById('alert_box');
  const sSelesai = status == 'N' ? 'selesai' : '';

  bg_blackOut.classList.replace('display-none', 'display-show');
  document.body.style.overflow = 'hidden';
  alert_box.style.display = 'block';

  alert_box.innerHTML = `<div class='icon'><i class='fal fa-exclamation-circle'></i></div><div class='keterangan'>Ubah Status pada ID Order order ${id} ?</div><div class='button'><button id='ok'>OK</button> <button id='closeBtn'>Cancel</button></div>`;

  tClose();

  const okBtn = document.getElementById('ok');
  okBtn.addEventListener('click', function (e) {
    let variable;
    variable = 'oid=' + id + '&status=' + status + '&sSelesai=' + sSelesai + '&typeSubmit=statusFinishedOID';

    const xhr = ajaxReq();
    let url = 'progress/sql_Progress.php';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.send(variable);

    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        try {
          hideLightBox();
          loading();
          loadList();
          // document.getElementById('ajax_load').innerHTML = xhr.responseText;
        } catch (error) {
          throw Error;
        }
      }
    };
    e.preventDefault();
  });
}

function sLog(id) {
  const bg_blackOut = document.getElementById('blackout');
  const ctLightBox = document.getElementById('content-lightbox');

  bg_blackOut.classList.replace('display-none', 'display-show');
  document.body.style.overflow = 'hidden';
  ctLightBox.style.display = 'block';

  ctLightBox.innerHTML = `<div class='topForm'><span id='titleForm'><i class='fas fa-file-alt'></i> Logs ID #${id}</span><span id='closeBtn' class='pointer'><i class='fas fa-window-close'></i></span></div><div id='lightBoxContent'></div>`;

  tClose();

  let variable;
  variable = 'oid=' + id + '&showPage=logList';

  const xhr = ajaxReq();
  let url = 'progress/notif_prog.php';
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

function imgPreview(id) {
  const bg_blackOut = document.getElementById('blackout');
  const ctLightBox = document.getElementById('content-lightbox');

  bg_blackOut.classList.replace('display-none', 'display-show');
  document.body.style.overflow = 'hidden';
  ctLightBox.style.display = 'block';

  ctLightBox.innerHTML = `<div class='topForm'><span id='titleForm'><i class='fas fa-file-image'></i> Image Preview ID #${id}</span><span id='closeBtn' class='pointer'><i class='fas fa-window-close'></i></span></div><div id='lightBoxContent'></div>`;

  tClose();

  let variable;
  variable = 'oid=' + id + '&showPage=imagePreview';

  const xhr = ajaxReq();
  let url = 'progress/notif_prog.php';
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
