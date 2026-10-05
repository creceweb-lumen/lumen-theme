/* global wp, jQuery */
(function (api, $) {
	'use strict';

	if (!api || !$) {
		return;
	}

	var initialConfig = window.crecewebCustomizerConfig || {};
	var initialPrefix = initialConfig.settingPrefix || 'creceweb_lumen_settings[';

	if (api.Section && api.sectionConstructor) {
		api.sectionConstructor['creceweb-external-link'] = api.Section.extend({
			attachEvents: function () {},
			isContextuallyActive: function () {
				return true;
			}
		});
	}

	function numberFrom(value, fallback) {
		var parsed = parseFloat(value);
		return isNaN(parsed) ? fallback : parsed;
	}

	function normalise($range, value) {
		var min = numberFrom($range.attr('min'), 0);
		var max = numberFrom($range.attr('max'), 100);
		var step = numberFrom($range.attr('step'), 1);
		var numeric = Math.min(max, Math.max(min, numberFrom(value, min)));
		if (step > 0) {
			numeric = Math.round((numeric - min) / step) * step + min;
		}
		return String(Number(numeric.toFixed(4)));
	}

	function syncField($control, value) {
		var $range = $control.find('.cw-range-control__range, .cw-logo-width-control__range').first();
		var $number = $control.find('.cw-range-control__number').first();
		if (!$range.length) {
			return;
		}
		var clean = normalise($range, value);
		$range.val(clean);
		if ($number.length) {
			$number.val(clean);
		}
		$control.find('.cw-range-control__value span, .cw-logo-width-control__value span').text(clean);
	}

	function getSettingFromRange($range) {
		var settingId = $range.attr('data-customize-setting-link');
		return settingId ? api(settingId) : null;
	}

	function bindRangeControl(control) {
		var $container = control.container;
		if (!$container || !$container.length || $container.data('cwRangeBound')) {
			return;
		}
		var $range = $container.find('.cw-range-control__range, .cw-logo-width-control__range').first();
		if (!$range.length) {
			return;
		}
		$container.data('cwRangeBound', true);

		$container.on('input change', '.cw-range-control__range, .cw-logo-width-control__range', function () {
			syncField($container, this.value);
		});

		$container.on('change blur', '.cw-range-control__number', function () {
			var value = normalise($range, this.value);
			syncField($container, value);
			var setting = getSettingFromRange($range);
			if (setting) {
				setting.set(value);
			}
		});

		control.setting.bind(function (value) {
			syncField($container, value);
		});
		syncField($container, control.setting.get());
	}

	function bindBooleanCheckboxControl(control) {
		var $container = control.container;
		if (!$container || !$container.length || $container.data('cwBooleanBound')) {
			return;
		}

		var $input = $container.find('.cw-boolean-checkbox__input').first();
		if (!$input.length) {
			return;
		}

		$container.data('cwBooleanBound', true);

		function syncBoolean(value) {
			var enabled = String(value) === '1';
			$input.prop('checked', enabled).attr('aria-checked', enabled ? 'true' : 'false');
		}

		$container.on('change', '.cw-boolean-checkbox__input', function () {
			control.setting.set(this.checked ? '1' : '0');
		});

		control.setting.bind(syncBoolean);
		syncBoolean(control.setting.get());
	}

	function bindEmptyColorControl(control) {
		var $container = control.container;
		if (!$container || !$container.length || $container.data('cwEmptyColorBound')) {
			return;
		}

		var $input = $container.find('.wp-color-picker').first();
		if (!$input.length) {
			return;
		}

		$container.data('cwEmptyColorBound', true);

		function syncEmptyColor(value) {
			var isEmpty = String(value || '').trim() === '';
			$container.toggleClass('cw-color-control--empty', isEmpty);
		}

		$container.on('input change colorchange', '.wp-color-picker', function () {
			syncEmptyColor(this.value);
		});

		control.setting.bind(syncEmptyColor);
		syncEmptyColor(control.setting.get());
	}

	function bindMobileLogoWidthMode(control) {
		if (!control || control.id !== initialPrefix + 'mobile_logo_width_mode]') {
			return;
		}

		function syncMobileLogoWidthControl(value) {
			var widthControl = api.control(initialPrefix + 'mobile_logo_width]');
			if (widthControl && widthControl.container) {
				widthControl.container.toggle(String(value) === 'custom');
			}
		}

		control.setting.bind(syncMobileLogoWidthControl);
		syncMobileLogoWidthControl(control.setting.get());
	}

	function bindChoiceCardsControl(control) {
		var $container = control.container;
		if (!$container || !$container.length || $container.data('cwChoiceCardsBound')) {
			return;
		}

		var $inputs = $container.find('.cw-choice-card input[type="radio"]');
		if (!$inputs.length) {
			return;
		}

		$container.data('cwChoiceCardsBound', true);
		var groupName = 'cw-choice-' + String(control.id || 'setting').replace(/[^a-zA-Z0-9_-]/g, '-');
		$inputs.attr('name', groupName);

		function syncChoiceCards(value) {
			var selected = String(value);
			$inputs.each(function () {
				var isSelected = String(this.value) === selected;
				this.checked = isSelected;
				$(this).closest('.cw-choice-card').attr('aria-checked', isSelected ? 'true' : 'false');
			});
		}

		$container.on('change', '.cw-choice-card input[type="radio"]', function () {
			if (!this.checked) {
				return;
			}
			control.setting.set(String(this.value));
		});

		control.setting.bind(syncChoiceCards);
		syncChoiceCards(control.setting.get());
	}

	function bindAllCustomControls() {
		api.control.each(function (control) {
			bindRangeControl(control);
			bindBooleanCheckboxControl(control);
			bindEmptyColorControl(control);
			bindMobileLogoWidthMode(control);
			bindChoiceCardsControl(control);
		});
	}

	/**
	 * Returns whether a URL points back to a theme/plugin upload endpoint.
	 *
	 * @param {string} value Candidate URL.
	 * @return {boolean} Whether the target is unsafe for Customizer close.
	 */
	function isUnsafeCustomizerReturnUrl(value) {
		var decoded = String(value || '').trim();
		var attempt;

		if (!decoded) {
			return false;
		}

		for (attempt = 0; attempt < 3; attempt += 1) {
			try {
				var next = decodeURIComponent(decoded);
				if (next === decoded) {
					break;
				}
				decoded = next;
			} catch (error) {
				break;
			}
		}

		try {
			var parsed = new URL(decoded, window.location.origin);
			return /(?:^|\/)update\.php$/.test(parsed.pathname) && ['upload-theme', 'upload-plugin'].indexOf(parsed.searchParams.get('action')) !== -1;
		} catch (error) {
			return false;
		}
	}

	/**
	 * WordPress can open the Customizer from a one-time theme/plugin upload endpoint.
	 * Returning to that upload endpoint is unsafe because its original form has
	 * a required file input. Keep native close actions non-submitting and point
	 * them to the Themes screen when that return target persists.
	 */
	function protectCustomizerCloseActions() {
		var selectors = [
			'#customize-controls-close',
			'.customize-controls-close',
			'.customize-controls-close-button',
			'[data-customize-close]'
		].join(',');
		var customizerConfig = window.crecewebCustomizerConfig || {};
		var safeReturn = customizerConfig.safeCustomizerReturnUrl || '';
		var query = new URLSearchParams(window.location.search || '');
		var returnTarget = query.get('return') || '';
		var settingsReturn = window._wpCustomizeSettings && window._wpCustomizeSettings.url
			? window._wpCustomizeSettings.url.return || ''
			: '';

		if (safeReturn && isUnsafeCustomizerReturnUrl(settingsReturn)) {
			window._wpCustomizeSettings.url.return = safeReturn;
		}

		$(selectors).each(function () {
			var tagName = this.tagName ? this.tagName.toLowerCase() : '';
			var href = tagName === 'a' ? (this.getAttribute('href') || this.href || '') : '';

			if (tagName === 'button') {
				this.type = 'button';
			}
			this.setAttribute('formnovalidate', 'formnovalidate');

			if (safeReturn && tagName === 'a' && (
				isUnsafeCustomizerReturnUrl(href) ||
				isUnsafeCustomizerReturnUrl(returnTarget) ||
				isUnsafeCustomizerReturnUrl(settingsReturn)
			)) {
				this.setAttribute('href', safeReturn);
			}
		});
	}

	// Run both immediately and once Customizer controls are fully ready.
	// The latter covers close elements added by different WordPress versions.
	api.bind('ready', function () {
		bindAllCustomControls();
		protectCustomizerCloseActions();
	});
	bindAllCustomControls();
	protectCustomizerCloseActions();
}(wp.customize, jQuery));

