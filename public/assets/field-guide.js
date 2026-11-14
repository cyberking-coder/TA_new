const menu = document.querySelector('.menu');
const nav = document.querySelector('#mainnav');
function setMenu(open) {
  menu.setAttribute('aria-expanded', String(open));
  menu.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  menu.textContent = open ? 'Close' : 'Menu';
  nav.classList.toggle('open', open);
}
menu.addEventListener('click', () => setMenu(menu.getAttribute('aria-expanded') !== 'true'));
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && nav.classList.contains('open')) {
    setMenu(false);
    menu.focus();
  }
});
const query = document.querySelector('#query');
if (query) {
  const kind = document.querySelector('#kind');
  const rows = [...document.querySelectorAll('.notice')];
  function filter() {
    const term = query.value.trim().toLowerCase();
    let count = 0;
    for (const row of rows) {
      row.hidden = !row.textContent.toLowerCase().includes(term) || Boolean(kind.value && kind.value !== row.dataset.kind);
      if (!row.hidden) count++;
    }
    document.querySelector('#count').textContent = count + (count === 1 ? ' notice' : ' notices');
    document.querySelector('#empty').hidden = count > 0;
  }
  query.addEventListener('input', filter);
  kind.addEventListener('change', filter);
}
