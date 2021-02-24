onload = function () {
	loadList();
}

function search_display() {
    const div_class = document.getElementById("plugin-search");
    const div_search = document.getElementById("button-search");

    if(div_class.classList.contains("display-none")) {
        div_search.innerHTML = "<i class='fas fa-search-minus'></i>";
        div_class.classList.replace("display-none", "display-show");
    } else {
        div_search.innerHTML = "<i class='fas fa-search-plus'></i>";
        div_class.classList.replace("display-show", "display-none");
    }
}

function loadList() {
    const search_client = document.getElementById("search_client").value;
    const search_data = document.getElementById("search_data").value;
    const search_drTgl = document.getElementById("search_drTgl").value;
    const search_keTgl = document.getElementById("search_keTgl").value;

    let variable;
	variable =
		"client=" + search_client +
		"&date=" + search_data + 
		"&drTgl=" + search_drTgl + 
		"&keTgl=" + search_keTgl
	;

    var xhr = ajaxReq();
    var url = "ajax/sales_order_ajax.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send(variable);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                document.getElementById("ajax_load").innerHTML = xhr.responseText;

                const jumlah_order = document.querySelector("span#jumlah_order").innerHTML;
                document.getElementById("right_title").innerHTML = jumlah_order + ' Order';

            } catch (error) {
                throw Error;
            }
        }
    }
}
