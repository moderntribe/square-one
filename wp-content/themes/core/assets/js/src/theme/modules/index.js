/**
 * @module
 * @description Base vendor module for the modern tribe libs js.
 */

import embeds from './embeds';
import socialShare from './social-share';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	embeds();

	socialShare();
};

export default init;

