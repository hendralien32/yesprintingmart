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
