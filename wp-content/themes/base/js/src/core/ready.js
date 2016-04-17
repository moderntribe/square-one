/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

'use strict';

import _ from "lodash"; // you MUST do this in every module you use lodash in. A custom bundle of only the lodash
                        // you use will be built by babel. 

import resize from './resize';
import plugins from './plugins';
import viewport_dims from './viewport-dims';

import { on, ready } from '../utils/events';

/**
 * @function init
 * @description The core dispatcher for init across the codebase.
 */

let init = () => {

	// init external plugins

	plugins();

	// set initial states

	viewport_dims();

	// initialize global events

	bind_events();

	// initialize widgets


	// initialize the main scripts


	console.info( 'Initialized all javascript that targeted document ready.' );

};

/**
 * @function bind_events
 * @description Bind global event listeners here,
 */

let bind_events = () => {

	on( window, 'resize', _.debounce( resize, 200, false ) );

};

/**
 * @function dom_ready
 * @description Export our dom ready enabled init.
 */

let dom_ready = () => {

	ready( init );

};

export default dom_ready;

