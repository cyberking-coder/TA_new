// Hero slider: crossfade between .hero-slide images using each slide's data-duration (ms).
(function(){
  function startSlider(){
    const slides = Array.from(document.querySelectorAll('.hero-slider .hero-slide'));
    console.log('[hero-slider] found ' + slides.length + ' slide(s)');
    if (slides.length === 0) return;
    slides.forEach((s, idx) => {
      s.classList.toggle('is-active', idx === 0);
      s.addEventListener('error', () => console.warn('[hero-slider] slide ' + idx + ' failed to load: ' + s.src));
    });
    if (slides.length < 2) {
      console.log('[hero-slider] only one slide, nothing to rotate');
      return;
    }
    let i = 0;
    function next(){
      const current = slides[i];
      i = (i + 1) % slides.length;
      const incoming = slides[i];
      current.classList.remove('is-active');
      incoming.classList.add('is-active');
      const dur = parseInt(incoming.dataset.duration, 10) || 5000;
      console.log('[hero-slider] showing slide ' + i + ' for ' + dur + 'ms');
      setTimeout(next, dur);
    }
    const first = parseInt(slides[0].dataset.duration, 10) || 5000;
    console.log('[hero-slider] first rotation in ' + first + 'ms');
    setTimeout(next, first);
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startSlider);
  } else {
    startSlider();
  }
})();

// Mobile menu + notice filter (from the kit).
(function(){
  const menu = document.querySelector('.menu');
  const nav = document.querySelector('#mainnav');
  if (menu && nav) {
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
  }

  const query = document.querySelector('#query');
  if (query) {
    const kind = document.querySelector('#kind');
    const rows = [...document.querySelectorAll('.notice')];
    function filter() {
      const term = query.value.trim().toLowerCase();
      let count = 0;
      for (const row of rows) {
        row.hidden = !row.textContent.toLowerCase().includes(term) || Boolean(kind && kind.value && kind.value !== row.dataset.kind);
        if (!row.hidden) count++;
      }
      const c = document.querySelector('#count');
      if (c) c.textContent = count + (count === 1 ? ' notice' : ' notices');
      const e = document.querySelector('#empty');
      if (e) e.hidden = count > 0;
    }
    query.addEventListener('input', filter);
    if (kind) kind.addEventListener('change', filter);
  }
})();
