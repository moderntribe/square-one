/**
 * @module
 * @description JavaScript specific to forms, specifically Gravity Forms.
 */

import Spinner from 'spin.js';
import scrollTo from '../../src/utils/dom/scroll-to';

let el = document.getElementById('site-wrap');
let $el;
let spinner;

/**
 * @function init
 * @description Kick off this modules functions
 */

const forms = () => {
	if (el) {
		$el = $(el);

		spinner = new Spinner({
			lines: 11,
			length: 6,
			width: 2,
			radius: 5,
			color: '#333333',
			speed: 1.2,
		});

		bindEvents();

		console.info('Initialized global form scripts.');
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

let bindEvents = () => {
	$(document)
		.on('gform_post_render', gravityFormPostRender)
		.on('gform_confirmation_loaded', gravityFormConfirmationLoaded);

	$el.on('click', '.gform_button', (e) => spinOn(e));
};

/**
 * @function gravityFormPostRender
 * @description executes every time the form is rendered including initial form load,
 * next/previous page for multi-page forms, form rendered with validation errors,
 * confirmation message displayed, etc.
 */

let gravityFormPostRender = (e, formId) => {
	spinner.stop();
	scrollSubmit($('#gform_wrapper_' + formId));
};

/**
 * @function _gform_confirmation_loaded
 * @description@desc executes on AJAX-enabled forms when the confirmation page is loaded.
 */

let gravityFormConfirmationLoaded = (e, formId) => {
	scrollSubmit($('#gforms_confirmation_message_' + formId));
};

/***
 * @function _spin_on
 * @description Kicks off spinner for form submit.
 */

let spinOn = (e) => {
	spinner.spin(e.currentTarget.parentNode);
};

/**
 * @function _scroll_submit
 * @description Adjusts gravity form submit top placement.
 */

let scrollSubmit = ($target) => {
	scrollTo({
		duration: 500,
		offset: -60,
		target: $target,
	});
};

export default forms;
