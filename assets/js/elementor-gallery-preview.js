(function ($) {
	'use strict';

	var config = window.crecewebLumenElementorGalleryPreview || {};
	var themeBorderColor = typeof config.borderColor === 'string' && config.borderColor.trim()
		? config.borderColor.trim()
		: '#cbd5e1';

	function isCanvasDocument() {
		return document.body && document.body.classList.contains('elementor-template-canvas');
	}

	function getConfigSettings($scope) {
		if (!window.elementorFrontend || !elementorFrontend.config || !elementorFrontend.config.elements) {
			return null;
		}

		var cid = $scope.attr('data-model-cid');
		var data = cid && elementorFrontend.config.elements.data
			? elementorFrontend.config.elements.data[cid]
			: null;

		return data && data.attributes ? data.attributes : null;
	}

	function getViewSettings(view) {
		if (!view || !view.model || typeof view.model.get !== 'function') {
			return null;
		}

		var settingsModel = view.model.get('settings');
		return settingsModel && settingsModel.attributes ? settingsModel.attributes : null;
	}

	function hasExplicitBorderColor(settings) {
		if (!settings) {
			return false;
		}

		if (typeof settings.image_border_color === 'string' && settings.image_border_color.trim()) {
			return true;
		}

		var globals = settings.__globals__;
		return !!(
			globals &&
			typeof globals.image_border_color === 'string' &&
			globals.image_border_color.trim()
		);
	}

	function stopObserver($scope) {
		var element = $scope && $scope[0];
		if (element && element.crecewebLumenGalleryObserver) {
			element.crecewebLumenGalleryObserver.disconnect();
			element.crecewebLumenGalleryObserver = null;
		}
	}

	function clearThemeFallback($scope) {
		stopObserver($scope);
		$scope.removeAttr('data-cw-gallery-theme-border');
		$scope.find('.elementor-gallery__container').each(function () {
			this.style.removeProperty('--image-border-color');
		});
		$scope.find('.elementor-gallery-item').each(function () {
			this.style.removeProperty('border-color');
		});
	}

	function ensureObserver($scope) {
		var element = $scope && $scope[0];
		if (!element || element.crecewebLumenGalleryObserver || typeof MutationObserver === 'undefined') {
			return;
		}

		element.crecewebLumenGalleryObserver = new MutationObserver(function () {
			window.requestAnimationFrame(function () {
				var settings = getConfigSettings($scope);
				if (settings) {
					applyThemeFallback($scope, settings);
				}
			});
		});
		element.crecewebLumenGalleryObserver.observe(element, {
			childList: true,
			subtree: true
		});
	}

	function paintThemeBorder($scope) {
		$scope.attr('data-cw-gallery-theme-border', '1');

		/*
		 * Pro Gallery may scope --image-border-color below the widget wrapper in
		 * the live editor. Set the fallback on the actual Gallery container and
		 * items. This intentionally affects color only; Elementor still owns width,
		 * style and radius.
		 */
		$scope.find('.elementor-gallery__container').each(function () {
			this.style.setProperty('--image-border-color', themeBorderColor);
		});
		$scope.find('.elementor-gallery-item').each(function () {
			this.style.setProperty('border-color', themeBorderColor);
		});
	}

	function applyThemeFallback($scope, settings) {
		if (!$scope || !$scope.length || !$scope.hasClass('elementor-widget-gallery') || isCanvasDocument()) {
			return;
		}

		if (!settings) {
			return;
		}

		if (hasExplicitBorderColor(settings)) {
			clearThemeFallback($scope);
			return;
		}

		paintThemeBorder($scope);
		ensureObserver($scope);
	}

	function applyFromConfig($scope) {
		var settings = getConfigSettings($scope);
		if (settings) {
			applyThemeFallback($scope, settings);
		}
	}

	function bindEditorChanges() {
		if (!window.elementor || !elementor.channels || !elementor.channels.editor) {
			return;
		}

		elementor.channels.editor.on('change:gallery', function (controlView, elementView) {
			if (!elementView || !elementView.model || typeof elementView.model.get !== 'function') {
				return;
			}

			if ('gallery' !== elementView.model.get('widgetType')) {
				return;
			}

			window.requestAnimationFrame(function () {
				applyThemeFallback(elementView.$el, getViewSettings(elementView));
			});
		});
	}

	$(window).on('elementor/frontend/init', function () {
		if (!window.elementorFrontend || !elementorFrontend.hooks) {
			return;
		}

		elementorFrontend.hooks.addAction('frontend/element_ready/gallery.default', function ($scope) {
			applyFromConfig($scope);

			/* Gallery markup can be populated just after element_ready in editor. */
			window.requestAnimationFrame(function () {
				applyFromConfig($scope);
			});
			window.setTimeout(function () {
				applyFromConfig($scope);
			}, 80);
		});

		bindEditorChanges();
	});
})(jQuery);
