/**
 * @module
 * @description Module for primary navigation on the Square 1 demo site.
 **/

import delegate from 'delegate';
import * as tools from 'utils/tools';
import * as bodyLock from 'utils/dom/body-lock';
import hoverintent from 'hoverintent';
import globalState from '../config/state';

const el = {
	container: tools.getNodes('primary-nav-menu')[0],
};

const state = {
	open: false,
};

const options = {
	hoverIntent: {
		sensitivity: 9,
		interval: 25,
	},
};

const showMenu = () => {
	bodyLock.lock();
	el.siteHeader.style.transform = 'translateX(-300px)';
	el.siteMain.style.transform = 'translateX(-300px)';
	el.siteFooter.style.transform = 'translateX(-300px)';
	el.mobileTrigger.childNodes[1].classList.replace('icon-menu', 'icon-cross');
	state.open = true;
};

const hideMenu = () => {
	bodyLock.unlock();
	el.siteHeader.style.transform = 'translateX(0)';
	el.siteMain.style.transform = 'translateX(0)';
	el.siteFooter.style.transform = 'translateX(0)';
	el.mobileTrigger.childNodes[1].classList.replace('icon-cross', 'icon-menu');
	state.open = false;
};

const toggleMenu = (e) => {
	e.stopPropagation();
	if (state.open) {
		hideMenu();
		return;
	}

	showMenu();
};

const offMenuClick = (e) => {
	if (!state.open) {
		return;
	}
	if (e.target.id !== 'primary-nav' && !tools.closest(e.target, '[data-js="primary-nav"]')) {
		hideMenu();
	}
};

const handleResize = () => {
	if (globalState.v_width >= 768 && state.open) {
		hideMenu();
	}
};

const hoverIn = (e) => {
	const target = e.target;
	if (!target || !target.classList.contains('primary__action--has-children')) {
		return;
	}

	const parent = tools.closest(target, '.primary__list-item--depth-0');
	parent.classList.add('active-menu');
};

const hoverOut = (e) => {
	const target = e.target;
	if (!target) {
		return;
	}

	const parent = tools.closest(target, '.primary__list-item--depth-0');
	parent.classList.remove('active-menu');
};

const cacheElements = () => {
	el.mobileTrigger = tools.getNodes('trigger-mobile')[0];
	el.siteHeader = tools.getNodes('.site-header', false, document, true)[0];
	el.siteMain = tools.getNodes('main', false, document, true)[0];
	el.siteFooter = tools.getNodes('.site-footer', false, document, true)[0];
	el.parentMenuItems = tools.getNodes('.primary__list-item--depth-0', false, el.container, true);
};

const bindEvents = () => {
	delegate(el.siteHeader, '[data-js="trigger-mobile"]', 'click', toggleMenu);
	el.parentMenuItems.forEach(item => hoverintent(item, hoverIn, hoverOut).options(options.hoverIntent));

	document.addEventListener('click', offMenuClick);
	document.addEventListener('modern_tribe/resize_executed', handleResize);
};

const init = () => {
	if (!el.container) {
		return;
	}

	cacheElements();

	bindEvents();
};

export default init;
