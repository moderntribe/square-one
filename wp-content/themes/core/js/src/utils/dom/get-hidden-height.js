
/**
 * @function getHiddenHeight
 * @since 1.0
 * @desc gets the height of hidden objects.
 */

const getHiddenHeight = (el) => {

	let zindex = getComputedStyle(el)['z-index'];
	let pos = getComputedStyle(el).position;

	el.style.visibility = 'hidden';
	el.style.height = 'auto';
	el.style.position = 'fixed';
	el.style.zIndex = -1;

	let tHeight = el.offsetHeight;

	el.style.visibility = 'visible';
	el.style.height = 0;
	el.style.position = pos;
	el.style.zIndex = zindex;

	return tHeight;

};

export default getHiddenHeight;
