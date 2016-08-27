/**
 * @module
 * @description JavaScript specific to the social sharing of content.
 */

import popup from '../utils/dom/popup';

const el = document.getElementsByClassName('social-share-popup');
let $el;

/**
 * @function launchSocialPopup
 * @description Init social share popups.
 */

const launchSocialPopup = (e) => {
	popup({
		event: e,
		specs: {
			width: parseInt(e.currentTarget.getAttribute('data-width'), 10),
			height: parseInt(e.currentTarget.getAttribute('data-height'), 10),
		},
	});
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

const bindEvents = () => {
	$el.on('click', (e) => launchSocialPopup(e));
};

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

export default socialShare;
