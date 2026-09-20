/* global MutationObserver, ResizeObserver */
(function () {
	'use strict';

	function setupHeaderBehavior() {
		var header = document.querySelector('.cw-site-header');
		if (!header || !document.body) {
			return;
		}

		var spacer = document.querySelector('.cw-header-sticky-spacer');
		if (!spacer) {
			spacer = document.createElement('div');
			spacer.className = 'cw-header-sticky-spacer';
			spacer.setAttribute('aria-hidden', 'true');
			header.parentNode.insertBefore(spacer, header);
		}

		var rafId = 0;

		function syncHeaderMetrics() {
			/* The optional top bar lives inside .cw-site-header, so this public
			 * metric is the complete visible header unit used by spacers and Heroes. */
			var headerHeight = Math.ceil(header.getBoundingClientRect().height || 0);
			if (headerHeight > 0) {
				document.documentElement.style.setProperty('--cw-lumen-header-height', headerHeight + 'px');
			}
		}

		function stickyModeEnabled() {
			return document.body.classList.contains('cw-header-behavior--sticky');
		}

		function fixedModeEnabled() {
			return document.body.classList.contains('cw-header-behavior--fixed');
		}

		/* A transparent Pro header is fixed from the beginning so it can remain
		 * over the hero. Its state is handled by Pro; Lumen must not toggle the
		 * same class or reserve duplicate space. */
		function proOwnsSticky() {
			return document.body.classList.contains('cw-lumen-pro-controls-sticky');
		}

		function refreshHeaderState() {
			rafId = 0;
			syncHeaderMetrics();
			if (proOwnsSticky()) {
				/* Pro owns both the anchored header and its spacer. Do not reset the
				 * spacer here: a solid Fixed-from-start profile must reserve the
				 * header height, while a transparent Hero overlay intentionally uses
				 * zero. Writing 0px from Theme created a load-order race with Pro. */
				return;
			}

			if (fixedModeEnabled()) {
				spacer.style.height = Math.ceil(header.getBoundingClientRect().height) + 'px';
				header.classList.add('cw-header-is-fixed');
				return;
			}

			if (!stickyModeEnabled()) {
				header.classList.remove('cw-header-is-fixed');
				spacer.style.height = '0px';
				return;
			}

			var isFixed = header.classList.contains('cw-header-is-fixed');
			var headerHeight = Math.ceil(header.getBoundingClientRect().height);
			var trigger = isFixed
				? spacer.getBoundingClientRect().top + window.scrollY
				: header.getBoundingClientRect().top + window.scrollY;
			var shouldFix = window.scrollY > trigger;

			if (shouldFix) {
				spacer.style.height = headerHeight + 'px';
				header.classList.add('cw-header-is-fixed');
			} else {
				header.classList.remove('cw-header-is-fixed');
				spacer.style.height = '0px';
			}
		}

		function scheduleRefresh() {
			if (!rafId) {
				rafId = window.requestAnimationFrame(refreshHeaderState);
			}
		}

		window.addEventListener('scroll', scheduleRefresh, { passive: true });
		window.addEventListener('resize', scheduleRefresh);
		window.addEventListener('load', scheduleRefresh);

		if (window.MutationObserver) {
			new MutationObserver(scheduleRefresh).observe(document.body, {
				attributes: true,
				attributeFilter: ['class']
			});
		}

		/* Logo loading, navigation wrapping, Customizer changes and top-bar
		 * widgets can change the anchored unit without a window resize. */
		if (window.ResizeObserver) {
			new ResizeObserver(scheduleRefresh).observe(header);
		}

		scheduleRefresh();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', setupHeaderBehavior);
	} else {
		setupHeaderBehavior();
	}
}());
