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

// selesai Load oleh Script.js

// document.addEventListener('change', async (e) => {
//   // untuk ganti List Absensi di Form saat Input[type=date] di ganti
//   if (e.target.classList.contains('tglAbsensi')) {
//     const tableAbsensi = await reLoadAjaxForm(document.querySelector('#tglAbsensi').value);
//     reUpdateForm(tableAbsensi);
//   }

//   // validasi untuk Checkbox Absen dan Cuti, Apabila Absen & Cuti di check maka Scan Masuk dan Scan Keluar Disabled
//   const dataUID = e.target.dataset.uid;
//   if (typeof dataUID === 'undefined') {
//     return false;
//   } else {
//     const queryDataUID = document.querySelectorAll(`[data-uid='${dataUID}']`);
//     const scanMasuk = queryDataUID[2];
//     const scankeluar = queryDataUID[3];
//     const checkboxAbsen = queryDataUID[4];
//     const checkboxCuti = queryDataUID[5];

//     if (e.target.checked == true) {
//       scanMasuk.disabled = true;
//       scankeluar.disabled = true;
//       scanMasuk.value = '';
//       scankeluar.value = '';
//     } else if (e.target.checked == false) {
//       scanMasuk.disabled = false;
//       scankeluar.disabled = false;
//     }

//     if (e.target.value == 'absen' && e.target.checked == true) {
//       checkboxCuti.disabled = true;
//     } else if (e.target.value == 'absen' && e.target.checked == false) {
//       checkboxCuti.disabled = false;
//     } else if (e.target.value == 'cuti' && e.target.checked == true) {
//       checkboxAbsen.disabled = true;
//     } else if (e.target.value == 'cuti' && e.target.checked == false) {
//       checkboxAbsen.disabled = false;
//     }
//   }
// });

