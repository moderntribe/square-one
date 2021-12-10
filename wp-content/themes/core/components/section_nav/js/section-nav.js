/* -----------------------------------------------------------------------------
 *
 * Component: Section Nav
 *
 * ----------------------------------------------------------------------------- */

import delegate from 'delegate';

import { on } from 'utils/events';
import state from 'config/state';
import adminState from '../../../assets/js/src/admin/config/state';

const MOBILE_BREAKPOINT = 600;

const intObserverOpts = {
	threshold: 1,
	rootMargin: '-49px 0px 0px 0px', /* Set to 1px LESS than the `top: Npx` value applied to `.c-section-nav` when sticky. */
};

const el = {
	container: document.querySelector( '[data-js="site-wrap"]' ) ?? document.querySelector( '.is-root-container.block-editor-block-list__layout' ),
};

const componentState = {
	vWidth: state.v_width,
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
 * If the scroll width of the inner wrapper within the component is less than or equal to
 * the offset width of the inner wrapper, then the nav fits.
 *
 * @param sectionNav
 * @returns {boolean}
 */
const navFits = ( sectionNav ) => {
	const inner = sectionNav.querySelector( '[data-js="c-section-nav__inner"]' );
	return inner.offsetWidth >= inner.scrollWidth;
};

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
 * Set up the resizeObserver callback for each SectionNav.
 *
 * @type {ResizeObserver}
 */
const sectionNavResizeObserver = new ResizeObserver( ( [ entry ] ) => handleNavFit( entry.target ) );

/**
 * Set up the IntersectionObserver callback for each SectionNav.
 *
 * @type {IntersectionObserver}
 */
const sectionNavIntersectionObserver = new IntersectionObserver( ( [ entry ] ) => {
	entry.target.classList.toggle( 'c-section-nav--stuck', entry.intersectionRatio < 1 );
}, intObserverOpts );

/**
 * Initialize the observers applied to each SectionNav.
 *
 * @param sectionNav
 */
const initializeObservers = ( sectionNav ) => {
	sectionNavResizeObserver.observe( sectionNav );
	sectionNavIntersectionObserver.observe( sectionNav );
};

/**
 * Handle the initial state for the component.
 */
const handleInitialState = () => {
	componentState.isMobile = componentState.vWidth < MOBILE_BREAKPOINT;
	el.sectionNavs.forEach( sectionNav => initializeObservers( sectionNav ) );
};

/**
 * Handle the ACF editor preview update.
 *
 * @param $block
 */
const handlePreviewUpdate = ( $block ) => {
	const sectionNav = $block.find( '[data-js="c-section-nav"]' )[ 0 ];

	if ( ! sectionNav ) {
		return;
	}

	handleNavFit( sectionNav );
};

/**
 * Be sure the component state's viewport width is based on whichever JS bundle is being loaded (theme or admin).
 */
const updateViewportWidth = () => componentState.vWidth = state.v_width || adminState.v_width;

/**
 * Handle resize events for this module.
 */
const handleResize = () => {
	updateViewportWidth();

	// If viewport state larger than mobile, but component state is mobile, update component state to match and show all section navs.
	if ( componentState.vWidth >= MOBILE_BREAKPOINT && componentState.isMobile ) {
		toggleAllSectionNavs( 'open' );
		componentState.isMobile = false;
		el.sectionNavs.forEach( sectionNav => handleNavFit( sectionNav ) );
	}

	// If viewport state is mobile, but component state is NOT, update component state and hide all section navs.
	if ( componentState.vWidth < MOBILE_BREAKPOINT && ! componentState.isMobile ) {
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

	if ( window.acf ) {
		window.acf.addAction( 'render_block_preview/type=sectionnav', handlePreviewUpdate );
	}
};

/**
 * Kick off this module's functions
 */
const init = ( sectionNavs ) => {
	if ( ! sectionNavs.length || ! el.container ) {
		return;
	}

	el.sectionNavs = sectionNavs;

	updateViewportWidth();
	handleInitialState();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized Section Nav component scripts.' );
};

export default init;
