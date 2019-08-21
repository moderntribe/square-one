/**
 * @module
 * @description JavaScript that drives Dialog
 */

import A11yDialog from 'mt-a11y-dialog';
import * as tools from 'utils/tools';

const el = {
	siteWrap: tools.getNodes('site-wrap')[0],
	container: tools.getNodes('c-dialog-trigger', true),
};

const instances = {
	dialogs: {},
};

const options = {
	dialog: {
		appendTarget: '',
		wrapperClasses: 'c-dialog',
		closeButtonClasses: 'c-dialog__close-button',
		trigger: '[data-js="c-dialog-trigger"]',
	},
};

/**
 * @function initSwiper
 * @description
 */

const initSwiper = (dialogEl) => {
	const gallery = tools.getNodes('c-slider', false, dialogEl)[0];
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = (instance) => {
	instance.on('show', initSwiper);
};

/**
 * @function getMainOptsForDialog
 * @description Get the main variable options for the dialog
 */

const getOptionsDialog = ( trigger ) => {
	const opts = options.dialog;
	if ( trigger.dataset.dialogOptions && tests.isJson( trigger.dataset.dialogOptions ) ) {
		Object.assign( opts, JSON.parse( trigger.dataset.dialogOptions ) );
	}
	return opts;
};

/**
 * @function initDialog
 * @description Initialize Dialog
 */

const initDialogs = () => {
	tools.getNodes('[data-js="c-dialog-trigger"]', true, document, true).forEach( (trigger) => {
		const dialogId = trigger.getAttribute('data-content');
		options.dialog.trigger = `[data-js="c-dialog-trigger"][data-content="${dialogId}"]`;
		instances.dialogs[dialogId] = new A11yDialog(getOptionsDialog(trigger));
		bindEvents(instances.dialogs[dialogId]);
	} );
};

/**
 * @function init
 * @description Kick off this modules functions.
 */

const init = () => {
	if (!el.container) {
		return;
	}

	initDialogs();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
