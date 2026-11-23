// ===== Interaksi umum website SMK INFOKOM BOGOR =====
document.addEventListener('DOMContentLoaded', function () {

  // --- Counter statistik (animasi angka saat terlihat di layar) ---
  const counters = document.querySelectorAll('.stat-number');

  const animate = (el) => {
    const target = parseInt(el.dataset.target, 10) || 0;
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 60));
    const interval = setInterval(() => {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(interval);
      }
      el.textContent = current.toLocaleString('id-ID');
    }, 25);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animate(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach((c) => observer.observe(c));

  // --- Navbar: bayangan saat scroll; disembunyikan ke atas saat scroll ke bawah
  //     (termasuk saat sampai footer) dan muncul lagi saat scroll ke atas ---
  const navbar = document.querySelector('.site-navbar');
  if (navbar) {
    const navCollapse = document.getElementById('navMain');
    let lastY = window.scrollY;
    const onScroll = () => {
      const y = window.scrollY;
      const menuTerbuka = !!(navCollapse && navCollapse.classList.contains('show'));
      navbar.classList.toggle('is-scrolled', y > 8);
      if (menuTerbuka || y <= 140) {
        navbar.classList.remove('is-hidden');
      } else if (y > lastY + 4) {
        navbar.classList.add('is-hidden');    // scroll ke bawah -> navbar naik
      } else if (y < lastY - 4) {
        navbar.classList.remove('is-hidden'); // scroll ke atas -> navbar turun lagi
      }
      lastY = y;
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    // Sinkron saat menu mobile dibuka/ditutup tanpa scroll (event Bootstrap)
    if (navCollapse) {
      navCollapse.addEventListener('shown.bs.collapse', onScroll);
      navCollapse.addEventListener('hidden.bs.collapse', onScroll);
    }
    onScroll();
  }

  // --- Tombol kembali ke atas ---
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    const toggleBtn = () => backToTop.classList.toggle('show', window.scrollY > 400);
    window.addEventListener('scroll', toggleBtn, { passive: true });
    toggleBtn();
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }
});
