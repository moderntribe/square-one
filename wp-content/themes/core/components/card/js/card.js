/** -----------------------------------------------------------------------------
 *
 * Component: Card
 *
 * Scripts specific to the card component.
 *
 * ----------------------------------------------------------------------------- */

import delegate from 'delegate';

/* Maximum amount of time between mousedown & mouseup to be considered a true click */
const MOUSEUP_THRESHOLD = 200;

/* Elements that should be excluded when handling the card click-within */
const EXCLUDED_TARGETS = [ 'A', 'BUTTON' ];

const el = {
	siteWrap: document.querySelector( '[data-js="site-wrap"]' ),
};

const state = {
	down: 0,
	up: 0,
};

/**
 * handleCardClick
 *
 * Finds the relevant target link and triggers location change to that URL.
 *
 * @param e
 */
const handleCardClick = ( e ) => {
	const targetLinkEl = e.delegateTarget.querySelector( '[data-js="target-link"]' );

	if ( targetLinkEl && targetLinkEl.hasAttribute( 'href' ) ) {
		const url = targetLinkEl.getAttribute( 'href' );
		e.ctrlKey ? window.open( url ) : window.location = url;
	}
};

/**
 * handleCardMouseDown
 *
 * Sets a timestamp on mousedown for cards for testing text selection.
 *
 * @param e
 */
const handleCardMouseDown = ( e ) => {
	if ( EXCLUDED_TARGETS.includes( e.target.nodeName ) ) {
		return;
	}

	state.down = new Date();
};

/**
 * handleCardMouseUp
 *
 * Checks the amount of time past since mousedown and triggers a click
 * if the time past is less than the threshold.
 *
 * @param e
 */
const handleCardMouseUp = ( e ) => {
	if ( EXCLUDED_TARGETS.includes( e.target.nodeName ) ) {
		return;
	}

	state.up = new Date();

	if ( state.up - state.down < MOUSEUP_THRESHOLD ) {
		handleCardClick( e );
	}
};

/**
 * bindEvents
 */
const bindEvents = () => {
	delegate( el.siteWrap, '[data-js="use-target-link"]', 'mousedown', handleCardMouseDown );
	delegate( el.siteWrap, '[data-js="use-target-link"]', 'mouseup', handleCardMouseUp );
};

/**
 * init
 */
const init = () => {
	bindEvents();

	console.info( 'SquareOne Theme: Initialized card component scripts.' );
};

export default init;
