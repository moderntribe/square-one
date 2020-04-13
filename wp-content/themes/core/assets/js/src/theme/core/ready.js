/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

import _ from 'lodash';
import { on, ready } from 'utils/events';
import * as tests from 'utils/tests';
import applyBrowserClasses from 'utils/dom/apply-browser-classes';
// @EXAMPLE_REACT_APP

// import * as tools from 'utils/tools';
// import { HMR_DEV } from 'config/wp-settings';

// you MUST do this in every module you use lodash in.
// A custom bundle of only the lodash you use will be built by babel.

import resize from './resize';
import plugins from './plugins';
import viewportDims from './viewport-dims';

import components from './components';
import integrations from './integrations';
import modules from '../modules/index';

import single from '../single/index';

// @EXAMPLE_REACT_APP

// const el = {
// 	exampleAppRoot: tools.getNodes( 'example-app' )[ 0 ],
// };

/**
 * @function bindEvents
 * @description Bind global event listeners here,
 */

const bindEvents = () => {
	on( window, 'resize', _.debounce( resize, 200, false ) );
};

/**
 * @function browserSupportsAllFeatures
 * @description Add feature detects here like window.IntersectionObserver || window.Fetch etc
 */

const browserSupportsAllFeatures = () =>
	window.IntersectionObserver;

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

	single();
	components();
	integrations();

	// @EXAMPLE_REACT_APP (Make sure to include the wrapping if block for ALL react apps

	// #if INCLUDEREACT
	// if ( el.exampleAppRoot && ! HMR_DEV ) {
	// 	import( 'Example' /* webpackChunkName:"example" */ );
	// }
	// #endif

	// if ( tests.supportsWorkers() ) {
	// 	import( '../service-worker-init/index' /* webpackChunkName:"service-worker-init" */ ).then( ( module ) => {
	// 		module.default();
	// 	} );
	// }

	console.info( 'Square One FE: Initialized all javascript that targeted document ready.' );
};

/**
 * @function setupEnivironment
 * @description
 */

const setupEnvironment = () => {
	if ( browserSupportsAllFeatures() ) {
		init();
		return;
	}
	import( './polyfills' /* webpackChunkName:"polyfills" */ ).then( () => init() );
};

/**
 * @function domReady
 * @description Export our dom ready enabled init.
 */

const domReady = () => {
	ready( setupEnvironment );
};

export default domReady;
