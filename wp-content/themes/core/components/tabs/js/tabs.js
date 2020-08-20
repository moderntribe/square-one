/* -----------------------------------------------------------------------------
 *
 * Component: Tabs
 *
 * ----------------------------------------------------------------------------- */

/**
 * @module
 * @description Javascript that drives the site wide tabs widget.
 */

import delegate from 'delegate';

import state from 'config/state';
import { on } from 'utils/events';
import * as tools from 'utils/tools';

const componentState = {
	isMobile: state.is_mobile,
};

const el = {
	tabsComponents: tools.getNodes( 'c-tabs', true ),
	tablistDropdowns: tools.getNodes( 'c-tabs__tablist-dropdown', true ),
	mobileToggles: tools.getNodes( 'c-tabs__tablist-toggle', true ),
};

/**
 * Actions required to show a particular tab.
 *
 * @param tab
 * @param trigger
 */
const showTabPanel = ( tab, trigger ) => {
	tab.removeAttribute( 'hidden' );
	trigger.setAttribute( 'aria-selected', 'true' );
	trigger.removeAttribute( 'tabindex' );
};

/**
 * Actions required to hide a particular tab.
 *
 * @param tab
 * @param trigger
 */
const hideTabPanel = ( tab, trigger ) => {
	tab.setAttribute( 'hidden', true );
	trigger.setAttribute( 'aria-selected', 'false' );
	trigger.setAttribute( 'tabindex', '-1' );
};

/**
 * Update the mobile toggle button label, if available.
 *
 * @param targetTabButton
 * @param container
 */
const updateMobileTriggerLabel = ( targetTabButton, container ) => {
	const mobileToggle = container.querySelector( '[data-js="c-tabs__tablist-toggle"]' );

	if ( mobileToggle ) {
		mobileToggle.innerHTML = targetTabButton.innerHTML;
	}
};

/**
 * Handles switching the selected tab.
 *
 * @param targetTabButton
 */
const switchTabs = ( targetTabButton ) => {
	const container = tools.closest( targetTabButton, '[data-js="c-tabs"]' );
	const tabs = tools.getNodes( '[role="tabpanel"]', true, container, true );
	const selectedTabId = targetTabButton.getAttribute( 'aria-controls' );

	if ( ! selectedTabId ) {
		return;
	}

	tabs.forEach( ( tab ) => {
		const tabButton = container.querySelector( `#${ tab.getAttribute( 'aria-labelledby' ) }` );
		tab.id === selectedTabId ? showTabPanel( tab, tabButton ) : hideTabPanel( tab, tabButton );
	} );

	updateMobileTriggerLabel( targetTabButton, container );
};

/**
 * Handle tab trigger click events.
 *
 * @param e
 * @returns {*}
 */
const handleTabClick = ( e ) => e.target.getAttribute( 'aria-selected' ) === 'false' ? switchTabs( e.target ) : false;

/**
 * Handle keyboard events within the tablist.
 *
 * @param e
 */
const handleTabListKeyDown = ( e ) => {
	const container = tools.closest( e.target, '[data-js="c-tabs"]' );
	const tabList = container.querySelector( '[role="tablist"]' );

	const keyEvents = [
		'ArrowLeft',
		'ArrowRight',
	];

	if ( tabList.getAttribute( 'aria-orientation' ) === 'vertical' ) {
		keyEvents.push( 'ArrowUp' );
		keyEvents.push( 'ArrowDown' );
	}

	if ( ! keyEvents.includes( e.key ) ) {
		return;
	}

	const tabButtons = tools.getNodes( '[role="tab"]', true, container, true );
	const currentIndex = tabButtons.indexOf( document.activeElement );
	const lastIndex = tabButtons.length - 1;

	if ( e.key === 'ArrowRight' || e.key === 'ArrowDown' ) {
		// If the current trigger is the last, then cycle to the first. Otherwise, go to the next.
		const nextIndex = currentIndex + 1 > lastIndex ? 0 : currentIndex + 1;
		switchTabs( tabButtons[ nextIndex ] );
		tabButtons[ nextIndex ].focus();
	}

	if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp' ) {
		// If the current trigger is the first, then cycle to the last. Otherwise, go to the previous.
		const prevIndex = currentIndex - 1 < 0 ? lastIndex : currentIndex - 1;
		switchTabs( tabButtons[ prevIndex ] );
		tabButtons[ prevIndex ].focus();
	}
};

