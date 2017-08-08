/**
 * @module
 * @description Javascript that handles WordPress comment form.
 */

import delegate from 'delegate';
import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('comment-form')[0],
};

/**
 * @function validateCommentSubmit
 * @description Simple comment form validation.
 */

const validateCommentSubmit = (e) => {
	const inputs = tools.convertElements(el.container.querySelectorAll('textarea, input[name="author"], input[name="email"]'));

	if (!inputs.length) {
		return;
	}

	inputs.forEach((input) => {
		const valueCheck = input.value ? input.value.trim() : '';
		if (valueCheck.length === 0) {
			e.preventDefault();
		}
	});
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(el.container, 'input[type="submit"]', 'click', validateCommentSubmit);
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	console.info('Modern Tribe FE: Initialized comment form script.');
};

export default init;
