/* -----------------------------------------------------------------------------
 *
 * Component: Social Share
 *
 * ----------------------------------------------------------------------------- */

/**
 * @module
 * @description JavaScript specific to the social sharing of content.
 */

import delegate from 'delegate';
import * as tools from 'utils/tools';
import popup from 'utils/dom/popup';

const el = {
	container: tools.getNodes( 'social-share-networks' )[ 0 ],
};

/**
 * @function launchSocialPopup
 * @description Init social share popups.
 */

const launchSocialPopup = ( e ) => {
	popup( {
		event: e,
		url: e.delegateTarget.href,
		specs: {
			width: parseInt( e.delegateTarget.getAttribute( 'data-width' ), 10 ),
			height: parseInt( e.delegateTarget.getAttribute( 'data-height' ), 10 ),
		},
	} );
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

const bindEvents = () => {
	delegate( el.container, '[data-js="social-share-popup"]', 'click', launchSocialPopup );
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const socialShare = () => {
	if ( ! el.container ) {
		return;
	}

	bindEvents();

	console.info( 'SquareOne Theme: Initialized global social content sharing scripts.' );
};

export default socialShare;
