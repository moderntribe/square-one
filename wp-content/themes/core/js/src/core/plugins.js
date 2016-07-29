/**
 * @module
 * @exports plugins
 * @description Kicks in any third party plugins that operate on
 * a sitewide basis.
 */

import Fastclick from 'fastclick';

// import gsap from 'gsap'; // uncomment to import gsap globally

const plugins = () => {
	// initialize global external plugins here

	// remove click delay on mobiles

	Fastclick.attach(document.body);
};

export default plugins;
