/**
 * @module
 * @description JavaScript that drives Dialog
 */

import A11yDialog from 'mt-a11y-dialog';
import * as tools from 'utils/tools';
import * as tests from 'utils/tests';
import { trigger } from 'utils/events';

const el = {
	containers: tools.getNodes('[data-js="c-dialog-trigger"]', true, document, true),
};

const instances = {
	dialogs: {},
};

const options = {
	dialog: {
		appendTarget: '',
		wrapperClasses: 'c-dialog',
		closeButtonClasses: 'svgicon svgicon-close c-dialog__close-button',
	},
};

/**
 * @function initSwiper
 * @description
 */

const initSwiper = (dialogEl) => {
	const gallery = tools.getNodes('c-slider', false, dialogEl)[0];
	if (!gallery) {
		return;
	}
	trigger( { event: 'modern_tribe/component_dialog_rendered', native: false } );
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = (instance) => {
	instance.on('render', initSwiper);
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
	el.containers.forEach( (trigger) => {
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
	if (!el.containers.length) {
		return;
	}

	initDialogs();

	console.info( 'Modern Tribe FE: Initialized dialog scripts.' );
};

export default init;
