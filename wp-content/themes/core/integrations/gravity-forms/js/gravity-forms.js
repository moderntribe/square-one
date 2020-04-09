/**
 * @module
 * @description JavaScript specific to forms, specifically Gravity Forms.
 */

import { Spinner } from 'spin.js';
import delegate from 'delegate';
import * as tools from 'utils/tools';
import scrollTo from 'utils/dom/scroll-to';

const el = {
	container: tools.getNodes( 'site-wrap' )[ 0 ],
};

let spinner;
let submitting = false;

/**
 * @function scrollSubmit
 * @description Adjusts gravity form submit top placement.
 */

const scrollSubmit = ( form ) => {
	scrollTo( {
		duration: 500,
		offset: -60,
		$target: window.$( form ),
	} );
};

/**
 * @function gravityFormSubmit
 * @description We only want post_render doing its thing if a submit is in play.
 * This takes care of that.
 */

const gravityFormSubmit = () => {
	submitting = true;
};

/**
 * @function gravityFormPostRender
 * @description executes every time the form is rendered including initial form load,
 * next/previous page for multi-page forms, form rendered with validation errors,
 * confirmation message displayed, etc.
 */

const gravityFormPostRender = ( e, formId ) => {
	if ( ! submitting ) {
		return;
	}

	spinner.stop();
	submitting = false;
	scrollSubmit( document.getElementById( `gform_wrapper_${ formId }` ) );
};

/**
 * @function gravityFormConfirmationLoaded
 * @description executes on AJAX-enabled forms when the confirmation page is loaded.
 */

const gravityFormConfirmationLoaded = ( e, formId ) => {
	scrollSubmit( document.getElementById( `gforms_confirmation_message_${ formId }` ) );
};

/**
 * @function spinOn
 * @description Kicks off spinner for form submit.
 */

const spinOn = ( e ) => {
	spinner.spin( e.delegateTarget.parentNode );
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

const bindEvents = () => {
	window.$( document )
		.on( 'submit', '.gform_wrapper form', gravityFormSubmit )
		.on( 'gform_post_render', gravityFormPostRender )
		.on( 'gform_confirmation_loaded', gravityFormConfirmationLoaded );

	delegate( el.container, '.gform_button', 'click', spinOn );
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const gravityForms = () => {
	if ( ! el.container ) {
		return;
	}

	spinner = new Spinner( {
		lines: 11,
		length: 6,
		width: 2,
		radius: 5,
		color: '#333333',
		speed: 1.2,
	} );

	bindEvents();

	console.info( 'Square One FE: Initialized global form scripts.' );
};

export default gravityForms;
