/**
 * @module
 * @description Javascript that drives the sitewide accordion widget. Uses lodash.
 */

'use strict';

import { remove_class, add_class, has_class } from '../utils/tools';
import { set_acc_active_attributes, set_acc_inactive_attributes } from '../utils/dom/accessibility';
import scroll_to from '../utils/dom/scroll-to';

// setup shared variables

let pn = document.getElementById( 'panel-navigation' );
let gs = TweenMax;
let options;

/**
 * @function _bind_events
 * @description Bind the events for this module here.
 */

let _bind_events = () => {

	$( options.el )
		.on( 'click', '.ac-header', ( e ) => _toggle_item( e ) );

};

/**
 * @function _close_others
 * @param {HTMLElement} row The domnode to map from.
 * @description Close the other accordion toggles.
 */

let _close_others = ( row ) => {

	gs.to( row.parentNode.querySelectorAll( '.active .ac-content' ), options.speed, { height:0 } );

	Array.prototype.forEach.call( row.parentNode.querySelectorAll( '.active' ), ( row ) => {
		remove_class( row, 'active' );
		set_acc_inactive_attributes( row.querySelectorAll( '.ac-header' )[0], row.querySelectorAll( '.ac-content' )[0] );
	});

};


/**
 * @function _set_offset
 * @description We have to account for scroll offset due to admin bar and maybe a fixed panel nav when scrolling
 */

let _set_offset = () => {

	options.offset = -10;

	if( has_class( document.body, 'admin-bar' ) ){
		options.offset = options.offset - 40;
	}

	if( pn ){
		options.offset = options.offset - pn.offsetHeight;
	}

};

/**
 * @function _toggle_item
 * @param {Object} e The js event object.
 * @description Toggle the active accordion item using class methods.
 */

let _toggle_item = ( e ) => {

	let header = e.currentTarget,
		content = header.nextElementSibling;

	if( has_class( header.parentNode, 'active' ) ){

		remove_class( header.parentNode, 'active' );

		set_acc_inactive_attributes( header, content );

		gs.to( content, options.speed, {
			height:0,
			onComplete: function() {
				$( document ).trigger( 'modern_tribe/accordion_animated' );
			}
		} );

	} else {

		_close_others( header.parentNode );

		add_class( header.parentNode, 'active' );

		set_acc_active_attributes( header, content );

		_set_offset();

		gs.set( content, { height:"auto" } );
		gs.from( content, options.speed, {
			height:0,
			onComplete: function() {
				scroll_to( {
					after_scroll:function(){$( document ).trigger( 'modern_tribe/accordion_animated' );},
					offset  : options.offset,
					duration: 300,
					target  : $( header.parentNode )
				} );
			}
		} );

	}

};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

let init = ( opts ) => {

	options = _.assign( {
		el   : document.getElementsByClassName( 'widget-accordion' ),
		speed: 0.3
	}, opts );

	if( options.el.length ){

		_set_offset();
		_bind_events();

		console.info( 'Initialized accordion widget class.' );
	}
};

export default init;
