/*
Element to slide gets the following CSS:
    max-height: 0;
    opacity: 0;
    overflow: hidden;
*/
import _ from 'lodash';

import getHiddenHeight from './get-hidden-height';

/**
 * Like jQuery's slideDown function - uses CSS3 transitions
 * @param  {Node} elem Element to show and hide
 * @param  {int} time Length of animation
 */
export const down = (elem, time = 400) => {
	const height = getHiddenHeight(elem);
	elem.style.transition = `max-height ${time}ms ease 0s`;
	_.delay(() => {
		elem.style.maxHeight = `${height}px`;
		elem.style.opacity = '1';
	}, 25);
	_.delay(() => {
		elem.style.maxHeight = 'none';
	}, (time + 25));
};

/**
 * Slide element up
 * @param  {Node} elem Element
 * @param  {int} time Length of animation
 */
export const up = (elem, time = 400) => {
	elem.style.maxHeight = `${elem.offsetHeight}px`;
	_.delay(() => {
		elem.style.maxHeight = '0';
	}, 25);
	_.delay(() => {
		elem.style.opacity = '0';
	}, (time + 25));
};
