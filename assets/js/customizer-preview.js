/* global wp, crecewebLumenCustomizerPreview */
(function (api) {
	'use strict';

	if (!api) {
		return;
	}

	var root = document.documentElement;
	var body = document.body;
	var config = window.crecewebLumenCustomizerPreview || {};
	var settingPrefix = config.settingPrefix || 'creceweb_lumen_settings[';
	var classMap = {
		spacing_density: 'cw-spacing-density--',
		header_behavior: 'cw-header-behavior--',
		header_tone: 'cw-header-tone--',
		header_density: 'cw-header-density--',
		header_divider: 'cw-header-divider--',
		header_alignment: 'cw-header-alignment--',
		header_sticky_shadow: 'cw-header-sticky-shadow--',
		top_bar_tone: 'cw-top-bar-tone--',
		top_bar_width: 'cw-top-bar-width--',
		top_bar_alignment: 'cw-top-bar-alignment--',
		navigation_align: 'cw-navigation-align--',
		navigation_gap: 'cw-navigation-gap--',
		navigation_transform: 'cw-navigation-transform--',
		mobile_menu_style: 'cw-mobile-menu-style--',
		footer_tone: 'cw-footer-tone--',
		footer_density: 'cw-footer-density--',
		footer_widget_columns: 'cw-footer-widget-columns--',
		blog_surface: 'cw-blog-surface--',
		blog_layout: 'cw-blog-layout--',
		blog_columns: 'cw-blog-columns--',
		blog_image_ratio: 'cw-blog-image-ratio--',
		shape: 'cw-shape--',
		button_style: 'cw-button-style--',
		link_decoration: 'cw-link-decoration--',
		motion_preference: 'cw-motion-preference--',
		hide_site_title: 'cw-hide-site-title--',
		hide_site_tagline: 'cw-hide-site-tagline--',
		hide_footer_widgets_on_mobile: 'cw-hide-footer-widgets-on-mobile--'
	};
	var variableMap = {
		primary_color: ['--cw-color-primary'],
		accent_color: ['--cw-color-accent'],
		accent_strong: ['--cw-color-accent-strong'],
		background_color: ['--cw-color-background'],
		surface_color: ['--cw-color-surface'],
		text_color: ['--cw-color-text'],
		heading_color: ['--cw-color-heading'],
		link_color: ['--cw-color-link'],
		text_muted_color: ['--cw-color-text-muted'],
		border_color: ['--cw-color-border'],
		focus_color: ['--cw-color-focus'],
		navigation_color: ['--cw-navigation-color'],
		navigation_hover_color: ['--cw-navigation-hover-color'],
		navigation_active_color: ['--cw-navigation-active-color'],
		submenu_background_color: ['--cw-submenu-background'],
		submenu_hover_text_color: ['--cw-submenu-hover-text'],
		submenu_hover_background_color: ['--cw-submenu-hover-background'],
		submenu_hover_background_opacity: ['--cw-submenu-hover-background-opacity'],
		button_background_color: ['--cw-button-background'],
		button_hover_color: ['--cw-button-hover'],
		button_text_color: ['--cw-button-text'],
		footer_background_color: ['--cw-footer-background'],
		footer_text_color: ['--cw-footer-text'],
		footer_link_color: ['--cw-footer-link'],
		copyright_background_color: ['--cw-copyright-background'],
		copyright_text_color: ['--cw-copyright-text'],
		form_background_color: ['--cw-form-background'],
		form_border_color: ['--cw-form-border'],
		form_focus_color: ['--cw-form-focus'],
		top_bar_background_color: ['--cw-topbar-background'],
		top_bar_text_color: ['--cw-topbar-text'],
		top_bar_link_color: ['--cw-topbar-link'],
		content_width: ['--cw-layout-content'],
		wide_width: ['--cw-layout-wide'],
		mobile_gutter: ['--cw-mobile-gutter'],
		tablet_gutter: ['--cw-tablet-gutter'],
		mobile_site_title_size: ['--cw-mobile-site-title-size'],
		mobile_site_tagline_size: ['--cw-mobile-site-tagline-size'],
		mobile_navigation_font_size: ['--cw-mobile-navigation-font-size'],
		mobile_footer_columns: ['--cw-mobile-footer-columns'],
		floating_action_background_color: ['--cw-floating-action-background'],
		floating_action_icon_color: ['--cw-floating-action-icon-color'],
		floating_action_size: ['--cw-floating-action-size'],
		top_bar_padding: ['--cw-topbar-padding'],
		header_padding: ['--cw-header-custom-padding'],
		footer_padding: ['--cw-footer-custom-padding'],
		footer_widget_gap: ['--cw-footer-widget-gap'],
		social_icon_size: ['--cw-social-icon-size'],
		social_icon_gap: ['--cw-social-icon-gap'],
		site_title_size: ['--cw-site-title-size'],
		site_tagline_size: ['--cw-site-tagline-size'],
		navigation_font_size: ['--cw-navigation-font-size'],
		heading_weight: ['--cw-heading-weight'],
		navigation_weight: ['--cw-navigation-weight'],
		button_radius: ['--cw-button-radius'],
		button_padding_y: ['--cw-button-padding-y'],
		button_padding_x: ['--cw-button-padding-x'],
		form_radius: ['--cw-form-radius']
	};
	var percentageKeys = { submenu_hover_background_opacity: true };
	var dimensionKeys = {
		content_width: true, wide_width: true, mobile_gutter: true, tablet_gutter: true, mobile_site_title_size: true, mobile_site_tagline_size: true, mobile_navigation_font_size: true, floating_action_size: true, top_bar_padding: true,
		header_padding: true, footer_padding: true, footer_widget_gap: true, social_icon_size: true, social_icon_gap: true, site_title_size: true,
		site_tagline_size: true, navigation_font_size: true, button_radius: true,
		button_padding_y: true, button_padding_x: true, form_radius: true
	};
	var fontStacks = {
		'system-sans': "ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
		'system-serif': "ui-serif, Georgia, Cambria, 'Times New Roman', serif",
		'system-mono': 'ui-monospace, SFMono-Regular, Menlo, Consolas, monospace'
	};
	var fontScales = {
		compact: { body: '0.9375rem', small: '0.8125rem', display: 'clamp(2.25rem, 4.5vw, 4rem)', h1: 'clamp(2rem, 3.6vw, 3.1rem)', h2: 'clamp(1.6rem, 2.7vw, 2.25rem)', h3: 'clamp(1.28rem, 2vw, 1.65rem)', h4: '1.125rem' },
		standard: { body: '1rem', small: '0.875rem', display: 'clamp(2.5rem, 5vw, 4.5rem)', h1: 'clamp(2.25rem, 4vw, 3.5rem)', h2: 'clamp(1.75rem, 3vw, 2.55rem)', h3: 'clamp(1.4rem, 2.2vw, 1.85rem)', h4: '1.2rem' },
		comfortable: { body: '1.0625rem', small: '0.9375rem', display: 'clamp(2.75rem, 5.5vw, 4.75rem)', h1: 'clamp(2.5rem, 4.5vw, 4rem)', h2: 'clamp(1.95rem, 3.35vw, 2.9rem)', h3: 'clamp(1.55rem, 2.45vw, 2.05rem)', h4: '1.3rem' }
	};
	var shapeScales = {
		square: { sm: '0.25rem', md: '0.375rem', lg: '0.5rem', xl: '0.75rem' },
		soft: { sm: '0.5rem', md: '0.75rem', lg: '1rem', xl: '1.5rem' },
		rounded: { sm: '0.75rem', md: '1rem', lg: '1.5rem', xl: '2rem' }
	};

	function setProperties(properties) {
		Object.keys(properties).forEach(function (name) {
			root.style.setProperty(name, properties[name]);
		});
	}

	function normaliseValue(key, value) {
		if (percentageKeys[key]) {
			return String(value).replace(/%$/, '') + '%';
		}
		if (dimensionKeys[key]) {
			return String(value).replace(/px$/, '') + 'px';
		}
		return value;
	}

	function updateClass(key, value) {
		var prefix = classMap[key];
		if (!prefix || !body) {
			return;
		}
		Array.prototype.slice.call(body.classList).forEach(function (className) {
			if (className.indexOf(prefix) === 0) {
				body.classList.remove(className);
			}
		});
		body.classList.add(prefix + value);
	}

	function getSettingValue(key, fallback) {
		var setting = api(settingPrefix + key + ']');
		return setting ? setting.get() : fallback;
	}

	function getDimensionValue(key, fallback) {
		var value = parseInt(String(getSettingValue(key, fallback)).replace(/px$/, ''), 10);
		return isNaN(value) ? fallback : value;
	}

	function updateEffectiveSpacing() {
		var scales = { compact: 0.75, normal: 1, spacious: 1.3, none: 0 };
		var headerDensity = getSettingValue('header_density', 'normal');
		var footerDensity = getSettingValue('footer_density', 'normal');
		var headerPadding = getDimensionValue('header_padding', 24);
		var footerPadding = getDimensionValue('footer_padding', 48);
		setProperties({
			'--cw-header-effective-padding': Math.round(headerPadding * (scales[headerDensity] || 1)) + 'px',
			'--cw-footer-effective-padding': Math.round(footerPadding * (Object.prototype.hasOwnProperty.call(scales, footerDensity) ? scales[footerDensity] : 1)) + 'px'
		});
	}

	function updateDependentWidths() {
		var content = getDimensionValue('content_width', 760) + 'px';
		var wide = getDimensionValue('wide_width', 1120) + 'px';
		var headerChoice = getSettingValue('header_width', 'wide');
		var topBarChoice = getSettingValue('top_bar_width', 'wide');
		var headerWidths = { content: content, wide: wide, max: '1280px', full: '100%' };
		var topBarWidths = { full: '100%', content: content, wide: wide };
		setProperties({
			'--cw-header-width': headerWidths[headerChoice] || wide,
			'--cw-topbar-width': topBarWidths[topBarChoice] || wide
		});
	}

	function bindSettings(keys, callback) {
		keys.forEach(function (key) {
			api(settingPrefix + key + ']', function (setting) {
				setting.bind(callback);
			});
		});
	}

	Object.keys(variableMap).forEach(function (key) {
		api(settingPrefix + key + ']', function (setting) {
			var applyVariable = function (value) {
				var normalised = normaliseValue(key, value);
				variableMap[key].forEach(function (name) {
					if (String(normalised).trim() === '') {
						root.style.removeProperty(name);
						return;
					}
					root.style.setProperty(name, normalised);
				});
			};
			applyVariable(setting.get());
			setting.bind(applyVariable);
		});
	});

	function updateIdentityText(selector, value) {
		Array.prototype.slice.call(document.querySelectorAll(selector)).forEach(function (node) {
			var target = node.querySelector('a') || node;
			target.textContent = value || '';
		});
	}

	function updateIdentityVisibility(selector, value) {
		var hidden = String(value) === '1';
		Array.prototype.slice.call(document.querySelectorAll(selector)).forEach(function (node) {
			node.hidden = hidden;
			node.style.display = hidden ? 'none' : '';
			node.setAttribute('aria-hidden', hidden ? 'true' : 'false');
		});
	}

	api('blogname', function (setting) {
		setting.bind(function (value) {
			updateIdentityText('.cw-site-header .wp-block-site-title, .cw-site-footer .wp-block-site-title', value);
		});
	});
	api('blogdescription', function (setting) {
		setting.bind(function (value) {
			Array.prototype.slice.call(document.querySelectorAll('.cw-site-header .wp-block-site-tagline, .cw-site-footer .wp-block-site-tagline')).forEach(function (node) {
				node.textContent = value || '';
			});
		});
	});

	api(settingPrefix + 'hide_site_title]', function (setting) {
		updateIdentityVisibility('.cw-site-header .wp-block-site-title, .cw-site-footer .wp-block-site-title', setting.get());
		setting.bind(function (value) {
			updateIdentityVisibility('.cw-site-header .wp-block-site-title, .cw-site-footer .wp-block-site-title', value);
		});
	});
	api(settingPrefix + 'hide_site_tagline]', function (setting) {
		updateIdentityVisibility('.cw-site-header .wp-block-site-tagline, .cw-site-footer .wp-block-site-tagline', setting.get());
		setting.bind(function (value) {
			updateIdentityVisibility('.cw-site-header .wp-block-site-tagline, .cw-site-footer .wp-block-site-tagline', value);
		});
	});

	api(settingPrefix + 'logo_width]', function (setting) {
		var applyLogoWidth = function (value) {
			setProperties({ '--cw-logo-width': parseInt(value, 10) + 'px' });
		};
		applyLogoWidth(setting.get());
		setting.bind(applyLogoWidth);
	});

	function updateCompactLogoWidth() {
		var mode = getSettingValue('mobile_logo_width_mode', 'inherit');
		if (mode === 'custom') {
			setProperties({ '--cw-mobile-logo-width': getDimensionValue('mobile_logo_width', 32) + 'px' });
			return;
		}

		setProperties({ '--cw-mobile-logo-width': 'var(--cw-logo-width)' });
	}

	bindSettings(['mobile_logo_width_mode', 'mobile_logo_width'], updateCompactLogoWidth);
	updateCompactLogoWidth();
	api(settingPrefix + 'font_preset]', function (setting) {
		var applyFontPreset = function (value) {
			var stack = fontStacks[value] || fontStacks['system-sans'];
			setProperties({ '--cw-font-sans': stack });
			var headingSetting = api(settingPrefix + 'heading_preset]');
			if (headingSetting && headingSetting.get() === 'inherit') {
				setProperties({ '--cw-font-heading': stack });
			}
		};
		applyFontPreset(setting.get());
		setting.bind(applyFontPreset);
	});
	api(settingPrefix + 'heading_preset]', function (setting) {
		var applyHeadingPreset = function (value) {
			var bodyStack = getComputedStyle(root).getPropertyValue('--cw-font-sans').trim() || fontStacks['system-sans'];
			setProperties({ '--cw-font-heading': value === 'inherit' ? bodyStack : (fontStacks[value] || bodyStack) });
		};
		applyHeadingPreset(setting.get());
		setting.bind(applyHeadingPreset);
	});
	api(settingPrefix + 'font_scale]', function (setting) {
		var applyFontScale = function (value) {
			var scale = fontScales[value] || fontScales.standard;
			setProperties({ '--cw-text-body': scale.body, '--cw-text-small': scale.small, '--cw-text-display': scale.display, '--cw-text-h1': scale.h1, '--cw-text-h2': scale.h2, '--cw-text-h3': scale.h3, '--cw-text-h4': scale.h4 });
		};
		applyFontScale(setting.get());
		setting.bind(applyFontScale);
	});
	api(settingPrefix + 'shape]', function (setting) {
		setting.bind(function (value) {
			var shape = shapeScales[value] || shapeScales.soft;
			setProperties({ '--cw-radius-sm': shape.sm, '--cw-radius-md': shape.md, '--cw-radius-lg': shape.lg, '--cw-radius-xl': shape.xl });
		});
	});

	var alignmentMap = { left: 'flex-start', center: 'center', right: 'flex-end', 'space-between': 'space-between' };
	api(settingPrefix + 'header_alignment]', function (setting) { var apply = function (value) { setProperties({ '--cw-header-alignment': alignmentMap[value] || 'space-between' }); }; apply(setting.get()); setting.bind(apply); });
	api(settingPrefix + 'top_bar_alignment]', function (setting) { var apply = function (value) { setProperties({ '--cw-topbar-alignment': alignmentMap[value] || 'flex-end' }); }; apply(setting.get()); setting.bind(apply); });
	api(settingPrefix + 'navigation_align]', function (setting) { var apply = function (value) { setProperties({ '--cw-navigation-alignment': alignmentMap[value] || 'flex-end' }); }; apply(setting.get()); setting.bind(apply); });
	api(settingPrefix + 'navigation_gap]', function (setting) { var apply = function (value) { var map = { compact: '.75rem', normal: '1.25rem', spacious: '2rem' }; setProperties({ '--cw-navigation-gap': map[value] || map.normal }); }; apply(setting.get()); setting.bind(apply); });
	bindSettings(['header_padding', 'header_density', 'footer_padding', 'footer_density'], updateEffectiveSpacing);
	bindSettings(['content_width', 'wide_width', 'header_width', 'top_bar_width'], updateDependentWidths);

	Object.keys(classMap).forEach(function (key) {
		api(settingPrefix + key + ']', function (setting) {
			updateClass(key, setting.get());
			setting.bind(function (value) {
				updateClass(key, value);
			});
		});
	});
}(wp.customize));
