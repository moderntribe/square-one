/**
 * @module
 * @description A vanilla js scrollspy
 */

import { trigger } from '../events';
import { convertElements, isNodelist } from '../tools';

import _ from 'lodash';

const scrollspy = (options) => {

	let defaults = {
		min: 0,
		max: 0,
		debounce: 50,
		elements: null,
		mode: 'vertical',
		buffer: 0,
		container: window,
		onEnter: options.onEnter ? options.onEnter : [],
		onLeave: options.onLeave ? options.onLeave : [],
		onTick: options.onTick ? options.onTick : [],
	};

	let opts = Object.assign(defaults, options);

	if (!opts.elements) {
		return;
	}

	let elements = [];

	if (isNodelist(opts.elements)) {
		elements = convertElements(opts.elements);
	} else {
		elements.push(opts.elements);
	}

	let leaves;
	let o = opts;
	let mode = o.mode;
	let buffer = o.buffer;
	let enters = leaves = 0;
	let inside = false;

	elements.forEach((element) => {

		o.container.addEventListener('scroll', _.debounce(function () {

			let position = {
				top: element.scrollTop,
				left: element.scrollLeft,
			};

			let xy = (mode === 'vertical') ? position.top + buffer : position.left + buffer;
			let max = o.max;
			let min = o.min;

			/* fix max */
			if (_.isFunction(o.max)) {
				max = o.max();
			}

			/* fix max */
			if (_.isFunction(o.min)) {
				min = o.min();
			}

			if (parseInt(max) === 0) {
				max = (mode === 'vertical') ? o.container.offsetHeight : o.container.offsetWidth + element.offsetWidth;
			}

			/* if we have reached the minimum bound but are below the max ... */
			if (xy >= min && xy <= max) {
				/* trigger enter event */
				if (!inside) {
					inside = true;
					enters++;

					/* fire enter event */
					trigger({
						el: element,
						event: 'scrollEnter',
						native: false,
						data: {
							position: position,
						},
					});
					if (_.isFunction(o.onEnter)) {
						o.onEnter(element, position);
					}

				}

				/* trigger tick event */
				trigger({
					el: element,
					event: 'scrollTick',
					native: false,
					data: {
						position: position,
						inside: inside,
						enters: enters,
						leaves: leaves,
					},
				});
				if (_.isFunction(o.onTick)) {
					o.onTick(element, position, inside, enters, leaves);
				}
			} else {

				if (inside) {
					inside = false;
					leaves++;

					trigger({
						el: element,
						event: 'scrollLeave',
						native: false,
						data: {
							position: position,
							leaves: leaves,
						},
					});

					if (_.isFunction(o.onLeave)) {
						o.onLeave(element, position);
					}
				}
			}
		}, o.debounce, false));

	});
};

export default scrollspy;
