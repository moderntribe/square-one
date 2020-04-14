/**
 * @module
 * @exports plugins
 * @description Kicks in any third party plugins that operate on
 * a sitewide basis.
 */

// import gsap from 'gsap'; // uncomment to import gsap globally
import 'lazysizes';
import 'lazysizes/plugins/object-fit/ls.object-fit';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'lazysizes/plugins/respimg/ls.respimg';
import 'lazysizes/plugins/bgset/ls.bgset';

const plugins = () => {
	// initialize global external plugins here
};

export default plugins;
