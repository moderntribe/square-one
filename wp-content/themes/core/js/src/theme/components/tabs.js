/**
 * @module
 * @description Javascript that drives the sitewide tabs widget.
 */

import delegate from 'delegate';

import * as tools from 'utils/tools';

const siteWrap = tools.getNodes('site-wrap')[0];


/**
 * @function resetCurrent
 * @param {HTMLElement} button Button clicked.
 * @description Resets the current button and content to inactive state
 */

const resetCurrent = (button) => {
	const tabList = button.parentNode;
	const container = tabList.parentNode;
	const currentActiveButton = tools.getNodes('.c-tab__button--active', false, tabList, true)[0];
	const currentActiveContent = tools.getNodes('.c-tab__content--active', false, container, true)[0];
	tools.removeClass(currentActiveButton, 'c-tab__button--active');
	tools.removeClass(currentActiveContent, 'c-tab__content--active');
	currentActiveButton.setAttribute('aria-selected', 'false');
	currentActiveContent.setAttribute('aria-hidden', 'true');
};

/**
 * @function setNewCurrent
 * @param {HTMLElement} button Button clicked.
 * @description Set tab button and related content to active state
 */

const setNewCurrent = (button) => {
	const tabList = button.parentNode;
	const container = tabList.parentNode;
	const nxtContentId = button.getAttribute('aria-controls');
	const nextActiveContent = tools.getNodes(`.c-tab__content[id="${nxtContentId}"]`, false, container, true)[0];
	button.setAttribute('aria-selected', 'true');
	tools.addClass(button, 'c-tab__button--active');
	nextActiveContent.setAttribute('aria-hidden', 'false');
	tools.addClass(nextActiveContent, 'c-tab__content--active');
};

/**
 * @function tabClick
 * @param {Object} e The js event object.
 * @description Toggle the active tab item using class methods.
 */

const tabClick = (e) => {
	const button = e.delegateTarget;
	resetCurrent(button);
	setNewCurrent(button);
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(siteWrap, '.c-tab__button', 'click', tabClick);
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	bindEvents();

	console.info('Square One FE: Initialized tabs component scripts.');
};

export default init;
