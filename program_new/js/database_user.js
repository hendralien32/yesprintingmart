function test(id) {
  const checkbox = document.querySelectorAll(`.${id}`);
  [...checkbox].map((p) => (p.checked == true ? (p.checked = true) : (p.checked = false)));
}
