window.addEventListener('load', function () {
  loading();
  loadList();
});

const tSearch = document.getElementById('button-search');

tSearch.onclick = function () {
  const div_class = document.getElementById('plugin-search'),
    div_search = document.getElementById('button-search');

  if (div_class.classList.contains('display-none')) {
    div_search.innerHTML = "<i class='fas fa-search-minus'></i>";
    div_class.classList.replace('display-none', 'display-show');
  } else {
    div_search.innerHTML = "<i class='fas fa-search-plus'></i>";
    div_class.classList.replace('display-show', 'display-none');
  }
};

function loadList() {
  const search_client = document.getElementById('search_client').value,
    search_data = document.getElementById('search_data').value,
    search_drTgl = document.getElementById('search_drTgl').value,
    search_keTgl = document.getElementById('search_keTgl').value,
    search_limit = document.getElementById('search_limit').value,
    search_Setter = document.getElementById('ID_Setter').value;

  let variable;
  variable = 'client=' + search_client + '&data=' + search_data + '&drTgl=' + search_drTgl + '&keTgl=' + search_keTgl + '&limit=' + search_limit + '&Setter=' + search_Setter;

  const xhr = ajaxReq();
  let url = 'ajax/sales_order_ajax.php';
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
  xhr.send(variable);

  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      try {
        document.getElementById('ajax_load').innerHTML = xhr.responseText;

        const jumlah_order = document.querySelector('span#jumlah_order').innerHTML;
        document.getElementById('right_title').innerHTML = jumlah_order;
      } catch (error) {
        throw Error;
      }
    }
  };
}

function SearchData() {
  const search_client = document.getElementById('search_client').value,
    search_data = document.getElementById('search_data').value;

  if (search_client != '' || search_data != '') {
    document.getElementById('search_drTgl').value = '';
    document.getElementById('search_keTgl').value = '';
  }

  loading();
  loadList();
}

function SearchDate() {
  loading();
  loadList();
}

function SearchSetter() {
  const Setter_ID = document.getElementById('search_Setter').value;
  document.getElementById('ID_Setter').value = Setter_ID;
  loading();
  loadList();
}

function finished(id, status) {
  if (status == 'N') {
    var sSelesai = 'selesai';
  } else {
    var sSelesai = '';
  }
}
