'use strict';

/**
 * @function get_hidden_height
 * @since 1.0
 * @desc get_hidden_height gets the height of hidden objects.
 */

const get_hidden_height = ( el ) => {

	let zindex = getComputedStyle( el )[ 'z-index' ],
		pos = getComputedStyle( el )[ 'position' ],
		t_height = 0;

	el.style.visibility = 'hidden';
	el.style.height = 'auto';
	el.style.position = 'fixed';
	el.style.zIndex = - 1;

	t_height = el.offsetHeight;

	el.style.visibility = 'visible';
	el.style.height = 0;
	el.style.position = pos;
	el.style.zIndex = zindex;

	return t_height;

};

export default get_hidden_height;