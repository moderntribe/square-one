import * as tests from '../tests';

let browser = tests.browser_tests();
let scroll = 0;
let scroller = browser.edge || browser.ie || browser.firefox ? document.documentElement : document.body;

/**
 * @function lock
 * @description Lock the body at a particular position and prevent scroll,
 * use margin to simulate original scroll position.
 */

let lock = () => {

	scroll = scroller.scrollTop;
	document.body.style.position = 'fixed';
	document.body.style.marginTop = `-${scroll}px`;
};

/**
 * @function unlock
 * @description Unlock the body and return it to its actual scroll position.
 */

let unlock = () => {

	document.body.style.marginTop = `0px`;
	document.body.style.position = 'relative';
	scroller.scrollTop = scroll;
};

export { lock, unlock }