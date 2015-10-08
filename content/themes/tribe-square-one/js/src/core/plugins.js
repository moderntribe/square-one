/**
 * @module
 * @exports plugins
 * @description Kicks in any third party plugins that operate ona sitewide basis.
 */

'use strict';

import Fastclick from 'fastclick';
import gsap from 'gsap';
import Mtlazy from 'mtlazy';

let plugins = () => {

	// initialize global external plugins here

	// lazyloading images, general. Use class 'lazyload' and attributes 'data-src' and 'data-retina-src'.

	new Mtlazy({
		fade: true
	});

	// remove click delay on mobiles

	Fastclick.attach( document.body );


};

export default plugins;