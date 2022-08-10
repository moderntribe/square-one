/**
 * Scripts to control the site header navigation
 */

import delegate from 'delegate';

import globalState from 'config/state';
import * as globalOptions from 'config/options';
import * as a11y from 'utils/dom/accessibility';
import * as bodyLock from 'utils/dom/body-lock';
import * as slide from 'utils/dom/slide';

const el = {};

const state = {
	navAnimating: false,
	navOpen: false,
	submenuOpen: false,
};

const options = {
	navRevealSpeed: 400,
	submenuRevealSpeed: 250,
};

const closeSubmenuFast = ( trigger ) => {
	const listItem = trigger.closest( '.c-nav-primary__list-item--has-children' );
	const submenu = listItem.querySelector( '[data-js="c-nav-child-menu"]' );
	listItem.classList.remove( 'c-nav-primary__list-child--active' );
	listItem.classList.remove( 'c-nav-primary__list-child--animating' );
	submenu.style.maxHeight = '0';
	trigger.setAttribute( 'aria-expanded', 'false' );
	state.submenuOpen = false;
};

const closeAllSubmenus = () => {
	el.subMenuToggles.forEach( ( trigger ) => {
		if ( trigger.getAttribute( 'aria-expanded' ) === 'true' ) {
			closeSubmenuFast( trigger );
		}
	} );
};

const closeSubmenu = ( trigger, listItem, submenu ) => {
	slide.up( submenu, submenu.id );
	listItem.classList.remove( 'c-nav-primary__list-child--active' );

	setTimeout( () => {
		listItem.classList.remove( 'c-nav-primary__list-child--animating' );
		trigger.setAttribute( 'aria-expanded', 'false' );
		state.submenuOpen = false;
	}, options.submenuRevealSpeed );
};

const openSubmenu = ( trigger, listItem, submenu ) => {
	if ( globalState.v_width >= globalOptions.FULL_BREAKPOINT ) {
		closeAllSubmenus();
	}

	listItem.classList.add( 'c-nav-primary__list-child--animating' );

	setTimeout( () => {
		slide.down( submenu, submenu.id );
		listItem.classList.add( 'c-nav-primary__list-child--active' );
		trigger.setAttribute( 'aria-expanded', 'true' );
		state.submenuOpen = true;
	}, 25 );
};

const toggleSubmenu = ( e ) => {
	const trigger = e.target;
	const listItem = trigger.closest( '.c-nav-primary__list-item--has-children' );
	const submenu = listItem.querySelector( '[data-js="c-nav-child-menu"]' );
	trigger.getAttribute( 'aria-expanded' ) === 'true'
		? closeSubmenu( trigger, listItem, submenu )
		: openSubmenu( trigger, listItem, submenu );
};

const handleSubmenuKeyEvents = ( e ) => {
	if ( e.key !== 'Escape' && e.key !== 'Tab' ) {
		return;
	}

	if ( ! state.submenuOpen ) {
		return;
	}

	if ( globalState.v_width < globalOptions.FULL_BREAKPOINT ) {
		return;
	}

	const listItem = el.navContainer.querySelector( '.c-nav-primary__list-child--active' );
	const trigger = listItem.querySelector( '[data-js="c-nav-child-menu-trigger"]' );
	const submenu = listItem.querySelector( '[data-js="c-nav-child-menu"]' );

	if ( e.key === 'Escape' ) {
		closeSubmenuFast( trigger );
		trigger.focus();
	}

	if ( e.key === 'Tab' && ! listItem.contains( document.activeElement ) ) {
		closeSubmenu( trigger, listItem, submenu );
	}
};

const handleSubmenuClickOut = ( e ) => {
	if ( ! state.submenuOpen ) {
		return;
	}

	const listItem = el.navContainer.querySelector( '.c-nav-primary__list-child--active' );

	if ( ! listItem ) {
		return;
	}

	const trigger = listItem.querySelector( '[data-js="c-nav-child-menu-trigger"]' );

	if ( ! listItem.contains( e.target ) ) {
		closeSubmenuFast( trigger );
	}
};

const handleNavFlyoutKeyboardEvents = ( e ) => {
	if ( globalState.v_width >= globalOptions.FULL_BREAKPOINT ) {
		return;
	}

	// eslint-disable-next-line no-use-before-define
	a11y.focusLoop( e, el.navToggle, el.container, closeNavFlyout );
};

const closeNavFlyout = () => {
	closeAllSubmenus();
	el.container.classList.remove( 'c-site-header--nav-active' );
	el.navToggle.focus();
	el.container.removeEventListener( 'keydown', handleNavFlyoutKeyboardEvents );

	setTimeout( () => {
		el.container.classList.remove( 'c-site-header--nav-animating' );
		state.navAnimating = false;
		state.navOpen = false;
		bodyLock.unlock();
		document.body.style.overflowY = '';
		el.navToggle.setAttribute( 'aria-expanded', 'false' );
	}, options.navRevealSpeed );
};

const openNavFlyout = () => {
	closeAllSubmenus();
	window.scrollTo( 0, 0 );
	bodyLock.lock();
	document.body.style.overflowY = 'hidden';
	el.container.classList.add( 'c-site-header--nav-animating' );
	el.navToggle.setAttribute( 'aria-expanded', 'true' );
	el.container.addEventListener( 'keydown', handleNavFlyoutKeyboardEvents );

	setTimeout( () => {
		el.container.classList.add( 'c-site-header--nav-active' );
	}, 25 );

	setTimeout( () => {
		state.navAnimating = false;
		state.navOpen = true;
	}, options.navRevealSpeed );
};

const toggleNavFlyout = () => {
	if ( state.navAnimating ) {
		return;
	}

	state.navAnimating = true;
	state.navOpen ? closeNavFlyout() : openNavFlyout();
};

/**
 * Be sure to close the flyout when switching to desktop.
 */
const executeResize = () => {
	if ( globalState.v_width >= globalOptions.FULL_BREAKPOINT && state.navOpen ) {
		closeNavFlyout();
	}
};

/**
 * @function bindEvents
 */
const bindEvents = () => {
	delegate( el.navContainer, '[data-js="c-nav-child-menu-trigger"]', 'click', toggleSubmenu );

	el.navToggle.addEventListener( 'click', toggleNavFlyout );
	document.addEventListener( 'click', handleSubmenuClickOut );
	document.addEventListener( 'keyup', handleSubmenuKeyEvents );
	document.addEventListener( 'modern_tribe/resize_executed', executeResize );
};

/**
 * Cache elements needed for this script
 */
const cacheElements = () => {
	el.navContainer = el.container.querySelector( '[data-js="nav-flyout"]' );
	el.navToggle = el.container.querySelector( '[data-js="flyout-toggle"]' );
	el.subMenuToggles = el.navContainer.querySelectorAll( '[data-js="c-nav-child-menu-trigger"]' );
};

/**
 * Init this module.
 */
const init = ( container ) => {
	el.container = container;

	cacheElements();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized Site Header Navigation scripts.' );
};

export default init;
