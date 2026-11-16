(() => {
  'use strict';
  const $ = (selector, parent = document) => parent.querySelector(selector);
  const $$ = (selector, parent = document) => [...parent.querySelectorAll(selector)];
  const escapeHTML = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
  const rupiah = value => 'Rp' + Number(value || 0).toLocaleString('id-ID');
  const whatsappLink = (number, message) => `https://wa.me/${String(number || '').replace(/\D/g, '')}?text=${encodeURIComponent(message)}`;

  function setupSlider() {
    const slides = $$('.slide'), dots = $$('.dot');
    const next = $('.next'), prev = $('.prev');
    if (!slides.length) return;
    let active = 0, timer;
    const show = index => {
      active = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => slide.classList.toggle('active', i === active));
      dots.forEach((dot, i) => dot.classList.toggle('active', i === active));
    };
    const restart = () => { clearInterval(timer); timer = setInterval(() => show(active + 1), 5000); };
    next?.addEventListener('click', () => { show(active + 1); restart(); });
    prev?.addEventListener('click', () => { show(active - 1); restart(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { show(i); restart(); }));
    restart();
  }

  function renderCatalog(products) {
    const filter = $('#categoryFilter'), sectionsRoot = $('#productSections');
    const groups = new Map();
    products.forEach(product => {
      const key = product.category || 'lainnya';
      if (!groups.has(key)) groups.set(key, {label: product.category_label || 'Lainnya', products: []});
      groups.get(key).products.push(product);
    });
    $('#totalProducts').textContent = products.length;
    $('#allCount').textContent = products.length;
    filter.insertAdjacentHTML('beforeend', [...groups].map(([key, group]) =>
      `<button class="category-chip" data-category="${escapeHTML(key)}" type="button">${escapeHTML(group.label)} <b>${group.products.length}</b></button>`
    ).join(''));

    sectionsRoot.innerHTML = [...groups].map(([key, group]) => `
      <section class="category-section" data-section="${escapeHTML(key)}">
        <div class="category-heading"><h3>${escapeHTML(group.label)}</h3><span>${group.products.length} produk</span></div>
        <div class="product-grid">${group.products.map(product => {
          const image = product.image ? `${escapeHTML(product.image)}?v=${Date.now()}` : '';
          const terms = Array.isArray(product.terms) && product.terms.length
            ? `<div class="product-terms"><strong>Syarat &amp; Ketentuan:</strong><ul>${product.terms.map(term => `<li>${escapeHTML(term)}</li>`).join('')}</ul></div>` : '';
          return `<article class="product-card" data-name="${escapeHTML(String(product.name || '').toLocaleLowerCase('id-ID'))}" data-category="${escapeHTML(key)}">
            <div class="product-image">${image ? `<img src="${image}" alt="${escapeHTML(product.name)}" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><span class="product-placeholder" hidden>${escapeHTML(product.emoji || '🛒')}</span>` : `<span class="product-placeholder">${escapeHTML(product.emoji || '🛒')}</span>`}</div>
            <div class="product-info"><h4>${escapeHTML(product.name)}</h4>${terms}<div class="product-bottom"><strong>${rupiah(product.price)}</strong></div></div>
          </article>`;
        }).join('')}</div>
      </section>`).join('');

    let selectedCategory = 'all';
    const search = $('#productSearch'), noResults = $('#noResults');
    const applyFilter = () => {
      const keyword = (search.value || '').toLocaleLowerCase('id-ID').trim();
      let visible = 0;
      $$('.product-card', sectionsRoot).forEach(card => {
        const match = card.dataset.name.includes(keyword) && (selectedCategory === 'all' || card.dataset.category === selectedCategory);
        card.hidden = !match;
        if (match) visible++;
      });
      $$('.category-section', sectionsRoot).forEach(section => {
        section.hidden = !$$('.product-card', section).some(card => !card.hidden);
      });
      noResults.hidden = visible > 0;
    };
    filter.addEventListener('click', event => {
      const chip = event.target.closest('.category-chip');
      if (!chip) return;
      selectedCategory = chip.dataset.category;
      $$('.category-chip', filter).forEach(item => item.classList.toggle('active', item === chip));
      applyFilter();
    });
    search.addEventListener('input', applyFilter);
  }

  async function init() {
    $('#currentYear').textContent = new Date().getFullYear();
    setupSlider();
    try {
      const response = await fetch('data.json', {cache: 'no-store'});
      if (!response.ok) throw new Error(`Gagal memuat data.json (${response.status})`);
      const config = await response.json();
      if (!Array.isArray(config.products)) throw new Error('Format daftar produk pada data.json tidak sesuai.');
      const number = config.whatsappNumber || '';
      $('#partnerLink').href = whatsappLink(number, 'Halo KDKMP Bakulan, saya ingin bertanya mengenai kemitraan atau menawarkan produk UMKM.');
      $('#floatingWa').href = whatsappLink(number, 'Halo KDKMP Bakulan, saya ingin bertanya tentang produk atau kemitraan.');
      renderCatalog(config.products);
    } catch (error) {
      console.error(error);
      $('#productSections').innerHTML = '<p class="no-results">Data produk gagal dimuat. Pastikan file data.json tersedia di folder yang sama dengan index.html.</p>';
      $('#totalProducts').textContent = '0';
      $('#allCount').textContent = '0';
    }
  }
  init();
})();
