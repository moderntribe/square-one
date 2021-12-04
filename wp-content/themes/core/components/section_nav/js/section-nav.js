/* -----------------------------------------------------------------------------
 *
 * Component: Section Nav
 *
 * ----------------------------------------------------------------------------- */

import delegate from 'delegate';

import { on } from 'utils/events';
import state from 'config/state';

const MOBILE_BREAKPOINT = 600;

const el = {
	container: document.querySelector( '[data-js="site-wrap"]' ),
};

const componentState = {
	isMobile: state.v_width < MOBILE_BREAKPOINT,
};

/**
 * Close a particular More menu.
 *
 * @param sectionNav
 */
const closeMoreMenu = ( sectionNav ) => {
	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__toggle--more"]' );

	// Bail if no more menu.
	if ( ! toggle ) {
		return;
	}

	// Move focus to the sectionNav's toggle if it's currently inside the nav being closed.
	const moreListItem = sectionNav.querySelector( '[data-js="c-section-nav__list-item--more"]' );

	if ( document.activeElement.closest( '[data-js="c-section-nav__list-item--more"]' ) === moreListItem ) {
		toggle.focus();
	}

	sectionNav.classList.remove( 'c-section-nav--more-active' );
	toggle.setAttribute( 'aria-expanded', 'false' );
};

/**
 * Close all the Section Nav More menus.
 */
const closeAllMoreMenus = () => el.sectionNavs.forEach( sectionNav => closeMoreMenu( sectionNav ) );

/**
 * Open a particular More menu.
 *
 * @param sectionNav
 */
const openMoreMenu = ( sectionNav ) => {
	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__toggle--more"]' );

	closeAllMoreMenus();
	sectionNav.classList.add( 'c-section-nav--more-active' );
	toggle.setAttribute( 'aria-expanded', 'true' );
};

/**
 * Handle click events for Section Nav More menus.
 *
 * @param e
 */
const toggleMoreMenu = ( e ) => {
	const sectionNav = e.target.closest( '[data-js="c-section-nav"]' );
	e.target.getAttribute( 'aria-expanded' ) === 'false' ? openMoreMenu( sectionNav ) : closeMoreMenu( sectionNav );
};

/**
 * Open a particular Section Nav menu on mobile.
 *
 * @param sectionNav
 */
const openSectionNav = ( sectionNav ) => {
	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__toggle--mobile"]' );

	sectionNav.classList.add( 'c-section-nav--visible' );
	toggle.setAttribute( 'aria-expanded', 'true' );
};

/**
 * Close a particular Section Nav menu on mobile.
 *
 * @param sectionNav
 * @param useDefaultState
 */
const closeSectionNav = ( sectionNav, useDefaultState = true ) => {
	// Bail early if this nav defaults to open and this close event was not a direct user action.
	if ( useDefaultState && sectionNav.dataset.initOpen ) {
		return;
	}

	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__toggle--mobile"]' );

	// Move focus to the sectionNav's toggle if it's currently inside the nav being closed.
	if ( document.activeElement.closest( '[data-js="c-section-nav"]' ) === sectionNav ) {
		toggle.focus();
	}

	sectionNav.classList.remove( 'c-section-nav--visible' );
	toggle.setAttribute( 'aria-expanded', 'false' );
};

/**
 * Toggle all section Nav menus.
 *
 * @param closeNavs
 */
const toggleAllSectionNavs = ( closeNavs = 'close' ) => {
	el.sectionNavs.forEach( ( sectionNav ) => {
		closeNavs === 'close' ? closeSectionNav( sectionNav ) : openSectionNav( sectionNav );
	} );
};

/**
 * Handle click events for Section Nav menus on mobile.
 *
 * @param e
 */
const toggleSectionNav = ( e ) => {
	const sectionNav = e.target.closest( '[data-js="c-section-nav"]' );
	e.target.getAttribute( 'aria-expanded' ) === 'false' ? openSectionNav( sectionNav ) : closeSectionNav( sectionNav, false );
};

/**
 * Reset the nav to its default state
 *
 * @param sectionNav
 */
const resetNav = ( sectionNav ) => {
	// Reset the respective classes
	sectionNav.classList.remove( 'c-section-nav--more-initialized' );

	if ( ! componentState.isMobile ) {
		sectionNav.classList.add( 'c-section-nav--visible' );
	}

	// Bail if there's no "more" list item
	if ( ! sectionNav.querySelector( '[data-js="c-section-nav__list-item--more"]' ) ) {
		return;
	}

	// Close the more menu, if it's open.
	closeMoreMenu( sectionNav );

	const sectionNavList = sectionNav.querySelector( '[data-js="c-section-nav__list"]' );
	const moreListItem = sectionNavList.querySelector( '[data-js="c-section-nav__list-item--more"]' );
	const moreList = sectionNavList.querySelector( '[data-js="c-section-nav__list--more"]' );
	const items = moreList.querySelectorAll( 'li' );

	// Move all the items back to the top-level menu
	items.forEach( item => sectionNavList.insertBefore( item, moreListItem ) );
};

/**
 * Injects the more menu markup from the respective JS <template> into the section nav list.
 *
 * @param sectionNav
 */
const injectMoreMenu = ( sectionNav ) => {
	const template = sectionNav.querySelector( '[data-template="more"]' );
	const sectionNavList = sectionNav.querySelector( '[data-js="c-section-nav__list"]' );
	const moreMenu = document.importNode( template.content, true );
	sectionNavList.append( moreMenu );
};

/**
 * Check if the nav fits
 *
 * If the scroll width of the component is less than or equal to
 * the offset width of the component, then the nav fits.
 *
 * @param sectionNav
 * @returns {boolean}
 */
