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

const navFits = sectionNav => sectionNav.offsetWidth >= sectionNav.scrollWidth;

const handleNavFit = ( sectionNav ) => {
	if ( componentState.isMobile ) {
		return;
	}

	if ( navFits( sectionNav ) ) {
		return;
	}

	const template = sectionNav.querySelector( '[data-template="more"]' );
	const sectionNavList = sectionNav.querySelector( '[data-js="c-section-nav__list"]' );
	const moreMenu = document.importNode( template.content, true );
	const moreList = moreMenu.querySelector( '[data-js="c-section-nav__list--more"]' );
	sectionNavList.append( moreMenu );

	do {
		const lastItem = sectionNavList.querySelector( 'li:nth-last-child(2)' );
		moreList.append( lastItem );
	} while ( ! navFits( sectionNav ) );
};

/**
 * Handle resize events for this module.
 */
const handleResize = () => {
	// If viewport state larger than mobile, but component state is mobile, update component state to match and show all section navs.
	if ( state.v_width >= MOBILE_BREAKPOINT && componentState.isMobile ) {
		toggleAllSectionNavs( false );
		componentState.isMobile = false;
	}

	// If viewport state is mobile, but component state is NOT, update component state and hide all section navs.
	if ( state.v_width < MOBILE_BREAKPOINT && ! componentState.isMobile ) {
		toggleAllSectionNavs( true );
		componentState.isMobile = true;
	}
};

const handleInitialState = () => {
	el.sectionNavs.forEach( sectionNav => handleNavFit( sectionNav ) );
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
