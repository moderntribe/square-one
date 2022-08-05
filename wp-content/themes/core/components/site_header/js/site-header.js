/**
 * Scripts to control the site site-header
 */

import delegate from 'delegate';

import * as globalOptions from 'config/options';
import globalState from 'config/state';

const el = {};
const state = {
	searchAnimating: false,
	searchOpen: false,
};

/**
 * @function closeSearch
 * @description Close the search menu for desktop.
 *
 * @param reFocus bool	Should the close method move the cursor back to the search flyout toggle?
 */
const closeSearch = ( reFocus = true ) => {
	el.container.classList.remove( 'c-site-header--hide-nav' );
	setTimeout( () => {
		el.container.classList.remove( 'c-site-header--search-active' );
		el.desktopSearchToggle.setAttribute( 'aria-expanded', 'false' );
		if ( reFocus ) {
			el.desktopSearchToggle.focus();
		}
	}, 25 );
	setTimeout( () => {
		el.container.classList.remove( 'c-site-header--search-animating' );
		state.searchAnimating = false;
		state.searchOpen = false;
	}, 500 );
};

/**
 * @function openSearch
 * @description Open the search menu for desktop.
 */
const openSearch = () => {
	el.container.classList.add( 'c-site-header--search-animating' );

	setTimeout( () => {
		el.container.classList.add( 'c-site-header--search-active' );
		el.desktopSearchToggle.setAttribute( 'aria-expanded', 'true' );
	}, 25 );

	setTimeout( () => {
		el.container.classList.add( 'c-site-header--hide-nav' );
		state.searchAnimating = false;
		state.searchOpen = true;
		el.searchFormInput.focus();
	}, 500 );
};

/**
 * @function toggleSearch
 * @description
 */
const toggleSearch = () => {
	if ( state.searchAnimating ) {
		return;
	}
	state.searchAnimating = true;

	if ( state.searchOpen ) {
		closeSearch();
	} else {
		openSearch();
	}
};

/**
 * @function submitDefaultSearch
 * @description submits the form if there's characters on the input search
 */
const submitSearchForm = () => {
	if ( el.searchFormInput.value.length !== 0 ) {
		el.searchFlyout.submit();
		el.searchFormInput.value = '';
	}
};

/**
 * Close the search flyout if clicked outside.
 *
 * @param e
 */
const handleClickOut = ( e ) => {
	if ( ! state.searchOpen ) {
		return;
	}

	// Clicked outside the nav container, close it.
	if ( ! e.target.closest( '[data-js="nav-flyout"]' ) ) {
		closeSearch();
	}
};

/**
 * Close the search flyout on Escape key up.
 *
 * @param e
 */
const handleEscKeyOut = ( e ) => {
	if ( e.key !== 'Escape' || ! state.searchOpen ) {
		return;
	}

	closeSearch();
};

/**
 * Close the search flyout when tabbing out of it.
 *
 * @param e
 */
const handleTabKeyOut = ( e ) => {
	if ( e.key !== 'Tab' || ! state.searchOpen ) {
		return;
	}

	// Tabbed outside the nav container, close it.
	if ( ! e.target.closest( '[data-js="nav-flyout"]' ) ) {
		closeSearch( false );
	}
};

/**
 * @function executeResize
 */
const executeResize = () => {
	if ( globalState.v_width < globalOptions.FULL_BREAKPOINT && state.searchOpen ) {
		closeSearch();
	}
};

/**
 * @function bindEvents
 */
const bindEvents = () => {
	delegate( el.container, '[data-js="search-toggle"]', 'click', toggleSearch );
	delegate( el.mobileNavFlyout, 'input, button', 'keyup', handleEscKeyOut );

	el.searchFormTrigger.addEventListener( 'click', submitSearchForm );
	document.addEventListener( 'click', handleClickOut );
	document.addEventListener( 'keyup', handleTabKeyOut );
	document.addEventListener( 'modern_tribe/resize_executed', executeResize );
};

/**
 * @function cacheElements
 */
const cacheElements = () => {
	el.mobileNavFlyout = el.container.querySelector( '[data-js="nav-flyout"]' );
	el.searchFlyout = el.container.querySelector( '[data-js="search-flyout"]' );
	el.desktopSearchToggle = el.container.querySelector( '[data-js="search-toggle"]' );
	el.searchFormInput = el.container.querySelector( '[data-js="search-form-input"]' );
	el.searchFormTrigger = el.searchFlyout.querySelector( 'button[type="submit"]' );
};

/**
 * Init this module.
 */
const init = ( container ) => {
	el.container = container;

	cacheElements();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized Site Header component scripts.' );
};

export default init;