const navFits = sectionNav => sectionNav.offsetWidth >= sectionNav.scrollWidth;

/**
 * Loop through all the menu items and file them into the More menu until the nav fits.
 *
 * @param sectionNav
 */
const fileItems = ( sectionNav ) => {
	sectionNav.classList.add( 'c-section-nav--more-initialized' );

	if ( ! sectionNav.querySelector( '[data-js="c-section-nav__list--more"]' ) ) {
		injectMoreMenu( sectionNav );
	}

	const sectionNavList = sectionNav.querySelector( '[data-js="c-section-nav__list"]' );
	const moreList = sectionNavList.querySelector( '[data-js="c-section-nav__list--more"]' );

	do {
		// Grab the list item just before the "More" item
		const lastItem = sectionNavList.querySelector( 'li:nth-last-child(2)' );
		moreList.prepend( lastItem );
	} while ( ! navFits( sectionNav ) );
};

/**
 * Handle the fit of the nav for desktop.
 *
 * @param sectionNav
 */
const handleNavFit = ( sectionNav ) => {
	resetNav( sectionNav );

	if ( componentState.isMobile || navFits( sectionNav ) ) {
		return;
	}

	fileItems( sectionNav );
};

/**
 * Set up the resizeObserver callback
 *
 * @type {ResizeObserver}
 */
const sectionNavResizeObserver = new ResizeObserver( entries => {
	for ( const entry of entries ) {
		handleNavFit( entry.target );
	}
} );

/**
 * Loop through any SectionNav components and attach the ResizeObserver to handle the desktop "more" menu.
 */
const handleInitialState = () => {
	el.sectionNavs.forEach( sectionNav => sectionNavResizeObserver.observe( sectionNav ) );
};

/**
 * Handle resize events for this module.
 */
const handleResize = () => {
	// If viewport state larger than mobile, but component state is mobile, update component state to match and show all section navs.
	if ( state.v_width >= MOBILE_BREAKPOINT && componentState.isMobile ) {
		toggleAllSectionNavs( 'open' );
		componentState.isMobile = false;
		el.sectionNavs.forEach( sectionNav => handleNavFit( sectionNav ) );
	}

	// If viewport state is mobile, but component state is NOT, update component state and hide all section navs.
	if ( state.v_width < MOBILE_BREAKPOINT && ! componentState.isMobile ) {
		toggleAllSectionNavs( 'close' );
		componentState.isMobile = true;
	}
};

/**
 * Handle Escape key events for this module.
 *
 * @param e
 */
const handleEscKeyUp = ( e ) => {
	if ( e.key !== 'Escape' ) {
		return;
	}

	componentState.isMobile ? toggleAllSectionNavs( 'close' ) : closeAllMoreMenus();
};

/**
 * Handle "out" events on mobile for this module.
 *
 * @param sectionNav
 * @param targetEl
 */
const handleOutMobile = ( sectionNav, targetEl ) => {
	// If the section nav is not visible or the focused element is still within this section nav, bail.
	if ( ! sectionNav.classList.contains( 'c-section-nav--visible' ) ||
		targetEl.closest( '[data-js="c-section-nav"]' ) === sectionNav ) {
		return;
	}

	closeSectionNav( sectionNav );
};

/**
 * Handle "out" events on desktop for this module.
 *
 * @param sectionNav
 * @param targetEl
 */
const handleOutDesktop = ( sectionNav, targetEl ) => {
	const moreListItem = sectionNav.querySelector( '[data-js="c-section-nav__list-item--more"]' );

	// If the More menu isn't active or the focused element is still within this more menu, bail.
	if ( ! sectionNav.classList.contains( 'c-section-nav--more-active' ) ||
		targetEl.closest( '[data-js="c-section-nav__list-item--more"]' ) === moreListItem ) {
		return;
	}

	closeMoreMenu( sectionNav );
};

/**
 * Handle click outside event for this module.
 *
 * @param e
 */
const handleClickOut = ( e ) => {
	el.sectionNavs.forEach( ( sectionNav ) => componentState.isMobile
		? handleOutMobile( sectionNav, e.target )
		: handleOutDesktop( sectionNav, e.target ) );
};

/**
 * Handle Tab outside events for this module.
 *
 * @param e
 */
const handleTabKeyUp = ( e ) => {
	if ( e.key !== 'Tab' ) {
		return;
	}

	el.sectionNavs.forEach( ( sectionNav ) => componentState.isMobile
		? handleOutMobile( sectionNav, e.target )
		: handleOutDesktop( sectionNav, e.target ) );
};

/**
 * Build events for this module.
 */
const bindEvents = () => {
	delegate( el.container, '[data-js="c-section-nav__toggle--more"]', 'click', toggleMoreMenu );
	delegate( el.container, '[data-js="c-section-nav__toggle--more"]', 'keyup', handleEscKeyUp );
	delegate( el.container, '[data-js="c-section-nav__toggle--mobile"]', 'click', toggleSectionNav );
	delegate( el.container, '[data-js="c-section-nav__toggle--mobile"]', 'keyup', handleEscKeyUp );
	delegate( el.container, '.section-nav__list-item a', 'keyup', handleEscKeyUp );

	on( document, 'click', handleClickOut );
	on( document, 'keyup', handleTabKeyUp );
	on( document, 'modern_tribe/resize_executed', handleResize );
};

/**
 * Kick off this module's functions
 */
const init = ( sectionNavs ) => {
	if ( ! sectionNavs.length ) {
		return;
	}

	el.sectionNavs = sectionNavs;

	handleInitialState();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized Section Nav component scripts.' );
};

export default init;