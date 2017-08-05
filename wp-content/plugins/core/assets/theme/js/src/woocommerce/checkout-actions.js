/**
 * @module
 * @description Module handles some of the JS events that occur for the checkout.
 */

import state from '../config/state';
import scrollTo from '../../src/utils/dom/scroll-to';

const el = {
	container: document.getElementsByClassName('woocommerce-checkout')[0],
};

/**
 * @function scrollUp
 * @description Scroll back up top to cart for message udpates.
 */

const scrollUp = () => {
	scrollTo({
		duration: 500,
		offset: -state.header_offset - 25,
		$target: $('div.woocommerce'),
	});
};

/**
 * @function updatedCheckout
 * @description Handle updated checkout event.
 */

const updatedCheckout = () => {
	// Only scroll back up top of there is a message
	if ($('.woocommerce-notice--error').length) {
		scrollUp();
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	$(document.body).on('updated_checkout', updatedCheckout);

	$(document.body).on('checkout_error', updatedCheckout);
};

/**
 * @function checkoutActions
 * @description Kick off this modules functions
 */

const checkoutActions = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	console.info('Initialized WC checkout actions script.');
};

export default checkoutActions;
