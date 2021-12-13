/**
 * @module
 * @exports init
 * @description Initializes theme component JS in the block editor context.
 */

import sectionNav from 'components/section_nav';
import slider from 'components/slider';

const initComponents = () => {
	sectionNav();
	slider();
};

const bindEvents = () => {
	if ( window.acf ) {
		window.acf.addAction( 'render_block_preview', initComponents );
	}
};

const init = () => {
	bindEvents();
	initComponents();
	console.info( 'SquareOne Admin: Initialized all components.' );
};

export default init;
