/**
 * @module
 * @exports init
 * @description Initializes theme component JS in the block editor context.
 */

import slider from 'components/slider';

const init = () => {
	slider();
	console.info( 'SquareOne Admin: Initialized all components.' );
};

export default init;
