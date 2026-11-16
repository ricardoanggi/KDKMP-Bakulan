(() => {
    // Banner otomatis
    const slides = [...document.querySelectorAll('.slide')];
    const dots = [...document.querySelectorAll('.dot')];
    const next = document.querySelector('.next');
    const prev = document.querySelector('.prev');
    let active = 0;
    let timer;

    function showSlide(index) {
        active = (index + slides.length) % slides.length;
        slides.forEach((s, i) => s.classList.toggle('active', i === active));
        dots.forEach((d, i) => d.classList.toggle('active', i === active));
    }
    function restartTimer() {
        clearInterval(timer);
        timer = setInterval(() => showSlide(active + 1), 5000);
    }
    if (slides.length) {
        next.addEventListener('click', () => { showSlide(active + 1); restartTimer(); });
        prev.addEventListener('click', () => { showSlide(active - 1); restartTimer(); });
        dots.forEach((dot, i) => dot.addEventListener('click', () => { showSlide(i); restartTimer(); }));
        restartTimer();
    }

    // Filter kategori dan pencarian
    const search = document.getElementById('productSearch');
    const chips = [...document.querySelectorAll('.category-chip')];
    const cards = [...document.querySelectorAll('.product-card')];
    const sections = [...document.querySelectorAll('.category-section')];
    const noResults = document.getElementById('noResults');
    let selectedCategory = 'all';

    function filterProducts() {
        const keyword = (search.value || '').toLocaleLowerCase('id-ID').trim();
        let visibleTotal = 0;

        cards.forEach(card => {
            const matchesName = card.dataset.name.includes(keyword);
            const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
            const visible = matchesName && matchesCategory;
            card.hidden = !visible;
            if (visible) visibleTotal++;
        });

        sections.forEach(section => {
            const hasVisible = [...section.querySelectorAll('.product-card')].some(card => !card.hidden);
            section.hidden = !hasVisible;
        });

        noResults.hidden = visibleTotal !== 0;
    }

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            selectedCategory = chip.dataset.category;
            chips.forEach(item => item.classList.toggle('active', item === chip));
            filterProducts();
        });
    });

    search.addEventListener('input', filterProducts);
})();
