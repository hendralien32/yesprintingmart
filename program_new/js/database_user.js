window.addEventListener('load', loadPage);

async function loadPage() {
  loading();

  const ajaxPage = document.querySelector('.left_title').innerHTML.toLowerCase().replace(' ', '_');
  const variable = ``;

  const data = await getContent(ajaxPage, variable);
  updateContent(data);
}

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
      let variable = {};
      let error;
      const username = lightbox.querySelector('#username').value;
      const password = lightbox.querySelector('#password').value;
      const retypePassword = lightbox.querySelector('#retypePassword').value;
      const nama = lightbox.querySelector('#nama').value;
      const tglMasuk = lightbox.querySelector('#tglMasuk').value;
      const tglKeluar = lightbox.querySelector('#tglKeluar').value;
      const page = lightbox.querySelectorAll('.pageName');
      const alertUsername = lightbox.querySelector('#alert_username');
      const listPage = [...page].map((obj) => obj.innerHTML.replace(/\s/g, '') + '|' + obj.dataset.pageid).join(',');

      [...page].map((obj) => {
        const NameObj = obj.innerHTML.replace(/\s/g, '');
        const checkbox = obj.innerHTML.replace(/\s/g, '_');
        Object.assign(variable, {
          [NameObj]: [...lightbox.querySelectorAll(`.${checkbox}`)].map((a) => (a.checked == true ? 'Y' : 'N')).join(','),
        });
      });

      const iconError = `<i class='fas fa-times'></i>`;
      if ([...username].length == 0) {
        error = 'Username tidak boleh Kosong ';
        lightbox.querySelector('#alert_username').innerHTML = iconError;
      } else if ([...username].length < 3) {
        error = 'Username harus melebihi 3 huruf';
        lightbox.querySelector('#alert_username').innerHTML = iconError;
      } else if (alertUsername.innerHTML != '') {
        error = 'Username Sudah Terdaftar';
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
      } else if (nama == '') {
        error = 'Nama tidak boleh Kosong';
        const alert = lightbox.querySelectorAll('#alert_username, #alert_password, #alert_retype');
        [...alert].map((a) => (a.innerHTML = ''));
        lightbox.querySelector('#alert_nama').innerHTML = iconError;
      } else {
        const alert = lightbox.querySelectorAll('#alert_username, #alert_password, #alert_retype, #alert_nama');
        [...alert].map((a) => (a.innerHTML = ''));
        error = '';
      }

      Object.assign(variable, {
        listPage: listPage,
        username: username,
        password: password,
        nama: nama,
        tglMasuk: tglMasuk,
        tglKeluar: tglKeluar,
        error: error,
        typeProgress: 'insert_user',
      });

      return JSON.stringify(variable);
      break;
    case 'Form_hapusUser':
    case 'Form_resetPassword':
    case 'Form_EditProfile':
      let data = {};

      Object.assign(data, {
        idUser: id,
        error: false,
        typeProgress: 'Edit_Profile',
      });

      console.log(JSON.stringify(data));
      break;
    default:
      console.log('ERROR 404');
  }
}

async function validasi(jenis) {
  const iconError = `<i class='fas fa-times'></i>`;

  switch (jenis) {
    case 'username':
      const username = document.getElementById('username').value;
      const alertUsername = document.querySelector('#alert_username');
      const jumlah = await getValidation(jenis, username);

      if ([...username].length < 3) {
        console.log([...username].length);
        alertUsername.innerHTML = iconError;
      } else if (jumlah == '1') {
        alertUsername.innerHTML = iconError;
      } else {
        alertUsername.innerHTML = '';
      }

      break;

    default:
      console.log('ERROR 404');
  }
}

function getValidation(jenis, data) {
  return fetch(`../program_new/progress/validation.php`, {
    method: 'POST',
    body: `typevalidation=${jenis}&data=${data}`,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  })
    .then((response) => response.text())
    .then((response) => response);
}
