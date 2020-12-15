/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

import _ from 'lodash';

// you MUST do this in every module you use lodash in.
// A custom bundle of only the lodash you use will be built by babel.

import resize from './resize';
import plugins from './plugins';
import viewportDims from './viewport-dims';
import editor from '../editor';
import blockStyles from './block-styles';

import { on, ready } from 'utils/events';

/**
 * @function bindEvents
 * @description Bind global event listeners here,
 */

const bindEvents = () => {
	on( window, 'resize', _.debounce( resize, 200, false ) );
};

/**
 * @function init
 * @description The core dispatcher for init across the codebase.
 */

const init = () => {
	// init external plugins

	plugins();

	// set initial states

	viewportDims();

	// initialize global events

	bindEvents();

	// initialize the main scripts

	editor();

	// removes core block styles as needed
	blockStyles();

	console.info( 'SquareOne Admin: Initialized all javascript that targeted document ready.' );
};

/**
 * @function domReady
 * @description Export our dom ready enabled init.
 */

const domReady = () => {
	ready( init );
};

export default domReady;

