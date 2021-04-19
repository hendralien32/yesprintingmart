function test(id) {
  const checkbox = document.querySelectorAll(`.${id}`);
  [...checkbox].map(function (p) {
    if (document.querySelector(`.page_${id}`).checked == true) {
      p.checked = true;
    } else {
      p.checked = false;
    }
  });
}

function variable(lightbox, id, tipe) {
  switch (tipe) {
    case 'Add_User':
      const username = lightbox.querySelector('#username').value;
      const password = lightbox.querySelector('#password').value;
      const retypePassword = lightbox.querySelector('#retypePassword').value;
      const nama = lightbox.querySelector('#nama').value;
      const tglMasuk = lightbox.querySelector('#tglMasuk').value;
      const tglKeluar = lightbox.querySelector('#tglKeluar').value;
      const iconError = `<i class='fas fa-times'></i>`;
      let error;

      if (username == '') {
        error = 'Username tidak boleh Kosong';
        lightbox.querySelector('#alert_username').innerHTML = iconError;
      } else if (password == '') {
        error = 'password tidak boleh Kosong';
        lightbox.querySelector('#alert_username').innerHTML = ``;
        lightbox.querySelector('#alert_password').innerHTML = iconError;
      } else if (retypePassword == '') {
        error = 'Retype Password tidak boleh Kosong';
        lightbox.querySelector('#alert_retype').innerHTML = iconError;
      } else if (password !== retypePassword) {
        error = 'Password tidak sama';
        const alert = lightbox.querySelectorAll('#alert_password, #alert_retype');
        [...alert].map((a) => (a.innerHTML = iconError));
      } else {
        const alert = lightbox.querySelectorAll('#alert_username, #alert_password, #alert_retype');
        [...alert].map((a) => (a.innerHTML = ''));
        error = '';
      }

      const variable = `username=${username}&password=${password}&nama=${nama}&tglMasuk=${tglMasuk}&tglKeluar=${tglKeluar}&error=${error}&typeProgress=insert_user`;

      console.log(variable);
      break;
    default:
      console.log('ERROR 404');
  }
}
