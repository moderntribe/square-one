/**
 * Scripts to control the site site-header
 */

import delegate from 'delegate';
import * as tools from 'utils/tools';
import * as globalOptions from 'config/options';
import globalState from 'config/state';

const el = {};
const state = {
	desktop: true,
	searchAnimating: false,
	searchOpen: false,
	searchFlyoutOpen: false,
};

/**
 * @function closeSearch
 * @description Close the search menu for desktop.
 */

const closeSearch = () => {
	el.container.classList.remove( 'c-search--site-header-hide-nav' );
	setTimeout( () => {
		el.container.classList.remove( 'c-search--site-header-reveal-search' );
		el.desktopSearchToggle.focus();
	}, 25 );
	setTimeout( () => {
		el.container.classList.remove( 'c-search--site-header-animate-search' );
		state.searchAnimating = false;
		state.searchOpen = false;
	}, 500 );
};

/**
 * @function openSearch
 * @description Open the search menu for desktop.
 */

const openSearch = () => {
	el.container.classList.add( 'c-search--site-header-animate-search' );
	setTimeout( () => {
		el.container.classList.add( 'c-search--site-header-reveal-search' );
	}, 25 );
	setTimeout( () => {
		el.container.classList.remove( 'c-search--site-header-hide-nav' );
		state.searchAnimating = false;
		state.searchOpen = true;
		el.desktopSearchInput.focus();
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
 * @function maybeMoveSearch
 * @description Moves search in and out of the nav element. Mobile needs it inside, desktop out.
 */

const maybeMoveSearch = () => {
	if ( ! el.searchForm ) {
		return;
	}

	if ( globalState.v_width < globalOptions.FULL_BREAKPOINT && state.desktop ) {
		el.searchForm.classList.remove( 'c-search--site-header' );
		el.searchForm.classList.add( 'c-search--mobile-site-header' );
		el.nav.insertAdjacentElement( 'afterbegin', el.searchForm );
		state.desktop = false;
	} else if ( globalState.v_width >= globalOptions.FULL_BREAKPOINT && ! state.desktop ) {
		el.searchForm.classList.add( 'c-search--site-header' );
		el.searchForm.classList.remove( 'c-search--mobile-site-header' );
		el.desktopSearchToggle.insertAdjacentElement( 'afterend', el.searchForm );
		state.desktop = true;
	}
};

/**
 * @function submitDefaultSearch
 * @description submits the form if there's characters on the input search
 */

const submitSearchForm = () => {
	if ( el.desktopSearchInput.value.length !== 0 ) {
		el.searchForm.submit();
		el.desktopSearchInput.value = '';
	}
};

/**
 * @function cacheElements
 */

const cacheElements = () => {
	el.searchForm = tools.getNodes( 'search-form', false, el.container )[ 0 ];
	el.nav = tools.getNodes( 'c-nav-main', false, el.container )[ 0 ];
	el.desktopSearchInput = tools.getNodes( '.c-search--site-header > [data-js="search-form-input"]', false, el.container, true )[ 0 ];
	el.desktopSearchToggle = tools.getNodes( 'search-toggle', false, el.container )[ 0 ];
	el.searchFormTrigger = el.searchForm.querySelector( 'button[type="submit"]' );
};

/**
 * @function executeResize
 */

const executeResize = () => {
	if ( globalState.v_width < globalOptions.FULL_BREAKPOINT && state.searchOpen ) {
		closeSearch();
	}

	maybeMoveSearch();
};

/**
 * @function bindEvents
 */

const bindEvents = () => {
	delegate( el.container, '.c-site-header__search-toggle[data-js="search-toggle"]', 'click', toggleSearch );
	delegate( el.container, '.c-search--site-header [data-js="search-form-clear"]', 'click', toggleSearch );

	el.searchFormTrigger.addEventListener( 'click', submitSearchForm );

	document.addEventListener( 'modern_tribe/resize_executed', executeResize );
};

/**
 * Init this module.
 */
const init = ( container ) => {
	el.container = container;

	cacheElements();
	bindEvents();
	maybeMoveSearch();

	console.info( 'SquareOne Theme: Initialized site header scripts.' );
};

export default init;
