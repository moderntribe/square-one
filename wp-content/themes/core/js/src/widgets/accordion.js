/**
 * @module
 * @description Javascript that drives the sitewide accordion widget. Uses lodash.
 */

import _ from 'lodash';

import { removeClass, addClass, hasClass } from '../utils/tools';
import { setAccActiveAttributes, setAccInactiveAttributes } from '../utils/dom/accessibility';
import scrollTo from '../utils/dom/scroll-to';

// setup shared variables

/* global TweenMax */

const pn = document.getElementById('panel-navigation');
const gs = TweenMax;
let options;

/**
 * @function closeOthers
 * @param {HTMLElement} row The domnode to map from.
 * @description Close the other accordion toggles.
 */

const closeOthers = (row) => {
	gs.to(row.parentNode.querySelectorAll('.active .ac-content'), options.speed, { height: 0 });

	Array.prototype.forEach.call(row.parentNode.querySelectorAll('.active'), (childRow) => {
		removeClass(childRow, 'active');
		setAccInactiveAttributes(childRow.querySelectorAll('.ac-header')[0], childRow.querySelectorAll('.ac-content')[0]);
	});
};

/**
 * @function setOffset
 * @description We have to account for scroll offset due to admin bar and maybe a fixed panel nav when scrolling
 */

const setOffset = () => {
	options.offset = -10;

	if (hasClass(document.body, 'admin-bar')) {
		options.offset = options.offset - 40;
	}

	if (pn) {
		options.offset = options.offset - pn.offsetHeight;
	}
};

/**
 * @function toggleItem
 * @param {Object} e The js event object.
 * @description Toggle the active accordion item using class methods.
 */

const toggleItem = (e) => {
	const header = e.currentTarget;
	const content = header.nextElementSibling;

	if (hasClass(header.parentNode, 'active')) {
		removeClass(header.parentNode, 'active');
		setAccInactiveAttributes(header, content);

		gs.to(content, options.speed, {
			height: 0,
			onComplete: () => {
				$(document).trigger('modern_tribe/accordion_animated');
			},
		});
	} else {
		closeOthers(header.parentNode);
		addClass(header.parentNode, 'active');
		setAccActiveAttributes(header, content);
		setOffset();

		gs.set(content, { height: 'auto' });
		gs.from(content, options.speed, {
			height: 0,
			onComplete: () => {
				scrollTo({
					after_scroll: () => {
						$(document).trigger('modern_tribe/accordion_animated');
					},

					offset: options.offset,
					duration: 300,
					$target: $(header.parentNode),
				});
			},
		});
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	$(options.el)
		.on('click', '.ac-header', (e) => toggleItem(e));
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = (opts) => {
	options = _.assign({
		el: document.getElementsByClassName('widget-accordion'),
		speed: 0.3,
	}, opts);

	if (options.el.length) {
		setOffset();
		bindEvents();

		console.info('Initialized accordion widget class.');
	}
};

export default init;
