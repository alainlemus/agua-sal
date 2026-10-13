// Capa de movimiento: reveals, parallax, tilt, magnéticos, contadores y spotlight.
// Se engancha por selectores para cubrir todos los bloques del CMS sin tocar cada vista.

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const root = document.documentElement;

const REVEAL_SELECTOR = [
    'main section h2', 'main section h3', 'main section > div > p',
    'main section img', 'main section video',
    'main section [class*="rounded"][class*="border"]',
    'main section [class*="rounded"][class*="shadow"]',
    'main section form', 'main section iframe', 'main section blockquote',
    'main section .grid > *', 'footer > div > *',
].join(',');

const hasOwnAnimation = (el) => {
    for (let n = el; n && n !== document.body; n = n.parentElement) {
        for (const a of n.attributes) {
            if (a.name.startsWith('x-intersect') || a.name === 'x-transition' || a.name.startsWith('data-no-motion')) return true;
        }
        if (n.matches?.('.animate-fade-in-up, .animate-bubble, .animate-sparkle')) return true;
    }
    return false;
};

const inFirstScreen = (el) => el.getBoundingClientRect().top < window.innerHeight * 0.9;

/* ── Reveal on scroll con stagger por hermanos ─────────────────────────── */
let revealObserver;
const revealed = new WeakSet();

function initReveal() {
    if (reduced) return;
    revealObserver ??= new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (!e.isIntersecting) return;
            const el = e.target;
            revealed.add(el);
            el.classList.add('is-in');
            revealObserver.unobserve(el);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    const hero = document.querySelector('main section');
    document.querySelectorAll(REVEAL_SELECTOR).forEach((el) => {
        if (el.dataset.reveal !== undefined || hero?.contains(el) || hasOwnAnimation(el)) return;
        // evita doble reveal anidado: si un ancestro ya es reveal, solo el ancestro anima
        if (el.parentElement?.closest('[data-reveal]')) return;
        if (revealed.has(el)) { el.dataset.reveal = ''; el.classList.add('is-in'); return; }

        const siblings = el.parentElement ? [...el.parentElement.children].filter((c) => c.matches(REVEAL_SELECTOR)) : [];
        const i = Math.max(0, siblings.indexOf(el));
        el.style.setProperty('--reveal-delay', `${Math.min(i, 8) * 90}ms`);
        el.dataset.reveal = el.matches('img, video, iframe') ? 'zoom' : '';
        revealObserver.observe(el);
    });
    root.classList.add('motion-ready');
}

/* ── Titular del hero palabra por palabra ──────────────────────────────── */
function initHeroText() {
    const h1 = document.querySelector('main h1');
    if (!h1 || h1.dataset.split || reduced) return;
    h1.dataset.split = '1';
    h1.classList.remove('opacity-0', 'animate-fade-in-up');
    h1.style.opacity = '1';
    h1.style.animation = 'none';
    const label = h1.textContent.trim();
    h1.setAttribute('aria-label', label);
    h1.innerHTML = label.split(/\s+/).map((w, i) =>
        `<span class="hero-word" aria-hidden="true"><span style="--i:${i}">${w}</span></span>`
    ).join(' ');
}

/* ── Parallax + fade del hero, y zoom suave de imágenes al scrollear ───── */
let ticking = false;
function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
        const y = window.scrollY;
        const h = document.documentElement.scrollHeight - window.innerHeight;
        root.style.setProperty('--scroll-progress', h > 0 ? (y / h).toFixed(4) : '0');

        const hero = document.querySelector('main section');
        if (hero && y < window.innerHeight * 1.2) {
            const p = Math.min(1, y / window.innerHeight);
            hero.querySelectorAll('img, video').forEach((m) => {
                if (m.closest('.hero-fg')) return;
                m.style.transform = `translate3d(0, ${y * 0.28}px, 0) scale(${1.05 + p * 0.12})`;
            });
            const content = hero.querySelector('.relative.z-10');
            if (content) {
                content.style.transform = `translate3d(0, ${y * -0.12}px, 0)`;
                content.style.opacity = String(1 - p * 1.25);
            }
        }
        ticking = false;
    });
}

