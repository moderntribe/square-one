/**
 * @module
 * @description JavaScript that drives Dialog
 */

import Dialog from 'mt-a11y-dialog';
import * as tools from 'utils/tools';

const el = {
	wrap: tools.getNodes( 'site-wrap', true ),
	container: tools.getNodes( 'c-dialog-trigger', true ),
};

const instances = {};

const options = {
	dialog: {
		appendTarget: el.wrap,
		effect: 'fade',
		effectSpeed: 600,
		trigger: el.container,
	},
};

/**
 * @function initDialog
 * @description Initialize Dialog
 */

const initDialog = () => {
	instances.dialog = new Dialog( options.dialog );
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	if ( ! el.container.length ) {
		return;
	}

	initDialog();
	bindEvents();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
