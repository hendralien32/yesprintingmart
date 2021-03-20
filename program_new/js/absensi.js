window.addEventListener('load', loadPage);

async function loadPage() {
  const blnDari = document.getElementById('search_drBln').value;
  const blnKe = document.getElementById('search_keBln').value;
  const username = document.getElementById('search_user').value;
  const ajaxPage = document.querySelector('.left_title').innerHTML.toLowerCase().replace(' ', '_');

  let variable;

  if (ajaxPage == 'absensi_harian') {
    const typeList = document.getElementById('search_absensi');
    variable = `drTgl=${blnDari}&keTgl=${blnKe}&username=${username}&jenisList=${typeList.value}`;

    const data = await getContent(ajaxPage, variable);
    updateContent(data);

    typeList.addEventListener('change', loadPage);
  } else {
    variable = `blnDari=${blnDari}&username=${username}`;

    const data = await getContent(ajaxPage, variable);
    updateContent(data);
  }
  const jumlahKaryawan = document.getElementById('jumlahKaryawan').innerHTML;
  document.getElementById('right_title').innerHTML = jumlahKaryawan;
}

// function menampilkan search list saat tombol search di tekan
const btnSearch = document.querySelector('.button-search');
const inputDate = document.querySelector('#search_drBln');
const inputSearch = document.querySelector('#search_user');

btnSearch.onclick = (e) => {
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
  };

  return obj[namaForm];
}

function updateForm(ajaxFormLoad) {
  const content = document.querySelector('.lightBoxContent');
  content.innerHTML = ajaxFormLoad;

  if (content.querySelector('.absensi').classList.contains('individu') == true) {
    addListAbsensi(content);
    autocomplete(content);
  }
}

function autocomplete(data) {
  data.addEventListener('input', async (e) => {
    if (e.target.getAttribute('id') == 'namaKaryawan') {
      currentFocus = -1;

      const nomor = e.target.dataset.nomor;
      const inputKaryawan = data.querySelector(`.namaKaryawan_${nomor}`);
      const divAutoComplete = data.querySelector(`.ac_${nomor}`);

      const res = await fetch('../program_new/json/state_capitals.json');
      const karyawans = await res.json();

      let matches = karyawans.filter((karyawan) => {
        const regex = new RegExp(`^${inputKaryawan.value}`, 'gi');
        return karyawan.name.match(regex) || karyawan.abbr.match(regex);
      });

      if (inputKaryawan.value === '') {
        matches = [];
        divAutoComplete.style.display = 'hidden';
        divAutoComplete.style.border = 'none';
        divAutoComplete.innerHTML = '';
      }

      if (matches.length > 0) {
        divAutoComplete.style.display = 'block';
        const html = matches.map((match) => `<div class='item'>${match.name} <input type='hidden' id='test' value='${match.name}|${match.abbr}|${nomor}'></div>`).join('');
        divAutoComplete.innerHTML = html;
      } else {
        divAutoComplete.style.display = 'hidden';
        divAutoComplete.style.border = 'none';
        divAutoComplete.innerHTML = '';
      }
    }
  });

  data.addEventListener('click', (e) => {
    const a = e.target;
    const parent = a.parentNode;
    const grandParent = parent.parentNode;

    if (a.classList.contains('item')) {
      const data = a.querySelector('#test').value.split('|');
      const valueInput = data[0];
      const uidInput = data[1];
      const nilai_row = data[2];

      const inputNama = grandParent.querySelector(`.namaKaryawan_${nilai_row}`);
      const inputUID = grandParent.querySelector(`.idKaryawan_${nilai_row}`);
      const divAutoComplete = grandParent.querySelector(`.ac_${nilai_row}`);
      const checked = grandParent.querySelector(`.checklist_${nilai_row}`);
      divAutoComplete.innerHTML = '';
      divAutoComplete.style.display = 'hidden';
      inputNama.value = valueInput;
      inputUID.value = uidInput;
      checked.innerHTML = `<i class="far fa-check"></i>;`;
    }
  });

  data.addEventListener('keydown', function (e) {
    const a = e.target;
    const parent = a.parentNode;
    const y = parent.querySelectorAll('.item');
    console.log(currentFocus);
    if (e.keyCode == 40 && y.length - 2 >= currentFocus) {
      console.log(y[1 + currentFocus++].querySelector('#test').parentNode.classList.add('active'));
    } else if (e.keyCode == 38 && currentFocus >= 1) {
      console.log(y[currentFocus-- - 1].querySelector('#test').parentNode.classList.add('active'));
    } else if (e.keyCode == 13) {
      const doc = y[currentFocus].querySelector('#test').parentNode.parentNode.parentNode;
      const inputUID = doc.getElementById(idKaryawan_1);
    } else {
      return false;
    }
  });
}

function addListAbsensi(data) {
  const btnAdd = data.querySelector('.add');
  let i = 1;

  btnAdd.addEventListener('click', (e) => {
    i++;
    data.querySelector('#dynamic-field').insertAdjacentHTML(
      'beforeend',
      `<tr class='row_${i}'>
          <td>
            <input type='text' data-nomor='${i}' class='namaKaryawan_${i}' id='namaKaryawan'>
            <div class='autocomplete ac_${i}'>
              
            </div>
            <input type='text' data-nomor='${i}' class='idKaryawan_${i}' id='idKaryawan' style='width:15%'>
            <span class='checklist_${i}'></span>
          </td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamMulai'></td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamSelesai'></td>
          <td class='center'><input type='checkbox' data-nomor='${i}' id='permisi' value='permisi'></td>
          <td class='center'><input type='checkbox' data-nomor='${i}' id='lembur' value='lembur'></td>
          <td class='center remove' id='${i}'><i class='far fa-minus' id='${i}' onclick='removeListAbsensi(${i})'></i></td>
      </tr>`
    );
  });

  data.addEventListener('click', (e) => {
    const queryDataUID = document.querySelectorAll(`[data-nomor='${e.target.dataset.nomor}']`);
    const permisiCb = queryDataUID[4];
    const lemburCb = queryDataUID[5];

    if (e.target.value == 'lembur' && e.target.checked == true) {
      permisiCb.disabled = true;
    } else if (e.target.value == 'lembur' && e.target.checked == false) {
      permisiCb.disabled = false;
    } else if (e.target.value == 'permisi' && e.target.checked == true) {
      lemburCb.disabled = true;
    } else if (e.target.value == 'permisi' && e.target.checked == false) {
      lemburCb.disabled = false;
    }
  });
}

function removeListAbsensi(i) {
  document.querySelector(`.row_${i}`).remove();
}

// selesai Load oleh Script.js

document.addEventListener('change', async (e) => {
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