/* CreceWeb Lumen — guided presets and contextual controls. */
(function (api, $) {
	'use strict';

	if (!api || !$) {
		return;
	}

	var config = window.crecewebCustomizerConfig || {};
	var prefix = config.settingPrefix || 'creceweb_lumen_settings[';
	var strings = config.strings || {};
	var presets = config.presets || {};
	var applyingPreset = false;

	function setting(key) {
		return api(prefix + key + ']');
	}

	function toggleControl(key, visible) {
		var control = api.control(prefix + key + ']');
		if (control && control.container) {
			control.container.toggle(!!visible);
		}
	}

	function updateTopBar(value) {
		var enabled = value === '1';
		[
			'top_bar_tone',
			'top_bar_width',
			'top_bar_alignment',
			'top_bar_padding'
		].forEach(function (key) {
			toggleControl(key, enabled);
		});
		var tone = setting('top_bar_tone');
		updateTopBarColors(enabled && tone && tone.get() === 'custom');
	}

	function updateTopBarColors(show) {
		[
			'top_bar_background_color',
			'top_bar_text_color',
			'top_bar_link_color'
		].forEach(function (key) {
			toggleControl(key, show);
		});
	}

	function updateBlogLayout(value) {
		var isGrid = value === 'grid';
		toggleControl('blog_columns', isGrid);
		// Composition belongs only to the list. Grid cards stay vertical so
		// every column keeps a predictable height and visual hierarchy.
		toggleControl('blog_card_layout', !isGrid);
	}

	function updateFloatingAction(value) {
		var active = value !== 'none';
		[
			'floating_action_background_color',
			'floating_action_icon_color',
			'floating_action_size'
		].forEach(function (key) {
			toggleControl(key, active);
		});
	}

	function updateSticky(value) {
		toggleControl('header_sticky_shadow', value === 'sticky' || value === 'fixed');
	}

	function refreshPresetCards(value) {
		var hasSelection = false;
		$('.cw-design-presets__card').each(function () {
			var selected = $(this).data('cwDesignPreset') === value;
			hasSelection = hasSelection || selected;
			$(this).attr('aria-pressed', selected ? 'true' : 'false');
		});
		$('.cw-design-presets__status').text(hasSelection ? '' : (strings.customConfiguration || ''));
	}

	function bindProgressiveDisclosure() {
		var topBar = setting('top_bar_enabled');
		if (topBar) {
			updateTopBar(topBar.get());
			topBar.bind(updateTopBar);
		}
		var tone = setting('top_bar_tone');
		if (tone) {
			updateTopBarColors((!topBar || topBar.get() === '1') && tone.get() === 'custom');
			tone.bind(function (value) {
				updateTopBarColors((!topBar || topBar.get() === '1') && value === 'custom');
			});
		}
		var mobileLogoMode = setting('mobile_logo_width_mode');
		if (mobileLogoMode) {
			toggleControl('mobile_logo_width', String(mobileLogoMode.get()) === 'custom');
			mobileLogoMode.bind(function (value) {
				toggleControl('mobile_logo_width', String(value) === 'custom');
			});
		}

		var blogLayout = setting('blog_layout');
		if (blogLayout) {
			updateBlogLayout(blogLayout.get());
			blogLayout.bind(updateBlogLayout);
		}
		var headerBehavior = setting('header_behavior');
		if (headerBehavior) {
			updateSticky(headerBehavior.get());
			headerBehavior.bind(updateSticky);
		}
		var floatingAction = setting('floating_action');
		if (floatingAction) {
			updateFloatingAction(floatingAction.get());
			floatingAction.bind(updateFloatingAction);
		}
	}

	function bindPresets() {
		var presetSetting = setting('design_preset');
		if (!presetSetting) {
			return;
		}

		refreshPresetCards(presetSetting.get());
		presetSetting.bind(refreshPresetCards);

		$(document).on('click', '.cw-design-presets__card', function (event) {
			event.preventDefault();
			var presetId = $(this).data('cwDesignPreset');
			var preset = presets[presetId];
			if (!preset || !preset.values) {
				return;
			}
			if (presetSetting.get() !== presetId && !window.confirm(strings.confirmPreset || '')) {
				return;
			}
			applyingPreset = true;
			Object.keys(preset.values).forEach(function (key) {
				var target = setting(key);
				if (target) {
					target.set(String(preset.values[key]));
				}
			});
			presetSetting.set(presetId);
			window.setTimeout(function () {
				applyingPreset = false;
			}, 0);
		});

		var tracked = {};
		Object.keys(presets).forEach(function (presetId) {
			var values = presets[presetId] && presets[presetId].values ? presets[presetId].values : {};
			Object.keys(values).forEach(function (key) {
				tracked[key] = true;
			});
		});
		Object.keys(tracked).forEach(function (key) {
			var target = setting(key);
			if (target) {
				target.bind(function () {
					if (!applyingPreset && presetSetting.get() !== 'custom') {
						presetSetting.set('custom');
					}
				});
			}
		});
	}

	api.bind('ready', function () {
		bindProgressiveDisclosure();
		bindPresets();
	});
}(wp.customize, jQuery));