function variable(lightbox, id, tipe) {
  if (tipe == 'ConfirmBox_Hapus') {
    return `typeProgress=${tipe}&idAbsensi=${id}`;
  }

  if (tipe == 'Form_Update_Absensi_Individu') {
    const jam_mulai = lightbox.querySelector('#jam_mulai').value;
    const jam_selesai = lightbox.querySelector('#jam_selesai').value;
    const absen = lightbox.querySelector('#absen').checked == true ? 'Y' : 'N';
    const cuti = lightbox.querySelector('#cuti').checked == true ? 'Y' : 'N';
    const permisi = lightbox.querySelector('#permisi').checked == true ? 'Y' : 'N';
    const lembur = lightbox.querySelector('#lembur').checked == true ? 'Y' : 'N';

    return `typeProgress=${tipe}&idAbsensi=${id}&jam_mulai=${jam_mulai}&jam_selesai=${jam_selesai}&absen=${absen}&cuti=${cuti}&permisi=${permisi}&lembur=${lembur}`;
  }

  if (tipe == 'Form_Absensi_Individu') {
    const tglAbsensi = lightbox.querySelector('#tglAbsensiHarian').value;
    const idKaryawan = lightbox.querySelectorAll('#idKaryawan');
    const jamMulai = lightbox.querySelectorAll('#jamMulai');
    const jamSelesai = lightbox.querySelectorAll('#jamSelesai');
    const permisi = lightbox.querySelectorAll('#permisi');
    const lembur = lightbox.querySelectorAll('#lembur');
    const cuti = lightbox.querySelectorAll('#cuti');

    if (idKaryawan.length > 0) {
      // [...TEXT] => Spread Operator (...) untuk bisa mengambil value pada nodeList
      const valueIdKaryawan = [...idKaryawan].map((k) => k.value);
      const valueJamMulai = [...jamMulai].map((jm) => jm.value);
      const valueJamSelesai = [...jamSelesai].map((js) => js.value);
      const valuePermisi = [...permisi].map((p) => (p.checked == true ? 'Y' : 'N'));
      const valueLembur = [...lembur].map((l) => (l.checked == true ? 'Y' : 'N'));
      const valueCuti = [...cuti].map((c) => (c.checked == true ? 'Y' : 'N'));
      let error = '';

      // validasi sebelum POST ke progress.php
      for (let i = 0; i < idKaryawan.length; i++) {
        if (valuePermisi[i] == 'N' && valueLembur[i] == 'N' && valueCuti[i] == 'N') {
          error = `Checkbox Permisi, Lembur, Cuti harus dipilih`;
        } else if ((valuePermisi[i] == 'Y' || valueLembur[i] == 'Y' || valueCuti[i] == 'Y') && valueIdKaryawan[i] == '') {
          error = `Nama Karyawan Harus diisi`;
        } else if ((valuePermisi[i] == 'Y' || valueLembur[i] == 'Y') && valueIdKaryawan[i] != '' && valueJamMulai[i] == '' && valueJamSelesai[i] == '') {
          error = `Jam Mulai dan Jam Selesai Harus diisi`;
        }
      }

      return `tglAbensi=${tglAbsensi}&jamMulai=${valueJamMulai}&jamSelesai=${valueJamSelesai}&permisiCB=${valuePermisi}&lemburCB=${valueLembur}&cutiCB=${valueCuti}&uid=${valueIdKaryawan}&error=${error}&typeProgress=${tipe}`;
    }
  }

  if (tipe == 'Insert_Absensi') {
    const tglAbsensi = lightbox.querySelector('#tglAbsensi').value;
    const karyawanUid = lightbox.querySelectorAll('#karyawanUid');
    const scanMasuk = lightbox.querySelectorAll('#scanMasuk');
    const scanKeluar = lightbox.querySelectorAll('#scanKeluar');
    const absensiCB = lightbox.querySelectorAll('#Absen');
    const cutiCB = lightbox.querySelectorAll('#Cuti');

    if (karyawanUid.length > 0) {
      // [...TEXT] => Spread Operator (...) untuk bisa mengambil value pada nodeList
      const valueKaryawanUid = [...karyawanUid].map((k) => k.value);
      const valueScanMasuk = [...scanMasuk].map((sm) => sm.value);
      const valueScanKeluar = [...scanKeluar].map((sk) => sk.value);
      const valueAbsensiCB = [...absensiCB].map((a) => (a.checked == true ? 'Y' : 'N'));
      const valueCutiCB = [...cutiCB].map((c) => (c.checked == true ? 'Y' : 'N'));
      let error = '';

      for (let i = 0; i < karyawanUid.length; i++) {
        if (valueScanMasuk[i] == '' && valueScanKeluar[i] == '' && valueAbsensiCB[i] == 'N' && valueCutiCB[i] == 'N') {
          error = `Data Input kosong`;
        }
      }

      return `tglAbensi=${tglAbsensi}&scanMasuk=${valueScanMasuk}&scanKeluar=${valueScanKeluar}&absensiCB=${valueAbsensiCB}&cutiCB=${valueCutiCB}&uid=${valueKaryawanUid}&error=${error}&typeProgress=Insert_Absensi`;
    }
  }
}

function actionChecked(lightbox, tipe) {
  if (tipe == 'Form_Update_Absensi_Individu') {
    const jamMulai = lightbox.querySelector('#jam_mulai');
    const jamSelesai = lightbox.querySelector('#jam_selesai');
    const hadir = lightbox.querySelector('#hadir');
    const absen = lightbox.querySelector('#absen');
    const cuti = lightbox.querySelector('#cuti');
    const permisi = lightbox.querySelector('#permisi');
    const lembur = lightbox.querySelector('#lembur');

    const tempJamMulai = [...jamMulai.value];
    const tempJamSelesai = [...jamSelesai.value];

    lightbox.addEventListener('click', function (e) {
      if (e.target.getAttribute('id') === null) return false;
      let data = [jamMulai, jamSelesai, hadir, absen, cuti, permisi, lembur];
      const ID = e.target.getAttribute('id');
      const test = lightbox.querySelector(`#${ID}`);

      if (test.checked === false) {
        data.map((d) => {
          if (test.getAttribute('id') == 'jam_mulai' || test.getAttribute('id') == 'jam_selesai') return;
          d.disabled = false;
        });
      } else {
        data.map((d) => {
          if (d === test) {
            if (d.getAttribute('id') == 'hadir' || d.getAttribute('id') == 'permisi' || d.getAttribute('id') == 'lembur') {
              data[0].disabled = false;
              data[0].value = tempJamMulai.join('');
              data[1].disabled = false;
              data[1].value = tempJamSelesai.join('');
            }
            d.disabled = false;
          } else {
            d.disabled = true;
            d.value = '0000-00-00';
          }
        });
      }
    });

    return;
  }

  if (tipe == 'Form_Absensi_Individu') {
    addListAbsensi(lightbox);
    autocomplete(lightbox);
    return;
  }

  if (tipe == 'Insert_Absensi') {
    changeDateAbsensi(lightbox);

    lightbox.addEventListener('click', function (e) {
      if (e.target.getAttribute('id') === null) return;
      const data = ['scanMasuk', 'scanKeluar', 'Cuti', 'Absen'];
      const id = e.target.getAttribute('class').split('_');
      if (id[0] === 'Absen' || id[0] === 'Cuti') {
        if (e.target.checked === true) {
          data.map((d) => {
            lightbox.querySelector(`.scanMasuk_${id[1]}`).disabled = true;
            lightbox.querySelector(`.scanKeluar_${id[1]}`).disabled = true;
          });
        } else {
          data.map((d) => {
            lightbox.querySelector(`.${d}_${id[1]}`).disabled = false;
          });
        }
      }
      // if (b[0] === 'Cuti') {
      //   lightbox.querySelector(`#${a[1]}_${b[1]}`).disabled = true;
      //   return;
      // } else {
      //   lightbox.querySelector(`#${a[0]}_${b[1]}`).disabled = true;
      //   return;
      // }
    });
  }
}

