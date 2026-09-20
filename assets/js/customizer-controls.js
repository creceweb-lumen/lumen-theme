/* global wp, jQuery */
(function (api, $) {
	'use strict';

	if (!api || !$) {
		return;
	}

	var initialConfig = window.crecewebCustomizerConfig || {};
	var initialPrefix = initialConfig.settingPrefix || 'creceweb_lumen_settings[';

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

		function isDetailedMode() {
			var detailedSetting = api(initialPrefix + 'show_advanced_controls]');
			return !!detailedSetting && String(detailedSetting.get()) === '1';
		}

		function syncMobileLogoWidthControl(value) {
			var widthControl = api.control(initialPrefix + 'mobile_logo_width]');
			if (widthControl && widthControl.container) {
				widthControl.container.toggle(isDetailedMode() && String(value) === 'custom');
			}
		}

		control.setting.bind(syncMobileLogoWidthControl);
		var detailedSetting = api(initialPrefix + 'show_advanced_controls]');
		if (detailedSetting) {
			detailedSetting.bind(function () { syncMobileLogoWidthControl(control.setting.get()); });
		}
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
	 * WordPress can open the Customizer from update.php?action=upload-theme.
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
		var unsafeReturn = /(?:^|\/)update\.php(?:\?|$)/.test(returnTarget) && /(?:\?|&)action=upload-theme(?:&|$)/.test(returnTarget);

		$(selectors).each(function () {
			if (this.tagName && this.tagName.toLowerCase() === 'button') {
				this.type = 'button';
			}
			this.setAttribute('formnovalidate', 'formnovalidate');

			if (unsafeReturn && safeReturn && this.tagName && this.tagName.toLowerCase() === 'a') {
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

/* CreceWeb Lumen — guided presets and detailed controls. */
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
	var advancedControls = [
		'wide_width', 'button_radius', 'button_padding_y', 'button_padding_x', 'form_radius', 'link_decoration',
		'header_width', 'header_sticky_shadow', 'header_divider', 'navigation_gap', 'navigation_weight', 'navigation_transform',
		'top_bar_tone', 'top_bar_width', 'top_bar_alignment', 'top_bar_padding', 'top_bar_background_color', 'top_bar_text_color', 'top_bar_link_color',
		'blog_surface', 'footer_padding', 'footer_widget_gap',
		'mobile_logo_width_mode', 'mobile_logo_width', 'mobile_site_title_size', 'mobile_site_tagline_size', 'mobile_navigation_font_size',
		'focus_color', 'motion_preference'
	];
	var detailedMode = false;

	function setting(key) {
		return api(prefix + key + ']');
	}

	function toggleControl(key, visible) {
		var control = api.control(prefix + key + ']');
		if (control && control.container) {
			control.container.toggle(!!visible);
		}
	}


	function updateAdvanced(value) {
		detailedMode = value === '1';
		advancedControls.forEach(function (key) {
			toggleControl(key, detailedMode);
		});

		var topBar = setting('top_bar_enabled');
		if (topBar) {
			updateTopBar(topBar.get());
		}
		var tone = setting('top_bar_tone');
		if (tone) {
			updateTopBarColors(detailedMode && (!topBar || topBar.get() === '1') && tone.get() === 'custom');
		}
		var headerBehavior = setting('header_behavior');
		if (headerBehavior) {
			updateSticky(headerBehavior.get());
		}
		var mobileLogoMode = setting('mobile_logo_width_mode');
		if (mobileLogoMode) {
			toggleControl('mobile_logo_width', detailedMode && mobileLogoMode.get() === 'custom');
		}
	}

	function updateTopBar(value) {
		var enabled = detailedMode && value === '1';
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
		toggleControl('header_sticky_shadow', detailedMode && (value === 'sticky' || value === 'fixed'));
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
		var advanced = setting('show_advanced_controls');
		if (advanced) {
			updateAdvanced(advanced.get());
			advanced.bind(updateAdvanced);
		}
		var topBar = setting('top_bar_enabled');
		if (topBar) {
			updateTopBar(topBar.get());
			topBar.bind(updateTopBar);
		}
		var tone = setting('top_bar_tone');
		if (tone) {
			updateTopBarColors(detailedMode && (!topBar || topBar.get() === '1') && tone.get() === 'custom');
			tone.bind(function (value) {
				updateTopBarColors(detailedMode && (!topBar || topBar.get() === '1') && value === 'custom');
			});
		}
		var mobileLogoMode = setting('mobile_logo_width_mode');
		if (mobileLogoMode) {
			toggleControl('mobile_logo_width', detailedMode && mobileLogoMode.get() === 'custom');
			mobileLogoMode.bind(function (value) {
				toggleControl('mobile_logo_width', detailedMode && value === 'custom');
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
