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

function judulForm(dataBtn) {
  let namaForm = dataBtn.dataset.form;

  var obj = {
    absensi: 'Absensi Harian',
    absensi_individu: 'Absensi Personal',
  };

  return obj[namaForm];
}
