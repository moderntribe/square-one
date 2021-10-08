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
	isMobile: state.is_mobile,
};

const showSectionNav = ( toggle, container ) => {
	container.style.display = 'block';
	toggle.setAttribute( 'aria-expanded', 'true' );
};

const hideSectionNav = ( toggle, container ) => {
	container.style.display = 'none';
	toggle.setAttribute( 'aria-expanded', 'false' );
};

const toggleAllSectionNavs = ( hideNavs = true ) => {
	el.sectionNavs.forEach( ( sectionNav ) => {
		const toggle = sectionNav.querySelector( '[data-js="c-section-nav__container-toggle"]' );
		const container = sectionNav.querySelector( '[data-js="c-section-nav__container"]' );
		hideNavs ? hideSectionNav( toggle, container ) : showSectionNav( toggle, container );
	} );
};

const toggleSectionNav = ( e ) => {
	const toggle = e.target;
	const container = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
	e.target.getAttribute( 'aria-expanded' ) === 'false' ? showSectionNav( toggle, container ) : hideSectionNav( toggle, container );
};

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

const bindEvents = () => {
	delegate( el.container, '[data-js="c-section-nav__container-toggle"]', 'click', toggleSectionNav );

	on( document, 'modern_tribe/resize_executed', handleResize );
};

/**
 * @function init
 * @description Kick off this module's functions
 */

const init = ( sectionNavs ) => {
	if ( ! sectionNavs.length ) {
		return;
	}

	el.sectionNavs = sectionNavs;

	bindEvents();

	console.info( 'SquareOne Theme: Initialized Section Nav component scripts.' );
};

export default init;
