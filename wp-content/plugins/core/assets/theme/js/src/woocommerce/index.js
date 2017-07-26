
/**
 * @function init
 * @description Kick off this modules functions
 */

import ui from './ui';
import cartActions from './cart-actions';
import checkoutActions from './checkout-actions';

const init = () => {
	ui();

	cartActions();

	checkoutActions();
};

export default init;
