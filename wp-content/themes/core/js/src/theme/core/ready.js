/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

import _ from 'lodash';
import { on, ready } from 'utils/events';
import applyBrowserClasses from 'utils/dom/apply-browser-classes';

// you MUST do this in every module you use lodash in.
// A custom bundle of only the lodash you use will be built by babel.

import resize from './resize';
import plugins from './plugins';
import viewportDims from './viewport-dims';

import modules from '../modules/index';

import single from '../single/index';

import components from '../components/index';

import site from '../site/index';

/**
 * @function bindEvents
 * @description Bind global event listeners here,
 */

const bindEvents = () => {
	on(window, 'resize', _.debounce(resize, 200, false));
};

/**
 * @function init
 * @description The core dispatcher for init across the codebase.
 */

const init = () => {
	// apply browser classes

	applyBrowserClasses();

	// init external plugins

	plugins();

	// set initial states

	viewportDims();

	// initialize global events

	bindEvents();

	// initialize the module scripts

	modules();

	// initialize the main scripts

	components();
	site();
	single();

	console.info('Square One FE: Initialized all javascript that targeted document ready.');
};

/**
 * @function domReady
 * @description Export our dom ready enabled init.
 */

const domReady = () => {
	ready(init);
};

export default domReady;

