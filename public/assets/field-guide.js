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

// Hero slider: smooth cross-fade + Ken-Burns zoom between .hero-slide images.
// Each slide displays for its own data-duration (ms); the fade itself is 1600ms
// (CSS-driven), and the outgoing slide keeps is-active during the fade so both
// are rendered mid-transition (true crossfade, no flash of background).
(function initHeroSlider(){
    const slides = Array.from(document.querySelectorAll('.hero-slider .hero-slide'));
    if (slides.length < 2) return;

    const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const FADE_MS = reduced ? 400 : 1600;
    let i = 0;

    function prefetch(img){ if (img && 'decode' in img) img.decode().catch(() => {}); }

    function step(){
        const prev = slides[i];
        i = (i + 1) % slides.length;
        const cur = slides[i];
        prefetch(cur);
        cur.classList.add('is-active');
        setTimeout(() => prev.classList.remove('is-active'), FADE_MS);
        const d = Number(cur.dataset.duration) || 5000;
        setTimeout(step, Math.max(FADE_MS + 200, d));
    }

    prefetch(slides[1]);
    const firstDuration = Number(slides[0].dataset.duration) || 5000;
    setTimeout(step, Math.max(FADE_MS + 200, firstDuration));
})();
