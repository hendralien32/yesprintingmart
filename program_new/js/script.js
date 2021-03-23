const bahanAlertIcn = document.querySelectorAll('.icon')[1];

// munculkan image loading saat lagi proses load data
function loading() {
  document.getElementById('ajax_load').innerHTML = `<div id='loading_status' style='text-align:center'><img src='../images/loading_12.gif' style='width:15%;'></div>`;
}

// munculkan widget tanggal
function widget() {
  let now = moment();
  let tanggal = now.lang('id').tz('Asia/Jakarta').format('dddd, Do MMM YYYY');
  let waktu = now.lang('id').tz('Asia/Jakarta').format('[Jam : ]LTS');
  const header_time = document.querySelector('.header-time');

  header_time.innerHTML = "<b class='tanggal'>" + tanggal + "</b><br><b class='waktu'>" + waktu + '</b>';
}

setInterval(function () {
  window.innerWidth > 767.98 ? widget() : (document.querySelector('.header-time').innerHTML = '');
}, 500);

//menampilkan list bahan kurang saat icon notif di navbar di click
bahanAlertIcn.addEventListener('click', async function () {
  const div_class = document.querySelector('.notif_display');
  if (div_class.classList.contains('display-none')) {
    div_class.classList.replace('display-none', 'display-show');
    const bahan = await getListBahan();
    updateList(bahan);
  } else {
    div_class.classList.replace('display-show', 'display-none');
  }
});

function getListBahan() {
  return fetch('../program_new/json/json_data.php', {
    method: 'POST',
    body: `jenisData=listKekuranganKertas`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.json())
    .then((response) => response);
}

function updateList(bahan) {
  let listBahan = '';
  bahan.forEach((b) => (listBahan += showListBahan(b)));
  const tableList = document.querySelector('.table_notif');
  tableList.innerHTML = listBahan;
}

function showListBahan(b) {
  return `<tr>
            <td>${b.namaBarang}</td>
            <td style='text-align:right; padding-right:0.9em'><b>${b.sisaStock}</b> Lbr</td>
          </tr>`;
}

//menampilan file ajax saat di load page
function getContent(file, variable) {
  return fetch(`../program_new/ajax/${file}_ajax.php`, {
    method: 'POST',
    body: `${variable}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}

function updateContent(data) {
  const content = document.querySelector('.ajax_load');
  content.innerHTML = data;
}

//tampilkan form & menghilangkan form
function lightBoxClose() {
  const bg_blackOut = document.querySelector('.blackout');
  const cancelBtn = document.querySelector('.closeBtn');

  cancelBtn.addEventListener('click', closeForm);
  bg_blackOut.addEventListener('click', closeForm);
}

function closeForm() {
  const lightboxInput = document.querySelector('.lightbox-input');
  const ctLightBox = document.querySelector('.content-lightbox');
  const bg_blackOut = document.querySelector('.blackout');
  lightboxInput.classList.toggle('display-show');
  document.body.style.overflow = 'auto';
  ctLightBox.innerHTML = '';
  bg_blackOut.remove();
}

const btnAdd = document.querySelectorAll('.add_form');
btnAdd.forEach((btn) => {
  btn.addEventListener('click', async function () {
    const lightboxInput = document.querySelector('.lightbox-input');
    const ctLightBox = document.querySelector('.content-lightbox');

    const divBlackOut = document.createElement('div');
    divBlackOut.className = 'blackout';
    lightboxInput.appendChild(divBlackOut);

    lightboxInput.classList.toggle('display-show');
    document.body.style.overflow = 'hidden';

    let reJudulForm = judulForm(this);
    ctLightBox.innerHTML = `<div class='topForm'><span id='titleForm'><i class='fas fa-file-plus'></i> Add ${reJudulForm} </span><span class='closeBtn'><i class='fas fa-window-close'></i></span></div><div class='lightBoxContent'></div>`;

    lightBoxClose();
    const ajaxFormLoad = await loadAjaxForm(this);
    updateForm(ajaxFormLoad);
  });
});

function loadAjaxForm(file) {
  //nama File di ambil dari data-form pada button
  let namaForm = file.dataset.form;
  return fetch(`../program_new/form/${namaForm}_form.php`, {
    method: 'POST',
    body: ``,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}

// Confirm BOX START
async function confirmForm(jenis, tipe, id) {
  const lightboxConfirmation = document.querySelector('.lightbox-confirmation');
  const contentLightbox = lightboxConfirmation.querySelector('.content-lightbox');

  const divBlackOut = document.createElement('div');
  divBlackOut.className = 'blackout';
  lightboxConfirmation.appendChild(divBlackOut);
  lightboxConfirmation.classList.toggle('display-show');
  document.body.style.overflow = 'hidden';

  const ajaxConfirmForm = await loadConfirmForm(jenis, tipe);
  contentLightbox.innerHTML = ajaxConfirmForm;

  ActionConfirmBox(lightboxConfirmation, id, tipe);
}

function loadConfirmForm(file, tipe) {
  //nama File di ambil dari data-form pada button
  return fetch(`../program_new/form/confirm/${file}_cb.php`, {
    method: 'POST',
    body: `tipe=${tipe}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}

function ActionConfirmBox(lightboxConfirmation, id, tipe) {
  const btnNo = lightboxConfirmation.querySelector('.no-btn');
  const btnYes = lightboxConfirmation.querySelector('.yes-btn');
  const bg_blackOut = lightboxConfirmation.querySelector('.blackout');

  btnNo.addEventListener('click', function () {
    closeActionCb(lightboxConfirmation);
  });

  bg_blackOut.addEventListener('click', function () {
    closeActionCb(lightboxConfirmation);
  });

  btnYes.addEventListener('click', function () {
    yesActionCb(lightboxConfirmation, id, tipe);
  });
}

function closeActionCb(lightboxConfirmation) {
  const contentLightbox = lightboxConfirmation.querySelector('.content-lightbox');
  const bg_blackOut = lightboxConfirmation.querySelector('.blackout');

  lightboxConfirmation.classList.toggle('display-show');
  document.body.style.overflow = 'auto';
  contentLightbox.innerHTML = '';
  bg_blackOut.remove();
}

function yesActionCb(lightboxConfirmation, id, tipe) {
  const errHTML = lightboxConfirmation.querySelector('.resultError');
  fetch(`../program_new/progress/progress.php`, {
    method: 'POST',
    body: `typeProgress=${tipe}&idKaryawan=${id}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => {
      if (response === 'true') {
        closeActionCb(lightboxConfirmation);
        loadPage();
      } else {
        errHTML.innerHTML = `<i class="far fa-exclamation-circle"></i> Proses penghapusan data ERROR`;
        errHTML.style.borderBottom = '1px solid #dfdfdf';
        errHTML.style.paddingBottom = '10px';
        return false;
      }
    })
    .catch((error) => {
      console.error('Error:', error);
    });
}
// CONFIRM BOX END
