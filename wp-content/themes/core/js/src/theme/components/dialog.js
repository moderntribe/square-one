/**
 * @module
 * @description JavaScript that drives Dialog
 */

import A11yDialog from 'mt-a11y-dialog';
import Swiper from 'swiper';
import * as tools from 'utils/tools';

const el = {
	siteWrap: tools.getNodes('site-wrap')[0],
	container: tools.getNodes('c-dialog-trigger', true),
};

const instances = {
	dialogs: {},
};

const options = {
	dialog: {
		appendTarget: '',
		wrapperClasses: 'c-dialog',
		closeButtonClasses: 'c-dialog__close-button',
		trigger: '[data-js="c-dialog-trigger"]',
	},
	swiper: {
		a11y: true,
		grabCursor: true,
		keyboard: true,
		pagination: {
			el: '.swiper-pagination',
			type: 'fraction',
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		spaceBetween: 60,
	},
};

/**
 * @function initSwiper
 * @description
 */

const initSwiper = (dialogEl) => {
	const gallery = tools.getNodes('c-slider', false, dialogEl)[0];
	if (gallery) {
		instances.swiper = new Swiper(gallery, options.swiper);
	}
};

/**
 * @function addDialogCloseSpan
 * @description Add span to close button.
 */

const addDialogCloseSpan = () => {
	const closeBtn = tools.getNodes('.c-dialog__close-button', true, document, true)[0];
	const span = document.createElement('span');
	span.className = 'c-dialog__close-button-icon';
	if (!closeBtn.hasChildNodes()) {
		closeBtn.appendChild(span);
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = (instance) => {
	instance.on('show', initSwiper);
	instance.on('show', addDialogCloseSpan);
};

/**
 * @function initDialog
 * @description Initialize Dialog
 */

const initDialogs = () => {
	tools.getNodes('[data-js="c-dialog-trigger"]', true, document, true).forEach( (trigger) => {
		const dialogId = trigger.getAttribute('data-content');
		options.dialog.trigger = `[data-js="c-dialog-trigger"][data-content="${dialogId}"]`;
		instances.dialogs[dialogId] = new A11yDialog(options.dialog);
		bindEvents(instances.dialogs[dialogId]);
	} );
};

/**
 * @function init
 * @description Kick off this modules functions.
 */

const init = () => {
	if (!el.container) {
		return;
	}

	initDialogs();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
