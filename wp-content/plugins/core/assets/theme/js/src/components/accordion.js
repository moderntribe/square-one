/**
 * @module
 * @description Javascript that drives the sitewide accordion widget. Uses lodash.
 */

import _ from 'lodash';
import delegate from 'delegate';

import { setAccActiveAttributes, setAccInactiveAttributes } from '../utils/dom/accessibility';
import scrollTo from '../utils/dom/scroll-to';
import * as slide from '../utils/dom/slide';
import * as tools from '../utils/tools';
import * as events from '../utils/events';

// setup shared variables

const pn = document.getElementById('panel-navigation');
let options;

/**
 * @function closeOthers
 * @param {HTMLElement} row The domnode to map from.
 * @description Close the other accordion toggles.
 */

const closeOthers = (row) => {
	tools.getNodes('.active .c-accordion__content', true, row.parentNode, true).forEach(accordion => slide.up(accordion, options.speed));
	tools.getNodes('.active', true, row.parentNode, true).forEach((childRow) => {
		tools.removeClass(childRow, 'active');
		setAccInactiveAttributes(childRow.querySelectorAll('.c-accordion__header')[0], childRow.querySelectorAll('.c-accordion__content')[0]);
	});
};

/**
 * @function setOffset
 * @description We have to account for scroll offset due to admin bar and maybe a fixed panel nav when scrolling
 */

const setOffset = () => {
	options.offset = -20;

	if (tools.hasClass(document.body, 'admin-bar')) {
		options.offset = -20 - 40;
	}

	if (pn) {
		options.offset = -10 - pn.offsetHeight;
	}
};

/**
 * @function openAccordion
 * @description Toggle the accordion open
 */

const openAccordion = (header, content) => {
	closeOthers(header.parentNode);
	tools.addClass(header.parentNode, 'active');
	setAccActiveAttributes(header, content);
	setOffset();

	slide.down(content, options.speed);
	_.delay(() => {
		scrollTo({
			after_scroll: () => {
				events.trigger({
					event: 'modern_tribe/accordion_animated',
					native: false,
				});
			},

			offset: options.offset,
			duration: 300,
			$target: $(header.parentNode),
		});
	}, options.speed);
};

/**
 * @function closeAccordion
 * @description Toggle the accordion closed
 */

const closeAccordion = (header, content) => {
	tools.removeClass(header.parentNode, 'active');
	setAccInactiveAttributes(header, content);
	slide.up(content, options.speed);
	_.delay(() => {
		events.trigger({
			event: 'modern_tribe/accordion_animated',
			native: false,
		});
	}, options.speed);
};

/**
 * @function toggleItem
 * @param {Object} e The js event object.
 * @description Toggle the active accordion item using class methods.
 */

const toggleItem = (e) => {
	const header = e.delegateTarget;
	const content = header.nextElementSibling;

	if (tools.hasClass(header.parentNode, 'active')) {
		closeAccordion(header, content);
	} else {
		openAccordion(header, content);
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	options.el.forEach(accordion => delegate(accordion, '.c-accordion__header', 'click', toggleItem));
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = (opts) => {
	options = Object.assign({
		el: tools.getNodes('c-accordion', true),
		speed: 300,
	}, opts);

	if (options.el.length) {
		setOffset();
		bindEvents();

		console.info('Modern Tribe FE: Initialized accordion components.');
	}
};

export default init;
