(function () {
	var toggle = document.querySelector('.nav-toggle');
	var nav = document.getElementById('menu-principal');
	if (!toggle || !nav) return;

	function close() {
		toggle.setAttribute('aria-expanded', 'false');
		document.body.classList.remove('nav-open');
	}

	toggle.addEventListener('click', function () {
		var open = toggle.getAttribute('aria-expanded') === 'true';
		var bottom = document.querySelector('.site-header').getBoundingClientRect().bottom;
		document.documentElement.style.setProperty('--nav-top', Math.round(bottom) + 'px');
		toggle.setAttribute('aria-expanded', String(!open));
		document.body.classList.toggle('nav-open', !open);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') close();
	});

	window.matchMedia('(min-width: 1024px)').addEventListener('change', function (mq) {
		if (mq.matches) close();
	});

	// Sous-menus : ouverture au clic sur mobile.
	nav.querySelectorAll('.menu-item-has-children > a').forEach(function (link) {
		var btn = document.createElement('button');
		btn.className = 'submenu-toggle';
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-label', 'Ouvrir le sous-menu ' + link.textContent.trim());
		btn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
		link.after(btn);
		btn.addEventListener('click', function () {
			var li = btn.parentElement;
			var open = li.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', String(open));
		});
	});

	// En-tête compact au défilement.
	var header = document.querySelector('.site-header');
	var onScroll = function () {
		header.classList.toggle('is-scrolled', window.scrollY > 40);
	};
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
})();
