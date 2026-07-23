// ================================================
// বইঘর - Main Application JavaScript
// ================================================

document.addEventListener('DOMContentLoaded', () => {

    // ======= PRELOADER =======
    setTimeout(() => {
        const pre = document.getElementById('preloader');
        if (pre) { pre.classList.add('hidden'); setTimeout(() => pre.remove(), 500); }
    }, 600);

    // ======= DARK MODE =======
    const html = document.documentElement;
    const darkBtn = document.getElementById('darkModeToggle');
    const darkIcon = document.getElementById('darkIcon');

    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateDarkIcon(savedTheme);

    darkBtn?.addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateDarkIcon(next);
    });

    function updateDarkIcon(theme) {
        if (!darkIcon) return;
        darkIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // ======= HEADER SCROLL =======
    const header = document.getElementById('siteHeader');
    window.addEventListener('scroll', () => {
        header?.classList.toggle('scrolled', window.scrollY > 20);
        document.getElementById('scrollTop')?.classList.toggle('visible', window.scrollY > 300);
    }, { passive: true });

    document.getElementById('scrollTop')?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ======= MOBILE MENU =======
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');
    mobileMenuBtn?.addEventListener('click', () => {
        mainNav?.classList.toggle('open');
        const icon = mobileMenuBtn.querySelector('i');
        icon.className = mainNav?.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
    });

    // ======= USER DROPDOWN =======
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = userMenuBtn?.closest('.dropdown');
    userMenuBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown?.classList.toggle('open');
    });

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
    });

    // ======= HERO SWIPER =======
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: { delay: 4500, disableOnInteraction: false },
            effect: 'fade',
            fadeEffect: { crossFade: true },
            pagination: { el: '.swiper-pagination', clickable: true },
            speed: 800,
        });
    }

    // ======= BOOKS SWIPER =======
    document.querySelectorAll('.books-swiper').forEach(el => {
        new Swiper(el, {
            slidesPerView: 'auto',
            spaceBetween: 16,
            freeMode: true,
            navigation: { nextEl: el.closest('.books-section')?.querySelector('.swiper-next'), prevEl: el.closest('.books-section')?.querySelector('.swiper-prev') },
            breakpoints: {
                320: { slidesPerView: 2.3 },
                480: { slidesPerView: 3.3 },
                768: { slidesPerView: 4.5 },
                1024: { slidesPerView: 5.5 },
                1280: { slidesPerView: 6.5 },
            }
        });
    });

    // ======= CSRF for AJAX =======
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    async function postRequest(url, data = {}) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(data),
        });
        return res.json();
    }

    // ======= FAVORITE TOGGLE =======
    document.querySelectorAll('.js-favorite').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault(); e.stopPropagation();
            const slug = btn.dataset.slug;
            try {
                const data = await postRequest(`/books/${slug}/favorite`);
                if (data.success) {
                    btn.classList.toggle('active', data.favorited);
                    btn.querySelector('i').className = data.favorited ? 'fas fa-heart' : 'far fa-heart';
                    showToast(data.favorited ? 'ফেভারিটে যোগ হয়েছে' : 'ফেভারিট থেকে সরানো হয়েছে', data.favorited ? 'success' : 'info');
                } else if (data.message === 'লগইন করুন') {
                    window.location.href = '/login';
                }
            } catch { }
        });
    });

    // ======= BOOKMARK TOGGLE =======
    document.querySelectorAll('.js-bookmark').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const slug = btn.dataset.slug;
            try {
                const data = await postRequest(`/books/${slug}/bookmark`);
                if (data.success) {
                    btn.classList.toggle('active', data.bookmarked);
                    showToast(data.bookmarked ? 'বুকমার্ক করা হয়েছে' : 'বুকমার্ক সরানো হয়েছে');
                }
            } catch { }
        });
    });

    // ======= STAR RATING =======
    document.querySelectorAll('.star-rating').forEach(container => {
        const stars = container.querySelectorAll('.star');
        const input = container.closest('.review-form')?.querySelector('[name="rating"]');

        stars.forEach((star, i) => {
            star.addEventListener('mouseover', () => {
                stars.forEach((s, j) => s.classList.toggle('active', j <= i));
            });
            star.addEventListener('click', () => {
                stars.forEach((s, j) => s.classList.toggle('active', j <= i));
                if (input) input.value = i + 1;
                container.dataset.selected = i + 1;
            });
        });
        container.addEventListener('mouseleave', () => {
            const sel = parseInt(container.dataset.selected || 0);
            stars.forEach((s, j) => s.classList.toggle('active', j < sel));
        });
    });

    // ======= REVIEW FORM =======
    const reviewForm = document.getElementById('reviewForm');
    reviewForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const slug = reviewForm.dataset.slug;
        const rating = reviewForm.querySelector('[name="rating"]')?.value;
        const review = reviewForm.querySelector('[name="review"]')?.value;
        if (!rating) { showToast('রেটিং দিন', 'error'); return; }
        const data = await postRequest(`/books/${slug}/review`, { rating, review });
        showToast(data.message || 'রিভিউ সংরক্ষণ হয়েছে', data.success ? 'success' : 'error');
    });

    // ======= QR CODE MODAL =======
    document.querySelector('.js-qr-code')?.addEventListener('click', () => {
        document.getElementById('qrModal')?.classList.add('open');
    });
    document.querySelector('.js-close-modal')?.addEventListener('click', () => {
        document.getElementById('qrModal')?.classList.remove('open');
    });
    document.querySelector('.modal-overlay')?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });

    // ======= AD WATCH =======
    document.querySelectorAll('.js-watch-ad').forEach(btn => {
        btn.addEventListener('click', async () => {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা করুন...';
            // Simulate ad watch (3 seconds)
            await new Promise(r => setTimeout(r, 3000));
            const data = await postRequest('/user/ad-watch');
            showToast(data.message || 'পয়েন্ট পেয়েছেন!', 'success');
            // Update points display
            document.querySelectorAll('.js-points-display').forEach(el => el.textContent = numberFormat(data.points || 0));
            btn.innerHTML = '<i class="fas fa-check-circle"></i> পয়েন্ট পেয়েছেন!';
            setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-play-circle"></i> বিজ্ঞাপন দেখুন ও পয়েন্ট নিন'; }, 5000);
        });
    });

    // ======= COPY REFERRAL =======
    document.querySelectorAll('.js-copy-ref').forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.dataset.copy;
            navigator.clipboard.writeText(text).then(() => showToast('কপি হয়েছে!'));
        });
    });

    // ======= SEARCH AUTOCOMPLETE =======
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const q = searchInput.value.trim();
        if (q.length < 2) { hideSearchSuggestions(); return; }
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(q)}&ajax=1`, { headers: { 'Accept': 'application/json' } });
                // For now just show the form submits
            } catch { }
        }, 300);
    });
    function hideSearchSuggestions() { /* placeholder */ }

    // ======= TOAST NOTIFICATION =======
    function showToast(msg, type = 'success') {
        const old = document.getElementById('jsToast');
        if (old) old.remove();
        const toast = document.createElement('div');
        toast.id = 'jsToast';
        toast.className = `alert-toast ${type}`;
        const icons = { success: 'check-circle', error: 'exclamation-circle', info: 'info-circle' };
        toast.innerHTML = `<i class="fas fa-${icons[type] || 'check-circle'}"></i> ${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // ======= NUMBER FORMAT =======
    function numberFormat(n) {
        return new Intl.NumberFormat('bn-BD').format(n);
    }

    // ======= AUTO-DISMISS TOAST =======
    document.querySelectorAll('.alert-toast').forEach(t => {
        setTimeout(() => t.style.opacity = '0', 3500);
    });

    // ======= IMAGE LAZY LOAD =======
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
                    observer.unobserve(img);
                }
            });
        }, { rootMargin: '200px' });
        document.querySelectorAll('img[data-src]').forEach(img => observer.observe(img));
    }

    // ======= READING MODE =======
    document.getElementById('readingModeBtn')?.addEventListener('click', () => {
        document.documentElement.classList.toggle('reading-mode');
        const on = document.documentElement.classList.contains('reading-mode');
        localStorage.setItem('readingMode', on ? '1' : '');
    });

    // Expose showToast globally
    window.showToast = showToast;
});
