/**
 * @module
 * @exports init
 * @description Initializes all components found in the components directory of the theme
 */

import accordion from 'components/accordion';
import slider from 'components/slider';
import tabs from 'components/tabs';

const init = () => {
	accordion();
	slider();
	tabs();

	console.info( 'SquareOne FE: Initialized all components.' );
};

export default init;
