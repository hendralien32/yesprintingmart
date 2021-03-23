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

// Autocomplete Progress Start
function autocomplete(data) {
  data.addEventListener('input', async (e) => {
    if (e.target.getAttribute('id') == 'namaKaryawan') {
      currentFocus = 0;

      const nomor = e.target.dataset.nomor;
      const inputKaryawan = data.querySelector(`.namaKaryawan_${nomor}`);
      const inputUid = data.querySelector(`.idKaryawan_${nomor}`);
      const divAutoComplete = data.querySelector(`.ac_${nomor}`);
      const checked = data.querySelector(`.checklist_${nomor}`);

      const karyawans = await fetch('../program_new/json/json_data.php', {
        method: 'POST',
        body: `jenisData=karyawanAbsensi`,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      })
        .then((response) => response.json())
        .then((response) => response);

      let matches = karyawans.filter((karyawan) => {
        const regex = new RegExp(`${inputKaryawan.value}`, 'gi');
        return karyawan.nama.match(regex);
      });

      if (inputKaryawan.value === '') {
        matches = [];
        divAutoComplete.style.display = 'hidden';
        divAutoComplete.style.border = 'none';
        divAutoComplete.innerHTML = '';
        checked.innerHTML = '';
        inputUid.value = '';
      }

      if (matches.length > 0) {
        divAutoComplete.style.display = 'block';
        divAutoComplete.style.border = '1px solid #dfdfdf';
        const html = matches
          .map((match) => {
            if (match.nama != inputKaryawan.value) {
              checked.innerHTML = '';
              inputUid.value = '';
            }
            return `<div class='item'>${match.nama} <input type='hidden' id='uidKaryawan' value='${match.nama}|${match.uid}|${nomor}'></div>`;
          })
          .join('');
        divAutoComplete.innerHTML = html;
        const item = divAutoComplete.querySelectorAll('.item');
        addActive(item);
      } else {
        divAutoComplete.style.display = 'hidden';
        divAutoComplete.style.border = 'none';
        divAutoComplete.innerHTML = '';
        checked.innerHTML = '';
        inputUid.value = '';
      }
    }
  });

  data.addEventListener('click', (e) => {
    const grandParent = e.target.parentNode.parentNode;

    if (e.target.classList.contains('item')) {
      const doc2 = a.querySelector('#uidKaryawan');
      selectList(grandParent, doc2);
    }
  });

  data.parentNode.addEventListener('click', (e) => {
    // menghilangkan isi list autocomplete saat kita click diluar list Item
    if (e.target.classList.contains('item') === false) {
      const listAutocomplete = document.querySelector('.autocomplete');
      listAutocomplete.innerHTML = '';
      listAutocomplete.style.display = 'hidden';
      listAutocomplete.style.border = 'none';
    }
  });

  data.addEventListener('mouseover', function (e) {
    // untuk menghilangkan Class Active di Item saat hover
    const target = e.target.classList.contains('item');
    if (target === true) {
      const item = data.querySelectorAll('.item');
      removeActive(item);
    }
  });

  data.addEventListener('keydown', function (e) {
    const parent = e.target.parentNode;
    const item = parent.querySelectorAll('.item');
    if (e.keyCode == 40 && item.length - 2 >= currentFocus) {
      // ketika Tekan Tombol Bawah
      currentFocus = currentFocus++ + 1;
      addActive(item);
    } else if (e.keyCode == 38 && currentFocus >= 1) {
      // ketika Tekan Tombol Atas
      currentFocus = currentFocus-- - 1;
      addActive(item);
    } else if (e.keyCode == 13 || e.keyCode == 9) {
      // ketika Tekan Tombol Enter & Tab
      const doc = item[currentFocus].querySelector('#uidKaryawan').parentNode.parentNode.parentNode;
      const doc2 = item[currentFocus].querySelector('#uidKaryawan');
      selectList(doc, doc2);
    }
  });
}

function addActive(item) {
  /*start by removing the "active" class on all items:*/
  removeActive(item);
  /*add class "autocomplete-active":*/
  item[currentFocus].classList.add('active');
}

function removeActive(item) {
  /*a function to remove the "active" class from all autocomplete items:*/
  [...item].map((row) => row.classList.remove('active'));
}

function closeAllList(grandParent, nilai_row) {
  const divAutoComplete = grandParent.querySelector(`.ac_${nilai_row}`);
  divAutoComplete.innerHTML = '';
  divAutoComplete.style.display = 'hidden';
  divAutoComplete.style.border = 'none';
}

