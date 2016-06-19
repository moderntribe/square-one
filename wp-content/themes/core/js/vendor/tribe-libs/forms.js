/**
 * @module
 * @description JavaScript specific to forms, specifically Gravity Forms.
 */

import Spinner from 'spin.js';
import scrollTo from '../../src/utils/dom/scroll-to';

const el = document.getElementById('site-wrap');
let $el;
let spinner;
let submitting = false;

/**
 * @function scrollSubmit
 * @description Adjusts gravity form submit top placement.
 */

const scrollSubmit = ($form) => {
	scrollTo({
		duration: 500,
		offset: -60,
		$target: $form,
	});
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

const gravityFormPostRender = (e, formId) => {
	if (!submitting) {
		return;
	}

	spinner.stop();
	submitting = false;
	scrollSubmit($(`#gform_wrapper_${formId}`));
};

/**
 * @function gravityFormConfirmationLoaded
 * @description executes on AJAX-enabled forms when the confirmation page is loaded.
 */

const gravityFormConfirmationLoaded = (e, formId) => {
	scrollSubmit($(`#gforms_confirmation_message_${formId}`));
};

/**
 * @function spinOn
 * @description Kicks off spinner for form submit.
 */

const spinOn = (e) => {
	spinner.spin(e.currentTarget.parentNode);
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

const bindEvents = () => {
	$(document)
		.on('submit', '.gform_wrapper form', gravityFormSubmit)
		.on('gform_post_render', gravityFormPostRender)
		.on('gform_confirmation_loaded', gravityFormConfirmationLoaded);

	$el.on('click', '.gform_button', (e) => spinOn(e));
};

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

export default forms;
