/**
 * @module
 * @description JavaScript that drives Dialog
 */

import A11yDialog from 'mt-a11y-dialog';
import * as tools from 'utils/tools';
import { trigger } from 'utils/events';

const el = {
	triggers: tools.getNodes( '[data-js="dialog-trigger"]', true, document, true ),
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
 * @function getMainOptsForDialog
 * @description Dialog specific options are put directly on the button as data-dialogOptions. This grabs them.
 * 
 * @param btn
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
    // Need to check for multiple trigger buttons
	el.triggers.forEach( ( btn ) => {
		const dialogId = btn.getAttribute( 'data-content' );
		// If this has multiple triggers, skip creating a new instance after the first one.
		if ( instances.dialogs[ dialogId ]  ) {
			return;
		}

        options.dialog.trigger = `[data-js="dialog-trigger"][data-content="${ dialogId }"]`;
        instances.dialogs[ dialogId ] = new A11yDialog( getOptionsDialog( btn ) );

        // This function will initialize the swiper slider when the dialog loads if it's not already initialized.
        instances.dialogs[ dialogId ].on('show', function () {
            trigger( { event: 'modern_tribe/component_dialog_rendered', native: false } );
        });
    } );
};

/**
 * @function init
 * @description Kick off this modules functions.
 */

const init = () => {
	if ( ! el.triggers.length ) {
		return;
    }

	initDialogs();

	console.info( 'SquareOne Theme : Initialized dialog scripts.' );
};

export default init;
