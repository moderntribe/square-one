/**
 * @module
 * @description Base module for the Square 1 demo site js.
 */

import navigation from './navigation';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	navigation();

	console.info('Square One FE: Initialized demo site javascript.');
};

export default init;
