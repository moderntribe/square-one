/**
 * Scripts to control the site header navigation
 */

import * as bodyLock from 'utils/dom/body-lock';

const el = {};

const state = {
	navAnimating: false,
	navOpen: false,
};

const options = {
	navRevealSpeed: 400,
};

const closeNavFlyout = () => {
	el.container.classList.remove( 'c-site-header--nav-active' );
	el.navToggle.focus();

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
	window.scrollTo( 0, 0 );
	bodyLock.lock();
	document.body.style.overflowY = 'hidden';
	el.container.classList.add( 'c-site-header--nav-animating' );
	el.navToggle.setAttribute( 'aria-expanded', 'true' );

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
 * @function bindEvents
 */
const bindEvents = () => {
	el.navToggle.addEventListener( 'click', toggleNavFlyout );
};

/**
 * Cache elements needed for this script
 */
const cacheElements = () => {
	el.navContainer = el.container.querySelector( '[data-js="nav-flyout"]' );
	el.navToggle = el.container.querySelector( '[data-js="flyout-toggle"]' );
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
