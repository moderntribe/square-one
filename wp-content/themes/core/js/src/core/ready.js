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

import { on, ready } from '../utils/events';

/**
 * @function init
 * @description The core dispatcher for init across the codebase.
 */

let init = () => {

	// init external plugins

	plugins();

	// set initial states

	viewportDims();

	// initialize global events

	bindEvents();

	// initialize widgets

	// initialize the main scripts

	console.info('Initialized all javascript that targeted document ready.');
};

/**
 * @function bind_events
 * @description Bind global event listeners here,
 */

let bindEvents = () => {

	on(window, 'resize', _.debounce(resize, 200, false));

};

/**
 * @function dom_ready
 * @description Export our dom ready enabled init.
 */

let domReady = () => {

	ready(init);

};

export default domReady;

