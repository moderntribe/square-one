import * as tests from '../tests';

const browser = tests.browserTests();
const scroller = browser.ie || browser.firefox || browser.safari || browser.ios || ( browser.chrome && ! browser.edge ) ? document.documentElement : document.body;
let locked = false;
let scroll = 0;
let scrollBehavior = '';

/**
 * @function isLocked
 * @description Returns state
 */

const isLocked = () => locked;

/**
 * @function lock
 * @description Lock the body at a particular position and prevent scroll,
 * use margin to simulate original scroll position.
 */

const lock = () => {
	scroll = scroller.scrollTop;
	scrollBehavior = document.documentElement.style.scrollBehavior;
	locked = true;

	document.documentElement.style.scrollBehavior = 'auto';
	document.body.style.position = 'fixed';
	document.body.style.marginTop = `-${ scroll }px`;
};

/**
 * @function unlock
 * @description Unlock the body and return it to its actual scroll position.
 */

const unlock = () => {
	document.body.style.position = 'static';
	document.body.style.marginTop = '0px';

	scroller.scrollTop = scroll;
	document.documentElement.style.scrollBehavior = scrollBehavior;
	locked = false;
};

export {
	lock,
	unlock,
	isLocked,
};
