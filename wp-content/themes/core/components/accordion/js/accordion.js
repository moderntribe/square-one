/* -----------------------------------------------------------------------------
 *
 * Component: Accordion
 *
 * ----------------------------------------------------------------------------- */

/**
 * @module
 * @description Javascript that drives the sitewide accordion widget. Uses lodash.
 */

import _ from 'lodash';
import delegate from 'delegate';

import scrollTo from 'utils/dom/scroll-to';
import * as slide from 'utils/dom/slide';
import * as tools from 'utils/tools';
import * as events from 'utils/events';

const el = {
	container: tools.getNodes( 'c-accordion' ),
};

const siteWrap = tools.getNodes( 'site-wrap' )[ 0 ];
const pn = document.getElementById( 'panel-navigation' );
const options = {
	speed: 300,
};

/**
 * @function closeOthers
 * @param {HTMLElement} row The domnode to map from.
 * @description Close the other accordion toggles.
 */

const closeOthers = ( row ) => {
	tools.getNodes( '.active .c-accordion__content', true, row.parentNode, true ).forEach( accordion => slide.up( accordion, accordion.id, options.speed ) );
	tools.getNodes( '.active', true, row.parentNode, true ).forEach( ( childRow ) => {
		const header = childRow.querySelector( '.c-accordion__header' );
		const content = childRow.querySelector( '.c-accordion__content' );
		tools.removeClass( childRow, 'active' );
		header.setAttribute( 'aria-expanded', 'false' );
		content.setAttribute( 'aria-hidden', 'true' );
		_.delay( () => {
			content.setAttribute( 'hidden', 'true' );
		}, options.speed );
	} );
};

/**
 * @function setOffset
 * @description We have to account for scroll offset due to admin bar and maybe a fixed panel nav when scrolling
 */

const setOffset = () => {
	options.offset = -20;

	if ( tools.hasClass( document.body, 'admin-bar' ) ) {
		options.offset = -20 - 40;
	}

	if ( pn ) {
		options.offset = -10 - pn.offsetHeight;
	}
};

/**
 *
 * @function triggerAfterScroll
 * @description - Trigger the `modern_tribe/accordion_animated` event
 */
const triggerAfterScroll = () => events.trigger( {
	event: 'modern_tribe/accordion_animated',
	native: false,
} );

/**
 * @function openAccordion
 * @description Toggle the accordion open
 */

const openAccordion = ( header, content ) => {
	const row = tools.closest( header, '.c-accordion__row' );
	const accordion = tools.closest( header, '.c-accordion' );
	closeOthers( row );
	tools.addClass( row, 'active' );
	header.setAttribute( 'aria-expanded', 'true' );
	content.setAttribute( 'aria-hidden', 'false' );
	content.removeAttribute( 'hidden' );
	setOffset();

	slide.down( content, content.id, options.speed );

	_.delay( () => {
		if ( ! accordion.dataset.scrollto ) {
			triggerAfterScroll();
			return;
		}
		scrollTo( {
			afterScroll: triggerAfterScroll,
			offset: options.offset,
			duration: 300,
			$target: $( row ),
		} );
	}, options.speed );
};

/**
 * @function closeAccordion
 * @description Toggle the accordion closed
 */

const closeAccordion = ( header, content ) => {
	const row = tools.closest( header, '.c-accordion__row' );
	tools.removeClass( row, 'active' );
	header.setAttribute( 'aria-expanded', 'false' );
	content.setAttribute( 'aria-hidden', 'true' );
	slide.up( content, content.id, options.speed );
	_.delay( () => {
		content.setAttribute( 'hidden', 'true' );
		triggerAfterScroll();
	}, options.speed );
};

/**
 * @function handlePanelEvents
 * @description
 */

const handlePanelEvents = ( e ) => {
	const panel = document.querySelectorAll( '.site-panel--accordion[class*="collection-preview__active"]' )[ 0 ];
	if ( ! panel ) {
		return;
	}
	const header = tools.getNodes( `.c-accordion__header[data-index="${ e.detail.rowIndex }"]`, false, panel, true )[ 0 ];
	const row = tools.closest( header, '.c-accordion__row' );
	closeOthers( row );
	openAccordion( header, header.nextElementSibling );
};

/**
 * @function toggleItem
 * @param {object} e The js event object.
 * @description Toggle the active accordion item using class methods.
 */
const toggleItem = ( e ) => {
	const header = e.delegateTarget;
	const row = tools.closest( header, '.c-accordion__row' );
	const content = row.querySelector( '.c-accordion__content' );

	if ( tools.hasClass( row, 'active' ) ) {
		closeAccordion( header, content );
	} else {
		openAccordion( header, content );
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate( siteWrap, '.c-accordion__header', 'click', toggleItem );
	document.addEventListener( 'modular_content/repeater_row_activated', handlePanelEvents );
	document.addEventListener( 'modular_content/repeater_row_added', handlePanelEvents );
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	if ( el.container.length === 0 ) {
		return;
	}

	setOffset();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized accordion component scripts.' );
};

export default init;
