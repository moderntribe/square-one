/**
 * @module
 * @description Javascript that kicks in js specific to the social sharing of content.
 */

import popup from '../../src/utils/dom/popup';

let el = document.getElementsByClassName('social-share-popup');
let $el;

/**
 * @function init
 * @description Kick off this modules functions
 */

const socialShare = () => {
	if (el) {
		$el = $(el);

		bindEvents();

		console.info('Initialized global social content sharing scripts.');
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

let bindEvents = () => {
	$el.on('click', (e) => launchSocialPopup(e));
};

/**
 * @function launchSocialPopup
 * @description Init social share popups.
 */

let launchSocialPopup = (e) => {
	popup({
		event: e,
		specs: {
			width: parseInt(e.currentTarget.getAttribute('data-width')),
			height: parseInt(e.currentTarget.getAttribute('data-height')),
		},
	});
};

export default socialShare;
