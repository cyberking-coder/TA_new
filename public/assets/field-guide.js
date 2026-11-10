const menu = document.querySelector('.menu');
const nav = document.querySelector('#mainnav');
function toggleMenu(open) {
  menu.setAttribute('aria-expanded', String(open));
  menu.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  nav.classList.toggle('open',open);
}
menu.addEventListener('click',()=>toggleMenu(menu.getAttribute('aria-expanded')!=='true'));
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&nav.classList.contains('open')){toggleMenu(false);menu.focus();}});
const query=document.querySelector('#query');
if(query) {
  const kind=document.querySelector('#kind'), exam=document.querySelector('#exam');
  const rows=[...document.querySelectorAll('.entries .notice-row')];
  function applyFilters() {
    const term=query.value.trim().toLowerCase();
    let count=0;
    for(const row of rows){
      const matches=(!term||row.textContent.toLowerCase().includes(term))&&(kind.value==='All types'||row.dataset.kind===kind.value)&&(exam.value==='All exams'||row.dataset.exam===exam.value);
      row.hidden=!matches;
      if(matches) count++;
    }
    document.querySelector('#count').textContent=count+' notice'+(count===1?'':'s');
    document.querySelector('#empty').hidden=Boolean(count);
  }
  query.addEventListener('input',applyFilters);kind.addEventListener('change',applyFilters);exam.addEventListener('change',applyFilters);
  document.querySelector('#reset').addEventListener('click',()=>{query.value='';kind.selectedIndex=0;exam.selectedIndex=0;applyFilters();query.focus();});
}
const form=document.querySelector('#demoform');
if(form) form.addEventListener('submit',e=>{e.preventDefault();form.reset();document.querySelector('#feedback').hidden=false;});
