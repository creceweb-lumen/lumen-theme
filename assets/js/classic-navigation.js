/* global crecewebLumenNavigation */
(function () {
	'use strict';

	var strings = window.crecewebLumenNavigation || {};

	var openNavigation = null;
	var savedBodyOverflow = '';
	var savedBodyPaddingRight = '';
	var submenuCounter = 0;

	function getBreakpoint() {
		var body = document.body;

		if (body.classList.contains('cw-mobile-menu-breakpoint--1024')) {
			return 1024;
		}

		if (body.classList.contains('cw-mobile-menu-breakpoint--960')) {
			return 960;
		}

		return 781;
	}

	function isMobileLayout() {
		return window.matchMedia('(max-width: ' + getBreakpoint() + 'px)').matches;
	}

	function getMenuPanel(navigation) {
		return navigation.querySelector('.cw-classic-navigation__menu-container');
	}

	function getToggle(navigation) {
		return navigation.querySelector('.cw-classic-navigation__toggle');
	}

	function getDirectChild(element, selector) {
		var children = element.children;

		for (var index = 0; index < children.length; index += 1) {
			if (children[index].matches(selector)) {
				return children[index];
			}
		}

		return null;
	}

	function getSubmenuElements(item) {
		return {
			button: getDirectChild(item, '.cw-classic-navigation__submenu-toggle'),
			submenu: getDirectChild(item, '.sub-menu')
		};
	}

	function isCompactNavigation(navigation) {
		return isMobileLayout() && navigation.classList.contains('cw-compact-navigation-active');
	}

	function setSubmenuState(navigation, item, isExpanded) {
		var elements = getSubmenuElements(item);

		if (!elements.button || !elements.submenu) {
			return;
		}

		if (!isCompactNavigation(navigation)) {
			item.classList.remove('is-submenu-open');
			elements.button.setAttribute('aria-expanded', 'false');
			elements.submenu.removeAttribute('aria-hidden');
			return;
		}

		item.classList.toggle('is-submenu-open', isExpanded);
		elements.button.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
		elements.submenu.setAttribute('aria-hidden', isExpanded ? 'false' : 'true');
	}

	function closeSubmenuDescendants(navigation, item) {
		item.querySelectorAll('.menu-item-has-children').forEach(function (child) {
			if (child !== item) {
				setSubmenuState(navigation, child, false);
			}
		});
	}

	function closeSiblingSubmenus(navigation, item) {
		var parent = item.parentElement;

		if (!parent) {
			return;
		}

		Array.prototype.slice.call(parent.children).forEach(function (sibling) {
			if (sibling !== item && sibling.classList.contains('menu-item-has-children')) {
				setSubmenuState(navigation, sibling, false);
				closeSubmenuDescendants(navigation, sibling);
			}
		});
	}

	function resetSubmenus(navigation) {
		navigation.querySelectorAll('.menu-item-has-children').forEach(function (item) {
			setSubmenuState(navigation, item, false);
		});
	}

	function initializeSubmenuControls(navigation) {
		navigation.querySelectorAll('.menu-item-has-children').forEach(function (item) {
			var submenu = getDirectChild(item, '.sub-menu');
			var link = getDirectChild(item, 'a');
			var button = getDirectChild(item, '.cw-classic-navigation__submenu-toggle');

			if (!submenu || !link) {
				return;
			}

			if (!submenu.id) {
				submenuCounter += 1;
				submenu.id = 'cw-classic-submenu-' + submenuCounter;
			}

			if (!button) {
				button = document.createElement('button');
				button.type = 'button';
				button.className = 'cw-classic-navigation__submenu-toggle';

				var accessibleLabel = document.createElement('span');
				accessibleLabel.className = 'screen-reader-text';
				var itemLabel = link.textContent.trim() || strings.unnamedItem || '';
				accessibleLabel.textContent = (strings.openSubmenuLabel || '%s').replace('%s', itemLabel);

				var icon = document.createElement('span');
				icon.className = 'cw-classic-navigation__submenu-icon';
				icon.setAttribute('aria-hidden', 'true');
				icon.textContent = '⌄';

				button.appendChild(accessibleLabel);
				button.appendChild(icon);
				link.insertAdjacentElement('afterend', button);
			}

			button.setAttribute('aria-controls', submenu.id);
			button.setAttribute('aria-expanded', 'false');

			button.addEventListener('click', function (event) {
				if (!isCompactNavigation(navigation)) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();

				var shouldOpen = !item.classList.contains('is-submenu-open');
				closeSiblingSubmenus(navigation, item);
				setSubmenuState(navigation, item, shouldOpen);

				if (!shouldOpen) {
					closeSubmenuDescendants(navigation, item);
				}
			});
		});
	}

	function syncSubmenuAccessibility(navigation) {
		var mobile = isCompactNavigation(navigation);
		var navigationIsOpen = navigation.classList.contains('is-open');

		navigation.querySelectorAll('.menu-item-has-children').forEach(function (item) {
			var elements = getSubmenuElements(item);

			if (!elements.button || !elements.submenu) {
				return;
			}

			if (!mobile) {
				item.classList.remove('is-submenu-open');
				elements.button.setAttribute('aria-expanded', 'false');
				elements.submenu.removeAttribute('aria-hidden');
				return;
			}

			var isExpanded = navigationIsOpen && item.classList.contains('is-submenu-open');
			elements.button.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
			elements.submenu.setAttribute('aria-hidden', isExpanded ? 'false' : 'true');
		});
	}

	function updateCompactNavigationState(navigation) {
		var compact = isMobileLayout();
		navigation.classList.toggle('cw-compact-navigation-active', compact);

		if (!compact && navigation.classList.contains('is-open')) {
			closeNavigation(navigation, false);
		}

		return compact;
	}

	function getFocusableElements(container) {
		return Array.prototype.slice.call(
			container.querySelectorAll('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])')
		).filter(function (element) {
			return element.getClientRects().length > 0;
		});
	}

	function lockPageScroll() {
		if (document.documentElement.classList.contains('cw-classic-menu-open')) {
			return;
		}

		savedBodyOverflow = document.body.style.overflow;
		savedBodyPaddingRight = document.body.style.paddingRight;

		var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
		document.documentElement.classList.add('cw-classic-menu-open');
		document.body.classList.add('cw-classic-menu-open');
		document.body.style.overflow = 'hidden';

		if (scrollbarWidth > 0) {
			document.body.style.paddingRight = scrollbarWidth + 'px';
		}
	}

	function unlockPageScroll() {
		document.documentElement.classList.remove('cw-classic-menu-open');
		document.body.classList.remove('cw-classic-menu-open');
		document.body.style.overflow = savedBodyOverflow;
		document.body.style.paddingRight = savedBodyPaddingRight;
	}

	function syncNavigationAccessibility(navigation) {
		var panel = getMenuPanel(navigation);
		var toggle = getToggle(navigation);
		var isOpen = navigation.classList.contains('is-open');

		if (!panel || !toggle) {
			return;
		}

		if (!isMobileLayout()) {
			panel.removeAttribute('aria-hidden');
			panel.removeAttribute('role');
			panel.removeAttribute('aria-modal');
			panel.removeAttribute('aria-label');
			toggle.setAttribute('aria-expanded', 'false');
			syncSubmenuAccessibility(navigation);
			return;
		}

		panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
		toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

		if (isOpen) {
			panel.setAttribute('role', 'dialog');
			panel.setAttribute('aria-modal', 'true');
			panel.setAttribute('aria-label', strings.mainMenuLabel || '');
		} else {
			panel.removeAttribute('role');
			panel.removeAttribute('aria-modal');
			panel.removeAttribute('aria-label');
		}

		syncSubmenuAccessibility(navigation);
	}

	function closeNavigation(navigation, restoreFocus) {
		var toggle = getToggle(navigation);
		var wasOpen = navigation.classList.contains('is-open');

		navigation.classList.remove('is-open');
		resetSubmenus(navigation);
		syncNavigationAccessibility(navigation);

		if (openNavigation === navigation) {
			openNavigation = null;
			unlockPageScroll();
		}

		if (restoreFocus && wasOpen && toggle) {
			toggle.focus();
		}
	}

	function openMenu(navigation) {
		var panel = getMenuPanel(navigation);
		var close = navigation.querySelector('.cw-classic-navigation__close');

		if (!panel || !isMobileLayout() || !navigation.classList.contains('cw-compact-navigation-active')) {
			return;
		}

		if (openNavigation && openNavigation !== navigation) {
			closeNavigation(openNavigation, false);
		}

		navigation.classList.add('is-open');
		openNavigation = navigation;
		lockPageScroll();
		syncNavigationAccessibility(navigation);

		window.requestAnimationFrame(function () {
			if (close) {
				close.focus();
				return;
			}

			var focusables = getFocusableElements(panel);
			if (focusables.length) {
				focusables[0].focus();
			}
		});
	}

	function toggleMenu(navigation) {
		if (navigation.classList.contains('is-open')) {
			closeNavigation(navigation, true);
			return;
		}

		openMenu(navigation);
	}

	function trapFocus(event) {
		if ('Tab' !== event.key || !openNavigation || !openNavigation.classList.contains('is-open')) {
			return;
		}

		var panel = getMenuPanel(openNavigation);
		if (!panel) {
			return;
		}

		var focusables = getFocusableElements(panel);
		if (!focusables.length) {
			event.preventDefault();
			return;
		}

		var first = focusables[0];
		var last = focusables[focusables.length - 1];

		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		var navigations = document.querySelectorAll('.cw-classic-navigation--primary');

		navigations.forEach(function (navigation) {
			var toggle = getToggle(navigation);
			var backdrop = navigation.querySelector('.cw-classic-navigation__backdrop');
			var close = navigation.querySelector('.cw-classic-navigation__close');

			if (!toggle) {
				return;
			}

			initializeSubmenuControls(navigation);
			updateCompactNavigationState(navigation);
			syncNavigationAccessibility(navigation);

			toggle.addEventListener('click', function () {
				toggleMenu(navigation);
			});

			if (backdrop) {
				backdrop.addEventListener('click', function () {
					closeNavigation(navigation, true);
				});
			}

			if (close) {
				close.addEventListener('click', function () {
					closeNavigation(navigation, true);
				});
			}

			navigation.querySelectorAll('.cw-classic-navigation__menu a').forEach(function (link) {
				link.addEventListener('click', function () {
					if (isMobileLayout()) {
						closeNavigation(navigation, false);
					}
				});
			});
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key && openNavigation) {
				event.preventDefault();
				closeNavigation(openNavigation, true);
				return;
			}

			trapFocus(event);
		});

		window.addEventListener('resize', function () {
			navigations.forEach(function (navigation) {
				updateCompactNavigationState(navigation);
				syncNavigationAccessibility(navigation);
			});
		});
	});
}());
