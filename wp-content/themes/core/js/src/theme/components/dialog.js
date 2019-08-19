/**
 * @module
 * @description JavaScript that drives Dialog
 */

import Dialog from 'mt-a11y-dialog';
import Swiper from 'swiper';
import * as tools from 'utils/tools';

const el = {
	container: tools.getNodes( 'c-dialog-trigger', true ),
};

const instances = {};

const options = {
	dialog: {
		appendTarget: '[data-js="site-wrap"]',
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

const initSwiper = ( dialogEl ) => {
	const gallery = tools.getNodes( 'c-slider', false, dialogEl )[0];
	instances.swiper = new Swiper( gallery, options.swiper );
};

/**
 * @function initDialog
 * @description Initialize Dialog
 */

const initDialog = () => {
	instances.dialog = new Dialog( options.dialog );
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	instances.dialog.on('show', initSwiper);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	if ( ! el.container ) {
		return;
	}

	initDialog();
	bindEvents();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
