window.addEventListener('load', loadPage);

async function loadPage() {
  const blnDari = document.getElementById('search_drBln').value;
  const username = document.getElementById('search_user').value;
  let variable;
  variable = `blnDari=${blnDari}&username=${username}`;

  const data = await getContent('absensi', variable);
  updateContent(data);

  const jumlahKaryawan = document.getElementById('jumlahKaryawan').innerHTML;
  document.getElementById('right_title').innerHTML = jumlahKaryawan;
}

// function menampilkan search list saat tombol search di tekan
const btnSearch = document.querySelector('.button-search');
const inputDate = document.querySelector('#search_drBln');
const inputSearch = document.querySelector('#search_user');

btnSearch.onclick = () => {
  const divSearch = document.querySelector('.plugin-search');
  divSearch.classList.toggle('display-show');
  if (divSearch.classList.contains('display-show')) {
    btnSearch.innerHTML = "<i class='fas fa-search-minus'></i>";
  } else {
    btnSearch.innerHTML = "<i class='fas fa-search-plus'></i>";
  }
};

inputDate.addEventListener('change', loadPage);
inputSearch.addEventListener('input', loadPage);

// Progress submit Absensi Harian Ke database
function submitAbsensiHarian() {
  const errHTML = document.querySelector('.resultQuery');
  const tglAbsensi = document.querySelector('#tglAbsensi').value;
  const karyawanUid = document.querySelectorAll('#karyawanUid');
  const scanMasuk = document.querySelectorAll('#scanMasuk');
  const scanKeluar = document.querySelectorAll('#scanKeluar');
  const absensiCB = document.querySelectorAll('#Absen');
  const cutiCB = document.querySelectorAll('#Cuti');

  if (karyawanUid.length > 0) {
    // [...TEXT] => Spread Operator (...) untuk bisa mengambil value pada nodeList
    const valueKaryawanUid = [...karyawanUid].map((k) => k.value);
    const valueScanMasuk = [...scanMasuk].map((sm) => sm.value);
    const valueScanKeluar = [...scanKeluar].map((sk) => sk.value);
    const valueAbsensiCB = [...absensiCB].map((a) => (a.checked == true ? 'Y' : 'N'));
    const valueCutiCB = [...cutiCB].map((c) => (c.checked == true ? 'Y' : 'N'));

    for (let i = 0; i < karyawanUid.length; i++) {
      if (valueScanMasuk[i] == '' && valueScanKeluar[i] == '' && valueAbsensiCB[i] == 'N' && valueCutiCB[i] == 'N') {
        errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Data Input kosong<br><br></b>`;
        return false;
      }
    }

    const variable = `tglAbensi=${tglAbsensi}&scanMasuk=${valueScanMasuk}&scanKeluar=${valueScanKeluar}&absensiCB=${valueAbsensiCB}&cutiCB=${valueCutiCB}&uid=${valueKaryawanUid}&typeProgress=Insert_Absensi`;

    fetch(`../program_new/progress/progress.php`, {
      method: 'POST',
      body: `${variable}`,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
    })
      .then((Response) => Response.text())
      .then((Response) => {
        if (Response === 'true') {
          closeForm();
          loadPage();
        } else {
          errHTML.innerHTML = Response;
        }
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  } else {
    errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Tidak ada data yang mau di upload<br><br></b>`;
    return false;
  }
}

//di load oleh script.js
function judulForm(dataBtn) {
  let namaForm = dataBtn.dataset.form;

  var obj = {
    absensi: 'Absensi Harian',
    absensi_individu: 'Absensi Personal',
    absensi_HariLibur: 'Absensi Set Hari Libur',
  };

  return obj[namaForm];
}

function updateForm(ajaxFormLoad) {
  const content = document.querySelector('.lightBoxContent');
  content.innerHTML = ajaxFormLoad;
}
// selesai Load oleh Script.js

document.addEventListener('change', async function (e) {
  // untuk ganti List Absensi di Form saat Input[type=date] di ganti
  if (e.target.classList.contains('tglAbsensi')) {
    const tableAbsensi = await reLoadAjaxForm(document.querySelector('#tglAbsensi').value);
    reUpdateForm(tableAbsensi);
  }

  // validasi untuk Checkbox Absen dan Cuti, Apabila Absen & Cuti di check maka Scan Masuk dan Scan Keluar Disabled
  const dataUID = e.target.dataset.uid;
  if (typeof dataUID === 'undefined') {
    return false;
  } else {
    const queryDataUID = document.querySelectorAll(`[data-uid='${dataUID}']`);
    const scanMasuk = queryDataUID[2];
    const scankeluar = queryDataUID[3];
    const checkboxAbsen = queryDataUID[4];
    const checkboxCuti = queryDataUID[5];

    if (e.target.checked == true) {
      scanMasuk.disabled = true;
      scankeluar.disabled = true;
      scanMasuk.value = '';
      scankeluar.value = '';
    } else if (e.target.checked == false) {
      scanMasuk.disabled = false;
      scankeluar.disabled = false;
    }

    if (e.target.value == 'absen' && e.target.checked == true) {
      checkboxCuti.disabled = true;
    } else if (e.target.value == 'absen' && e.target.checked == false) {
      checkboxCuti.disabled = false;
    } else if (e.target.value == 'cuti' && e.target.checked == true) {
      checkboxAbsen.disabled = true;
    } else if (e.target.value == 'cuti' && e.target.checked == false) {
      checkboxAbsen.disabled = false;
    }
  }
});

// reload List Table Absensi saat diganti Input[type=date] pada form
function reLoadAjaxForm(variable) {
  return fetch(`../program_new/form/absensi_sub_form.php`, {
    method: 'POST',
    body: `tanggal=${variable}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}

function reUpdateForm(ajaxFormLoad) {
  const content = document.querySelector('.absensiList');
  content.innerHTML = ajaxFormLoad;
}
