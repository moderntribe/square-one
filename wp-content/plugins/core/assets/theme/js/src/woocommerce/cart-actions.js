/**
 * @module
 * @description Module handles some of the JS events that occur for the cart.
 */

import * as tools from '../utils/tools';
import state from '../config/state';
import scrollTo from '../../src/utils/dom/scroll-to';

const el = {
	container: document.getElementsByClassName('woocommerce-cart')[0],
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
 * @function updatedCartTotals
 * @description Handle updated cart totals event.
 */

const updatedCartTotals = () => {
	const notices = tools.getNodes('woocommerce-notice', true, el.container);

	// Only scroll back up top of there is a message
	if (notices.length) {
		scrollUp();
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	$(document.body).on('updated_cart_totals', updatedCartTotals);
};

/**
 * @function cartActions
 * @description Kick off this modules functions
 */

const cartActions = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	console.info('Initialized WC cart actions script.');
};

export default cartActions;
