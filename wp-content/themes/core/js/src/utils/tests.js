/**
 * @module
 * @description Some handy test for common issues.
 */

const isJson = (str) => {

	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}

	return true;

};

const canLocalStore = () => {

	let mod;
	let result;
	try {
		mod = new Date;
		localStorage.setItem(mod, mod.toString());
		result = localStorage.getItem(mod) === mod.toString();
		localStorage.removeItem(mod);
		return result;
	} catch (_error) {
		console.error('This browser doesn\'t support local storage or is not allowing writing to it.');
	}

};

const browserTests = () => ({
	android: /Android/i.test(window.navigator.userAgent) && /Mobile/i.test(window.navigator.userAgent),
	chrome: !!window.chrome,
	firefox: typeof InstallTrigger !== 'undefined',
	ie: /*@cc_on!@*/false || document.documentMode,
	ios: !!navigator.userAgent.match(/(iPod|iPhone|iPad)/i),
	iosMobile: !!navigator.userAgent.match(/(iPod|iPhone)/i),
	safari: Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0,
	opera: !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0,
	os: navigator.platform,
});

export { isJson, canLocalStore, browserTests };
