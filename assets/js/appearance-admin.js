(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.cw-lumen-reset-form').forEach(function (form) {
			form.addEventListener('submit', function (event) {
				var message = form.getAttribute('data-confirm-message') || '';
				if (message && !window.confirm(message)) {
					event.preventDefault();
				}
			});
		});

		if (document.getElementById('creceweb-reset-notice') && window.history && typeof window.URL === 'function') {
			var currentUrl = new window.URL(window.location.href);
			currentUrl.searchParams.delete('creceweb_lumen_reset');
			window.history.replaceState({}, document.title, currentUrl.toString());
		}
	});
}());