/* CreceWeb Lumen — opt-in Google Fonts inside the unified family selectors. */
(function (api, $) {
	'use strict';

	if (!api || !$) {
		return;
	}

	var storageKey = 'crecewebLumenShowGoogleFonts';
	var config = window.crecewebCustomizerConfig || {};
	var strings = config.strings || {};

	function familyFromPreset(value) {
		var preset = String(value || '');
		if (preset.indexOf('google:') === 0) {
			return preset.slice(7).trim();
		}
		var legacy = {
			'google-roboto': 'Roboto',
			'google-open-sans': 'Open Sans',
			'google-lato': 'Lato',
			'google-montserrat': 'Montserrat',
			'google-poppins': 'Poppins',
			'google-nunito-sans': 'Nunito Sans',
			'google-source-sans-3': 'Source Sans 3',
			'google-raleway': 'Raleway',
			'google-merriweather': 'Merriweather',
			'google-playfair-display': 'Playfair Display'
		};
		return legacy[preset] || '';
	}

	function presetForFamily(family) {
		return 'google:' + String(family || '').trim();
	}

	function getStoredVisibleState() {
		try {
			return window.localStorage && window.localStorage.getItem(storageKey) === '1';
		} catch (e) {
			return false;
		}
	}

	function storeVisibleState(visible) {
		try {
			if (window.localStorage) {
				window.localStorage.setItem(storageKey, visible ? '1' : '0');
			}
		} catch (e) {
			// The catalog still works when browser storage is unavailable.
		}
	}

	function bindGoogleCatalog(control) {
		var $container = control && control.container;
		if (!$container || !$container.length || $container.data('cwGoogleFontsBound')) {
			return;
		}

		var $root = $container.find('.cw-google-fonts-toggle').first();
		if (!$root.length) {
			return;
		}
		$container.data('cwGoogleFontsBound', true);

		var catalogUrl = $root.attr('data-cw-google-fonts-catalog') || '';
		var bodySettingId = $root.attr('data-body-setting') || '';
		var headingSettingId = $root.attr('data-heading-setting') || '';
		var bodySetting = bodySettingId ? api(bodySettingId) : null;
		var headingSetting = headingSettingId ? api(headingSettingId) : null;
		var $toggle = $root.find('.cw-google-fonts-toggle__input').first();
		var $status = $root.find('.cw-google-fonts-toggle__status').first();
		var fonts = [];
		var loaded = false;
		var loading = false;

		function familyExists(family) {
			var target = String(family || '').toLocaleLowerCase();
			return fonts.some(function (font) {
				return String(font.family || '').toLocaleLowerCase() === target;
			});
		}

		function updateSelect(controlId, setting, showCatalog) {
			var fontControl = api.control(controlId);
			if (!fontControl || !fontControl.container || !setting) {
				return;
			}

			var $select = fontControl.container.find('[data-cw-font-family-select]').first();
			if (!$select.length) {
				return;
			}

			var current = String(setting.get() || '');
			var currentFamily = familyFromPreset(current);
			$select.find('[data-cw-google-current-group], [data-cw-google-catalog-group]').remove();

			if (showCatalog && loaded) {
				var $catalogGroup = $('<optgroup>', { label: 'Google Fonts', 'data-cw-google-catalog-group': '1' });
				if (currentFamily && !familyExists(currentFamily)) {
					$catalogGroup.append($('<option>', { value: current }).text(currentFamily));
				}
				fonts.forEach(function (font) {
					var family = String(font.family || '').trim();
					if (family) {
						$catalogGroup.append($('<option>', { value: presetForFamily(family) }).text(family));
					}
				});
				$select.append($catalogGroup);
			} else if (currentFamily) {
				var $currentGroup = $('<optgroup>', { label: 'Google Fonts', 'data-cw-google-current-group': '1' });
				$currentGroup.append($('<option>', { value: current }).text(currentFamily + ' · ' + (strings.googleInUse || 'en uso')));
				$select.append($currentGroup);
			}

			$select.val(current);
		}

		function syncSelects() {
			var showCatalog = $toggle.prop('checked') && loaded;
			updateSelect('creceweb_local_font_body', bodySetting, showCatalog);
			updateSelect('creceweb_local_font_heading', headingSetting, showCatalog);
		}

		function loadCatalog() {
			if (loaded) {
				syncSelects();
				return;
			}
			if (loading || !catalogUrl) {
				return;
			}

			loading = true;
			$status.text(strings.googleLoading || 'Cargando Google Fonts…');
			fetch(catalogUrl, { credentials: 'same-origin', cache: 'force-cache' })
				.then(function (response) {
					if (!response.ok) {
						throw new Error('catalog');
					}
					return response.json();
				})
				.then(function (data) {
					fonts = data && Array.isArray(data.fonts) ? data.fonts : [];
					loaded = true;
					loading = false;
					$status.text('');
					syncSelects();
				})
				.catch(function () {
					loading = false;
					$toggle.prop('checked', false);
					storeVisibleState(false);
					$status.text(strings.googleLoadError || 'No pudimos mostrar Google Fonts. Volvé a intentarlo.');
					syncSelects();
				});
		}

		$toggle.on('change', function () {
			var visible = $toggle.prop('checked');
			storeVisibleState(visible);
			if (visible) {
				loadCatalog();
			} else {
				$status.text('');
				syncSelects();
			}
		});

		if (bodySetting) {
			bodySetting.bind(syncSelects);
		}
		if (headingSetting) {
			headingSetting.bind(syncSelects);
		}

		$toggle.prop('checked', getStoredVisibleState());
		if ($toggle.prop('checked')) {
			loadCatalog();
		} else {
			syncSelects();
		}
	}

	function bindCatalogs() {
		api.control.each(function (control) {
			if (control && control.params && control.params.type === 'creceweb-google-font-catalog') {
				bindGoogleCatalog(control);
			}
		});
	}

	api.bind('ready', bindCatalogs);
	api.control.bind('add', function (control) {
		if (control && control.params && control.params.type === 'creceweb-google-font-catalog') {
			bindGoogleCatalog(control);
		}
	});
}(wp.customize, jQuery));
