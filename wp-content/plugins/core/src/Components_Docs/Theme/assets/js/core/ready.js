/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

import { ready } from 'utils/events';

import componentsPreview from '../modules/components-preview';

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

	// Components preview submission ajax stuff
	componentsPreview();

	console.info('Square One Components Docs: Initialized all javascript that targeted document ready.');
};

/**
 * @function setupEnivironment
 * @description
 */

const setupEnvironment = () => {
	if (browserSupportsAllFeatures()) {
		init();
	}
};

/**
 * @function domReady
 * @description Export our dom ready enabled init.
 */

const domReady = () => {
	ready(setupEnvironment);
};

export default domReady;

