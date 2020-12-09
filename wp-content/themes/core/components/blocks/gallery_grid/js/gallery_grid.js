/**
 * @module
 * @description JavaScript that drives Dialog
 */

import A11yDialog from 'mt-a11y-dialog';
import * as tools from 'utils/tools';
import { trigger } from 'utils/events';

const el = {
	containers: tools.getNodes( '[data-js="dialog-trigger"]', true, document, true ),
};

const instances = {
	dialogs: {},
};

const options = {
	dialog: {
		appendTarget: '[data-js="site-wrap"]',
		wrapperClasses: 'c-dialog',
		closeButtonClasses: 'icon icon-close c-dialog__close-button',
	},
};

/**
 * @function initSwiper
 * @description
 */

const initSwiper = ( dialogEl ) => {
	const gallery = tools.getNodes( 'c-slider', false, dialogEl )[ 0 ];
	if ( ! gallery ) {
		return;
	}
	trigger( { event: 'modern_tribe/component_dialog_rendered', native: false } );
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = ( instance ) => {
	instance.on( 'render', initSwiper );
};

/**
 * @function getMainOptsForDialog
 * @description Get the main variable options for the dialog
 */

const getOptionsDialog = ( btn ) => {
	const opts = options.dialog;
	if ( btn.dataset.dialogOptions ) {
		Object.assign( opts, JSON.parse( btn.dataset.dialogOptions ) );
	}
	return opts;
};

/**
 * @function initDialog
 * @description Initialize Dialog
 */

const initDialogs = () => {
	el.containers.forEach( ( btn ) => {
		const dialogId = btn.getAttribute( 'data-content' );
		// If this has multiple triggers, skip creating a new instance after the first one.
		if ( instances.dialogs[ dialogId ]  ) {
			return;
		}

		options.dialog.trigger = `[data-js="dialog-trigger"][data-content="${ dialogId }"]`;
		instances.dialogs[ dialogId ] = new A11yDialog( getOptionsDialog( btn ) );
		bindEvents( instances.dialogs[ dialogId ] );
	} );
};

/**
 * @function init
 * @description Kick off this modules functions.
 */

const init = () => {
	if ( ! el.containers.length ) {
		return;
	}

	initSwiper();
	initDialogs();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
