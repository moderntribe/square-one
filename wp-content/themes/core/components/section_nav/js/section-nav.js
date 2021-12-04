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
 * Open a particular Section Nav menu.
 *
 * @param sectionNav
 */
const openSectionNav = ( sectionNav ) => {
	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__container-toggle"]' );
	const container = sectionNav.querySelector( '[data-js="c-section-nav__container"]' );

	container.style.display = 'block';
	toggle.setAttribute( 'aria-expanded', 'true' );
};

/**
 * Close a particular Section Nav menu.
 *
 * @param sectionNav
 */
const closeSectionNav = ( sectionNav ) => {
	const toggle = sectionNav.querySelector( '[data-js="c-section-nav__container-toggle"]' );
	const container = sectionNav.querySelector( '[data-js="c-section-nav__container"]' );

	// Bail if already closed.
	if ( toggle.getAttribute( 'aria-expanded' ) !== 'true' ) {
		return;
	}

	// Move focus to the sectionNav's toggle if it's currently inside the nav being closed.
	if ( document.activeElement.closest( '[data-js="c-section-nav"]' ) === sectionNav ) {
		toggle.focus();
	}

	container.style.display = 'none';
	toggle.setAttribute( 'aria-expanded', 'false' );
};

/**
 * Toggle all section Nav menus.
 *
 * @param closeNavs
 */
const toggleAllSectionNavs = ( closeNavs = true ) => {
	el.sectionNavs.forEach( ( sectionNav ) => {
		closeNavs ? closeSectionNav( sectionNav ) : openSectionNav( sectionNav );
	} );
};

/**
 * Handle click events for Section Nav menus on mobile.
 *
 * @param e
 */
const toggleSectionNav = ( e ) => {
	const sectionNav = e.target.closest( '[data-js="c-section-nav"]' );
	e.target.getAttribute( 'aria-expanded' ) === 'false' ? openSectionNav( sectionNav ) : closeSectionNav( sectionNav );
};

/**
 * Handle Escape key events for this module.
 *
 * @param e
 */
const handleEscKeyUp = ( e ) => {
	if ( e.key !== 'Escape' || ! componentState.isMobile ) {
		return;
	}

	toggleAllSectionNavs( true );
};

/**
 * Reset the nav to its default state
 *
 * @param sectionNav
 */
const resetNav = ( sectionNav ) => {
	// Reset the respective classes
	sectionNav.classList.remove( 'c-section-nav--more-active' );

	// Bail if there's no "more" list item
	if ( ! sectionNav.querySelector( '[data-js="c-section-nav__list-item--more"]' ) ) {
		return;
	}

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
	sectionNav.classList.add( 'c-section-nav--more-active' );

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
		toggleAllSectionNavs( false );
		componentState.isMobile = false;
		el.sectionNavs.forEach( sectionNav => handleNavFit( sectionNav ) );
	}

	// If viewport state is mobile, but component state is NOT, update component state and hide all section navs.
	if ( state.v_width < MOBILE_BREAKPOINT && ! componentState.isMobile ) {
		toggleAllSectionNavs( true );
		componentState.isMobile = true;
	}
};

/**
 * Build events for this module.
 */
const bindEvents = () => {
	delegate( el.container, '[data-js="c-section-nav__container-toggle"]', 'click', toggleSectionNav );

	on( document, 'keyup', handleEscKeyUp );
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
