window.addEventListener('load', async function () {
  const tglDari = document.getElementById('search_drTgl').value;
  const tglke = document.getElementById('search_keTgl').value;
  const username = document.getElementById('search_user').value;
  let variable;
  variable = `tglDari=${tglDari}&tglke=${tglke}&username=${username} `;

  const data = await getContent('absensi', variable);
  updateContent(data);
});