function selectList(a, b) {
  const data = b.value.split('|');
  const valueInput = data[0];
  const uidInput = data[1];
  const nilai_row = data[2];

  const inputNama = a.querySelector(`.namaKaryawan_${nilai_row}`);
  const inputUID = a.querySelector(`.idKaryawan_${nilai_row}`);
  const checked = a.querySelector(`.checklist_${nilai_row}`);
  inputNama.value = valueInput;
  inputUID.value = uidInput;
  checked.innerHTML = `<i class="far fa-check"></i>;`;

  closeAllList(a, nilai_row);
}
// Autocomplete Progress DONE //

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
            <input type='hidden' data-nomor='${i}' class='idKaryawan_${i}' id='idKaryawan' style='width:15%'>
            <span class='checklist_${i}'></span>
          </td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamMulai'></td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamSelesai'></td>
          <td class='center'><input type='checkbox' data-nomor='${i}' id='permisi' value='permisi'></td>
          <td class='center'><input type='checkbox' data-nomor='${i}' id='lembur' value='lembur'></td>
          <td class='center'><input type='checkbox' data-nomor='${i}' id='cuti' value='cuti'></td>
          <td class='center remove' id='${i}'><i class='far fa-minus' id='${i}' onclick='removeListAbsensi(${i})'></i></td>
      </tr>`
    );
  });

  data.addEventListener('click', (e) => {
    const queryDataUID = document.querySelectorAll(`[data-nomor='${e.target.dataset.nomor}']`);
    const jamMasuk = queryDataUID[2];
    const jamKeluar = queryDataUID[3];
    const permisiCb = queryDataUID[4];
    const lemburCb = queryDataUID[5];
    const cutiCb = queryDataUID[6];

    if (e.target.value == 'lembur' && e.target.checked == true) {
      permisiCb.disabled = true;
      cutiCb.disabled = true;
    } else if (e.target.value == 'lembur' && e.target.checked == false) {
      permisiCb.disabled = false;
      cutiCb.disabled = false;
    } else if (e.target.value == 'permisi' && e.target.checked == true) {
      lemburCb.disabled = true;
      cutiCb.disabled = true;
    } else if (e.target.value == 'permisi' && e.target.checked == false) {
      lemburCb.disabled = false;
      cutiCb.disabled = false;
    } else if (e.target.value == 'cuti' && e.target.checked == true) {
      lemburCb.disabled = true;
      permisiCb.disabled = true;
      jamMasuk.disabled = true;
      jamKeluar.disabled = true;
    } else if (e.target.value == 'cuti' && e.target.checked == false) {
      lemburCb.disabled = false;
      permisiCb.disabled = false;
      jamMasuk.disabled = false;
      jamKeluar.disabled = false;
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

// Progress submit Ke database
async function submitAbsensiHarian() {
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

    const data = await fetchSubmitProgress(variable);
    resultSubmit(data);
  } else {
    errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Tidak ada data yang mau di upload<br><br></b>`;
    return false;
  }
}

async function submitAbsensiIndividu() {
  const errHTML = document.querySelector('.resultQuery');

  const tglAbsensi = document.querySelector('#tglAbsensiHarian').value;
  const idKaryawan = document.querySelectorAll('#idKaryawan');
  const jamMulai = document.querySelectorAll('#jamMulai');
  const jamSelesai = document.querySelectorAll('#jamSelesai');
  const permisi = document.querySelectorAll('#permisi');
  const lembur = document.querySelectorAll('#lembur');
  const cuti = document.querySelectorAll('#cuti');

  if (idKaryawan.length > 0) {
    // [...TEXT] => Spread Operator (...) untuk bisa mengambil value pada nodeList
    const valueIdKaryawan = [...idKaryawan].map((k) => k.value);
    const valueJamMulai = [...jamMulai].map((jm) => jm.value);
    const valueJamSelesai = [...jamSelesai].map((js) => js.value);
    const valuePermisi = [...permisi].map((p) => (p.checked == true ? 'Y' : 'N'));
    const valueLembur = [...lembur].map((l) => (l.checked == true ? 'Y' : 'N'));
    const valueCuti = [...cuti].map((c) => (c.checked == true ? 'Y' : 'N'));

    // validasi sebelum POST ke progress.php
    for (let i = 0; i < idKaryawan.length; i++) {
      if (valuePermisi[i] == 'N' && valueLembur[i] == 'N' && valueCuti[i] == 'N') {
        errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Checkbox Permisi, Lembur & Cuti harus dipilih<br><br></b>`;
        return false;
      } else if ((valuePermisi[i] == 'Y' || valueLembur[i] == 'Y' || valueCuti[i] == 'Y') && valueIdKaryawan[i] == '') {
        errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Nama Karyawan Harus diisi<br><br></b>`;
        return false;
      } else if ((valuePermisi[i] == 'Y' || valueLembur[i] == 'Y') && valueIdKaryawan[i] != '' && valueJamMulai[i] == '' && valueJamSelesai[i] == '') {
        errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Jam Mulai dan Jam Selesai Harus diisi<br><br></b>`;
        return false;
      }
    }

    const variable = `tglAbensi=${tglAbsensi}&jamMulai=${valueJamMulai}&jamSelesai=${valueJamSelesai}&permisiCB=${valuePermisi}&lemburCB=${valueLembur}&cutiCB=${valueCuti}&uid=${valueIdKaryawan}&typeProgress=Insert_Absensi_Individu`;

    const data = await fetchSubmitProgress(variable);
    resultSubmit(data);
  } else {
    errHTML.innerHTML = `<b style='color:red; font-size:0.7rem; font-weight:550; line-height:15px'>ERROR : Tidak ada data yang mau di upload<br><br></b>`;
    return false;
  }
}

function fetchSubmitProgress(variable) {
  return fetch(`../program_new/progress/progress.php`, {
    method: 'POST',
    body: `${variable}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((Response) => Response.text())
    .then((Response) => Response)
    .catch((error) => {
      console.error('Error:', error);
    });
}

function resultSubmit(data) {
  const errHTML = document.querySelector('.resultQuery');
  if (data === 'true') {
    closeForm();
    loadPage();
  } else {
    errHTML.innerHTML = data;
    return false;
  }
}
// Progress submit Ke database END

function smallLightBox(jenis, tipe, id) {
  console.log(id);
}
