/**
 * @module
 * @description Base module to kick off all component js
 */

import accordion from './accordion';
import slider from './slider';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	accordion();
	slider();
};

export default init;