function changeDateAbsensi(lightbox) {
  const inputDate = lightbox.querySelector('#tglAbsensi');

  inputDate.addEventListener('change', async (e) => {
    const tableAbsensi = await reLoadAjaxForm(inputDate.value);
    reUpdateForm(tableAbsensi);
  });
}

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
  const content = document.querySelector('.Table-Content');
  content.innerHTML = ajaxFormLoad;
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
            <input type='hidden' data-nomor='${i}' class='idKaryawan_${i}' id='idKaryawan' style='width:15%'>
            <span class='checklist_${i}'></span>
          </td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamMulai'></td>
          <td class='center'><input type='time' data-nomor='${i}' id='jamSelesai'></td>
          <td class='center'>
            <div class='form-checkbox'>
                <input data-nomor='${i}' class='input-checkbox100' id='permisi ${i}' type='checkbox' value='permisi'>
                <label class='label-checkbox100' for='permisi ${i}'></label>
            </div>
          </td>
          <td class='center'>
            <div class='form-checkbox'>
                <input data-nomor='${i}' class='input-checkbox100' id='lembur ${i}' type='checkbox' value='lembur'>
                <label class='label-checkbox100' for='lembur ${i}'></label>
            </div>
          </td>
          <td class='center'>
            <div class='form-checkbox'>
                <input data-nomor='${i}' class='input-checkbox100' id='cuti ${i}' type='checkbox' value='cuti'>
                <label class='label-checkbox100' for='cuti ${i}'></label>
            </div>
          </td>
          <td class='center remove' id='${i}'><i class='far fa-minus btn' id='${i}' onclick='removeListAbsensi(${i})'></i></td>
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

// Autocomplete Progress Start
function autocomplete(lightbox) {
  const inputNama = lightbox.querySelector('#namaKaryawan');

  inputNama.addEventListener('input', async (e) => {
    if (e.target.getAttribute('id') == 'namaKaryawan') {
      const data = inputNama.parentNode;
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

  inputNama.addEventListener('click', (e) => {
    const grandParent = e.target.parentNode;

    if (e.target.classList.contains('item')) {
      const doc2 = a.querySelector('#uidKaryawan');
      selectList(grandParent, doc2);
    }
  });

  inputNama.parentNode.addEventListener('click', (e) => {
    // menghilangkan isi list autocomplete saat kita click diluar list Item
    if (e.target.classList.contains('item') === false) {
      const listAutocomplete = document.querySelector('.autocomplete');
      if (listAutocomplete === null) return;
      listAutocomplete.innerHTML = '';
      listAutocomplete.style.display = 'hidden';
      listAutocomplete.style.border = 'none';
    }
  });

  inputNama.addEventListener('mouseover', function (e) {
    // untuk menghilangkan Class Active di Item saat hover
    const target = e.target.classList.contains('item');
    if (target === true) {
      const item = data.querySelectorAll('.item');
      removeActive(item);
    }
  });

  inputNama.addEventListener('keydown', function (e) {
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
