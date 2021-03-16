window.addEventListener('load', async function () {
  const blnDari = document.getElementById('search_drBln').value;
  const blnKe = document.getElementById('search_keBln').value;
  const username = document.getElementById('search_user').value;
  let variable;
  variable = `blnDari=${blnDari}&blnke=${blnKe}&username=${username} `;

  const data = await getContent('absensi', variable);
  updateContent(data);
});

// function menampilkan search list saat tombol search di tekan
const btnSearch = document.querySelector('.button-search');
btnSearch.onclick = function () {
  const divSearch = document.querySelector('.plugin-search');
  divSearch.classList.toggle('display-show');
  if (divSearch.classList.contains('display-show')) {
    btnSearch.innerHTML = "<i class='fas fa-search-minus'></i>";
  } else {
    btnSearch.innerHTML = "<i class='fas fa-search-plus'></i>";
  }
};

//di load oleh script.js
function judulForm(dataBtn) {
  let namaForm = dataBtn.dataset.form;

  var obj = {
    absensi: 'Absensi Harian',
    absensi_individu: 'Absensi Personal',
  };

  return obj[namaForm];
}

function updateform(ajaxFormLoad) {
  const content = document.querySelector('.lightBoxContent');
  content.innerHTML = ajaxFormLoad;
  valCheckBox();
  submit();
}
// selesai Load oleh Script.js

function valCheckBox() {
  document.addEventListener('click', function (e) {
    const dataUID = e.target.dataset.uid;
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
  });
}

function submit() {
  const btnSubmit = document.querySelector('#submit');
  btnSubmit.addEventListener('click', function (e) {
    const tglAbsensi = document.querySelector('#tglAbsensi').value;
    const karyawanUid = document.querySelectorAll('#karyawanUid');
    const scanMasuk = document.querySelectorAll('#scanMasuk');
    const scanKeluar = document.querySelectorAll('#scanKeluar');
    const absensiCB = document.querySelectorAll('#Absen');
    const cutiCB = document.querySelectorAll('#Cuti');
    let valuekaryawanUid = [];
    let valueScanMasuk = [];
    let valueScanKeluar = [];
    let valueAbsensiCB = [];
    let valueCutiCB = [];
    for (let i = 0; i < scanMasuk.length; i++) {
      valuekaryawanUid.push(karyawanUid[i].value);
      valueScanMasuk.push(scanMasuk[i].value);
      valueScanKeluar.push(scanKeluar[i].value);
      valueAbsensiCB.push(absensiCB[i].checked == true ? 'Y' : 'N');
      valueCutiCB.push(cutiCB[i].checked == true ? 'Y' : 'N');
    }

    let variable;
    variable = `tglAbensi=${tglAbsensi}&scanMasuk=${valueScanMasuk}&scanKeluar=${valueScanKeluar}&absensiCB=${valueAbsensiCB}&cutiCB=${valueCutiCB}&uid=${valuekaryawanUid}&typeProgress=Insert_Absensi`;

    fetch(`../program_new/progress/progress.php`, {
      method : 'POST',
      body: `${variable}`,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
    })
      .then(Response => Response.text())
      .then(Response => console.log(Response));


  });
}
