/**
 * @module
 * @exports init
 * @description Initializes all integrations found in the components directory of the theme that you select here
 */

import gravityForms from 'integrations/gravity-forms';

const init = () => {
	gravityForms();

	console.info( 'SquareOne FE: Initialized all integrations.' );
};

export default init;
