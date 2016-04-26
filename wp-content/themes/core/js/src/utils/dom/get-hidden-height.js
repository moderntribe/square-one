
/**
 * @function getHiddenHeight
 * @since 1.0
 * @desc gets the height of hidden objects.
 */

const getHiddenHeight = (el) => {
	const zindex = getComputedStyle(el)['z-index'];
	const pos = getComputedStyle(el).position;
	const element = el;

	element.style.visibility = 'hidden';
	element.style.height = 'auto';
	element.style.position = 'fixed';
	element.style.zIndex = -1;

	const tHeight = element.offsetHeight;

	element.style.visibility = 'visible';
	element.style.height = 0;
	element.style.position = pos;
	element.style.zIndex = zindex;

	return tHeight;
};

export default getHiddenHeight;