/**
 * Shows the mobile drop-down.
 *
 * @param toggle
 */
const showTabsDropDown = ( toggle ) => {
	const dropDown = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
	dropDown.style.display = 'block';
	toggle.setAttribute( 'aria-expanded', 'true' );
};

/**
 * Hides the mobile drop-down.
 *
 * @param toggle
 */
const hideTabsDropDown = ( toggle ) => {
	const dropDown = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
	dropDown.style.display = 'none';
	toggle.setAttribute( 'aria-expanded', 'false' );
};

/**
 * Handles the mobile drop-down toggle.
 *
 * @param e
 */
const handleTabsMobileToggle = ( e ) => {
	if ( state.is_desktop ) {
		return;
	}

	e.target.getAttribute( 'aria-expanded' ) === 'false' ? showTabsDropDown( e.target ) : hideTabsDropDown( e.target );
};

/**
 * Hides the mobile drop-down on click outside events.
 *
 * @param e
 */
const handleTabsMobileClickOut = ( e ) => {
	if ( state.is_desktop ) {
		return;
	}

	const tabList = tools.closest( e.target, '[data-js="c-tabs__tablist-wrapper"]' );

	// If the clicked element is not inside a tablist, then just hide them all.
	if ( ! tabList ) {
		el.mobileToggles.forEach( toggle => hideTabsDropDown( toggle ) );
	}
};

/**
 * Hides the mobile drop-down on Esc & Tab-outside events.
 *
 * @param e
 */
const handleTabsMobileKeyDown = ( e ) => {
	const keyEvents = [ 'Tab', 'Escape' ];

	if ( ! keyEvents.includes( e.key ) || state.is_desktop ) {
		return;
	}

	e.preventDefault();

	const tabList = tools.closest( e.target, '[role="tablist"]' );

	// If the current element is inside a tablist, then hide it and refocus on the drop-down toggle.
	if ( e.key === 'Escape' && tabList ) {
		const toggle = tabList.querySelector( '[data-js="c-tabs__tablist-toggle"]' );

		if ( toggle ) {
			hideTabsDropDown( toggle );
			toggle.focus();
		}
	}

	// If the current element is not inside a tablist, then just hide them all.
	if ( e.key === 'Tab' && ! tabList ) {
		el.mobileToggles.forEach( toggle => hideTabsDropDown( toggle ) );
	}
};

/**
 * Update tablist scroller for overflow and add/remove the relevant class.
 */
const handleScrollableTabsFit = () => {
	el.tablistDropdowns.forEach( dropdown => {
		const container = tools.closest( dropdown, '[data-js="c-tabs"]' );
		if ( container.getAttribute( 'data-layout' ) === 'horizontal' ) {
			dropdown.classList.toggle( 'is-scrollable', dropdown.scrollWidth > dropdown.clientWidth );
		}
	} );
};

/**
 * Handle browser resize events.
 */
const handleResize = () => {
	// If the component state is mobile, but the viewport is desktop, then show all the tablists and update the component state.
	if ( componentState.isMobile && state.is_desktop ) {
		el.mobileToggles.forEach( toggle => showTabsDropDown( toggle ) );
		componentState.isMobile = state.is_mobile;
	}

	// If the component state is NOT mobile, but the viewport state is, then hide all the tablists and update the component state.
	if ( ! componentState.isMobile && ! state.is_desktop ) {
		el.mobileToggles.forEach( toggle => hideTabsDropDown( toggle ) );
		componentState.isMobile = state.is_mobile;
	}

	handleScrollableTabsFit();
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate( el.tabsComponents, '[role="tab"]', 'click', handleTabClick );
	delegate( el.tabsComponents, '[role="tablist"]', 'keydown', handleTabListKeyDown );
	delegate( el.tabsComponents, '[data-js="c-tabs__tablist-toggle"]', 'click', handleTabsMobileToggle );

	on( document, 'click', handleTabsMobileClickOut );
	on( document, 'keyup', handleTabsMobileKeyDown );
	on( document, 'modern_tribe/resize_executed', handleResize );
};

const cacheElements = () => {};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */
const init = () => {
	if ( ! el.tabsComponents ) {
		return;
	}

	cacheElements();
	bindEvents();
	handleScrollableTabsFit();

	console.info( 'SquareOne Theme: Initialized tabs component scripts.' );
};

export default init;
