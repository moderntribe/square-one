/**
 * @module
 * @description Base module to kick off all component js
 */

import accordion from './accordion';
import slider from './slider';
import tabs from './tabs';
import dialog from './dialog';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	accordion();
	slider();
	tabs();
	dialog();
};

export default init;
