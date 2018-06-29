/**
 * @module
 * @description Javascript that drives the sitewide tabs widget.
 */

import delegate from 'delegate';

import * as tools from 'utils/tools';

const siteWrap = tools.getNodes('site-wrap')[0];
const tabs = tools.getNodes('c-tabs');

/**
 * @function resetCurrent
 * @param {HTMLElement} button Button clicked.
 * @description Resets the current button and content to inactive state
 */

const resetCurrent = (button) => {
	const container = tools.closest(button, '[data-js="c-tabs"]');
	const activeBtnClass = container.getAttribute('data-button-active-class');
	const activeContentClass = container.getAttribute('data-content-active-class');
	const currentActiveButton = tools.getNodes(`.${activeBtnClass}`, false, container, true)[0];
	const currentActiveContent = tools.getNodes(`.${activeContentClass}`, false, container, true)[0];
	if (!currentActiveButton || !currentActiveContent) {
		return;
	}
	tools.removeClass(currentActiveButton, activeBtnClass);
	tools.removeClass(currentActiveContent, activeContentClass);
	currentActiveButton.setAttribute('aria-selected', 'false');
	currentActiveContent.setAttribute('aria-hidden', 'true');
};

/**
 * @function setNewCurrent
 * @param {HTMLElement} button Button clicked.
 * @description Set tab button and related content to active state
 */

const setNewCurrent = (button) => {
	const container = tools.closest(button, '[data-js="c-tabs"]');
	const activeBtnClass = container.getAttribute('data-button-active-class');
	const activeContentClass = container.getAttribute('data-content-active-class');
	const nxtContentId = button.getAttribute('aria-controls');
	const nextActiveContent = tools.getNodes(`.c-tab__content[id="${nxtContentId}"]`, false, container, true)[0];
	if (!nextActiveContent) {
		return;
	}
	button.setAttribute('aria-selected', 'true');
	tools.addClass(button, activeBtnClass);
	nextActiveContent.setAttribute('aria-hidden', 'false');
	tools.addClass(nextActiveContent, activeContentClass);
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
 * @function focusRow
 * @description Focus row from index and row index
 */

const focusRow = (index, rowIndex) => {
	const buttonSelector = `[data-js="panel"][data-index="${index}"] [data-js="c-tab__button"][data-row-index="${rowIndex}"]`;
	const activeButton = tools.getNodes(buttonSelector, false, siteWrap, true)[0];
	if (activeButton) {
		resetCurrent(activeButton);
		setNewCurrent(activeButton);
	}
};

/**
 * @function repeaterChangeHandler
 * @description Responds to panel live updating.
 */

const repeaterChangeHandler = (e) => {
	focusRow(e.detail.index, e.detail.rowIndex);
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(siteWrap, '.c-tab__button', 'click', tabClick);
	document.addEventListener('modular_content/repeater_row_activated', repeaterChangeHandler);
	document.addEventListener('modular_content/repeater_row_added', repeaterChangeHandler);
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	if (!tabs) {
		return;
	}

	bindEvents();

	console.info('Square One FE: Initialized tabs component scripts.');
};

export default init;
