/**
 * @module
 * @description Javascript that drives the sitewide accordion widget. Uses lodash.
 */

import _ from 'lodash';

import { removeClass, addClass, hasClass } from '../utils/tools';
import { setAccActiveAttributes, setAccInactiveAttributes } from '../utils/dom/accessibility';
import scrollTo from '../utils/dom/scroll-to';

// setup shared variables

let pn = document.getElementById('panel-navigation');
let gs = TweenMax;
let options;

/**
 * @function _bindEvents
 * @description Bind the events for this module here.
 */

let _bindEvents = () => {

	$(options.el)
		.on('click', '.ac-header', (e) => _toggleItem(e));

};

/**
 * @function _closeOthers
 * @param {HTMLElement} row The domnode to map from.
 * @description Close the other accordion toggles.
 */

let _closeOthers = (row) => {

	gs.to(row.parentNode.querySelectorAll('.active .ac-content'), options.speed, { height: 0 });

	Array.prototype.forEach.call(row.parentNode.querySelectorAll('.active'), (row) => {
		removeClass(row, 'active');
		setAccInactiveAttributes(row.querySelectorAll('.ac-header')[0], row.querySelectorAll('.ac-content')[0]);
	});

};

/**
 * @function _setOffset
 * @description We have to account for scroll offset due to admin bar and maybe a fixed panel nav when scrolling
 */

let _setOffset = () => {

	options.offset = -10;

	if (hasClass(document.body, 'admin-bar')) {
		options.offset = options.offset - 40;
	}

	if (pn) {
		options.offset = options.offset - pn.offsetHeight;
	}

};

/**
 * @function _toggleItem
 * @param {Object} e The js event object.
 * @description Toggle the active accordion item using class methods.
 */

let _toggleItem = (e) => {

	let header = e.currentTarget;
	let content = header.nextElementSibling;

	if (hasClass(header.parentNode, 'active')) {

		removeClass(header.parentNode, 'active');

		setAccInactiveAttributes(header, content);

		gs.to(content, options.speed, {
			height: 0,
			onComplete: function () {
				$(document).trigger('modern_tribe/accordion_animated');
			},
		});

	} else {

		_closeOthers(header.parentNode);

		addClass(header.parentNode, 'active');

		setAccActiveAttributes(header, content);

		_setOffset();

		gs.set(content, { height: 'auto' });
		gs.from(content, options.speed, {
			height: 0,
			onComplete: function () {
				scrollTo({
					after_scroll: function () {
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
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

let init = (opts) => {

	options = _.assign({
		el: document.getElementsByClassName('widget-accordion'),
		speed: 0.3,
	}, opts);

	if (options.el.length) {

		_setOffset();
		_bindEvents();

		console.info('Initialized accordion widget class.');
	}
};

export default init;
