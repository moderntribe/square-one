/**
 * @module
 * @description Base vendor module for the modern tribe libs js.
 */

import embeds from './embeds';
import forms from './forms';
import socialShare from './social-share';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	embeds();

	//forms();

	socialShare();
};

export default init;