/* ── Tilt 3D con brillo en tarjetas con imagen ─────────────────────────── */
function initTilt() {
    if (reduced || !finePointer) return;
    document.querySelectorAll('main section [class*="rounded"][class*="border"]').forEach((card) => {
        if (card.dataset.tilt || !card.querySelector('img') || card.querySelector('form') || hasOwnAnimation(card)) return;
        if (card.getBoundingClientRect().width > 700) return;
        card.dataset.tilt = '1';
        card.classList.add('tilt-card');
        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width;
            const y = (e.clientY - r.top) / r.height;
            card.style.setProperty('--rx', `${(0.5 - y) * 8}deg`);
            card.style.setProperty('--ry', `${(x - 0.5) * 10}deg`);
            card.style.setProperty('--gx', `${x * 100}%`);
            card.style.setProperty('--gy', `${y * 100}%`);
        });
        card.addEventListener('pointerleave', () => {
            card.style.setProperty('--rx', '0deg');
            card.style.setProperty('--ry', '0deg');
        });
    });
}

/* ── Botones magnéticos ────────────────────────────────────────────────── */
function initMagnetic() {
    if (reduced || !finePointer) return;
    document.querySelectorAll('nav a[class*="uppercase"], main a[class*="uppercase"][class*="rounded"], main button[type="submit"]').forEach((btn) => {
        if (btn.dataset.magnetic) return;
        btn.dataset.magnetic = '1';
        btn.classList.add('magnetic');
        btn.addEventListener('pointermove', (e) => {
            const r = btn.getBoundingClientRect();
            const dx = e.clientX - (r.left + r.width / 2);
            const dy = e.clientY - (r.top + r.height / 2);
            btn.style.setProperty('--mx', `${dx * 0.22}px`);
            btn.style.setProperty('--my', `${dy * 0.3}px`);
        });
        btn.addEventListener('pointerleave', () => {
            btn.style.setProperty('--mx', '0px');
            btn.style.setProperty('--my', '0px');
        });
    });
}

/* ── Contadores animados (bloque de estadísticas) ──────────────────────── */
function initCounters() {
    const numRe = /^(\D*)(\d[\d,.]*)(\D*)$/;
    document.querySelectorAll('main section .font-\\[\\\'Anton\\\'\\].text-5xl, main [data-count]').forEach((el) => {
        if (el.dataset.counted) return;
        const m = el.textContent.trim().match(numRe);
        if (!m) return;
        el.dataset.counted = '1';
        if (reduced) return;
        const [, pre, num, post] = m;
        const target = parseFloat(num.replace(/,/g, ''));
        const decimals = (num.split('.')[1] || '').length;
        const useCommas = num.includes(',');
        const io = new IntersectionObserver(([e]) => {
            if (!e.isIntersecting) return;
            io.disconnect();
            const t0 = performance.now();
            const dur = 1600;
            const tick = (t) => {
                const p = Math.min(1, (t - t0) / dur);
                const v = target * (1 - Math.pow(1 - p, 4));
                let s = v.toFixed(decimals);
                if (useCommas) s = Number(s).toLocaleString('en-US', { minimumFractionDigits: decimals });
                el.textContent = pre + s + post;
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        }, { threshold: 0.6 });
        io.observe(el);
    });
}

/* ── Spotlight que sigue al cursor + barra de progreso ─────────────────── */
function initChrome() {
    if (document.querySelector('.scroll-progress')) return;
    const bar = document.createElement('div');
    bar.className = 'scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.appendChild(bar);

    if (reduced || !finePointer) return;
    const glow = document.createElement('div');
    glow.className = 'cursor-glow';
    glow.setAttribute('aria-hidden', 'true');
    document.body.appendChild(glow);
    window.addEventListener('pointermove', (e) => {
        glow.style.transform = `translate3d(${e.clientX}px, ${e.clientY}px, 0)`;
        glow.style.opacity = '1';
    }, { passive: true });
    document.addEventListener('pointerleave', () => { glow.style.opacity = '0'; });
}

function init() {
    initHeroText();
    initReveal();
    initTilt();
    initMagnetic();
    initCounters();
    initChrome();
    onScroll();
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();

window.addEventListener('scroll', onScroll, { passive: true });
window.addEventListener('resize', onScroll, { passive: true });
document.addEventListener('livewire:navigated', init);

// Livewire/Alpine re-renderizan fragmentos: volver a enganchar lo nuevo.
let debounce;
new MutationObserver(() => {
    clearTimeout(debounce);
    debounce = setTimeout(init, 120);
}).observe(document.body, { childList: true, subtree: true });
