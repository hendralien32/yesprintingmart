const bahanAlertIcn = document.querySelectorAll('.icon')[1];

// munculkan image loading saat lagi proses load data
function loading() {
  const ajaxPage = document.querySelector('.left_title').innerHTML.toLowerCase().replace(' ', '_');
  const loadingImage = `
    <div id='loading_status' style='text-align:center'>
      <img src='../images/loading_12.gif' style='width:15%;'>
    </div>
  `;
  switch (ajaxPage) {
    case 'absensi_list':
      document.querySelector('.date-grid').innerHTML = loadingImage;
      break;
    default:
      document.querySelector('.ajax_load').innerHTML = loadingImage;
  }
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

async function showForm(file, tipe, id, lightboxSize) {
  const lightbox = document.querySelector(`.lightbox`);
  const divBlackOut = document.createElement('div');
  divBlackOut.className = 'blackout';
  document.body.style.overflow = 'hidden';

  const divLightboxSize = document.createElement('div');
  divLightboxSize.className = `${lightboxSize}`;
  lightbox.appendChild(divLightboxSize);
  lightbox.appendChild(divBlackOut);
  lightbox.classList.toggle('display-show');

  const sizeLightbox = lightbox.querySelector(`.${lightboxSize}`);
  const ajaxLightbox = await loadLightbox(file, tipe, id);
  sizeLightbox.innerHTML = ajaxLightbox;

  actionLightbox(lightbox, id, tipe);
  switch (tipe) {
    case 'Add_User':
      break;
    case 'Insert_Absensi':
    case 'Form_Absensi_Individu':
    case 'Form_Update_Absensi_Individu':
    case 'ConfirmBox_Hapus':
      actionChecked(lightbox, tipe);
      break;
    default:
      console.log('ERROR 404');
  }
}

function loadLightbox(file, tipe, id) {
  return fetch(`../program_new/form/${file}_form.php`, {
    method: 'POST',
    body: `tipe=${tipe}&id=${id}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}

function actionLightbox(lightbox, id, tipe) {
  const btnNo = lightbox.querySelector('.no-btn');
  const btnYes = lightbox.querySelector('.yes-btn');
  const bg_blackOut = lightbox.querySelector('.blackout');

  btnNo.addEventListener('click', function () {
    closelightbox(lightbox);
  });

  bg_blackOut.addEventListener('click', function () {
    closelightbox(lightbox);
  });

  btnYes.addEventListener('click', async function () {
    const data = await variable(lightbox, id, tipe);
    yesLightbox(lightbox, data);
  });
}

function closelightbox(lightbox) {
  const bg_blackOut = lightbox.querySelector('.blackout');

  lightbox.classList.toggle('display-show');
  document.body.style.overflow = 'auto';
  lightbox.innerHTML = '';
  bg_blackOut.remove();
}

function yesLightbox(lightbox, variable) {
  const errHTML = lightbox.querySelector('.resultError');
  fetch(`../program_new/progress/progress.php`, {
    method: 'POST',
    body: `${variable}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      Accept: 'application/json',
    },
  })
    .then((response) => response.text())
    .then((response) => {
      if (response === 'true') {
        closelightbox(lightbox);
        loadPage();
      } else {
        errHTML.innerHTML = `<i class="far fa-exclamation-circle"></i> ${response}`;
        errHTML.style.borderBottom = '1px solid #dfdfdf';
        errHTML.style.paddingBottom = '10px';
        errHTML.style.paddingTop = '10px';
        return false;
      }
    })
    .catch((error) => {
      console.error('Error:', error);
    });
}
// CONFIRM BOX END
