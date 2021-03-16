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
  return fetch('../program_new/json/json_data.php')
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
