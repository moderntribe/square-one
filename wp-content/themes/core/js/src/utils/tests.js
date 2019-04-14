/**
 * @module
 * @description Some handy test for common issues.
 */

const isJson = ( str ) => {
	try {
		JSON.parse( str );
	} catch ( e ) {
		return false;
	}

	return true;
};

const canLocalStore = () => {
	let mod;
	let result = false;

	try {
		mod = new Date();
		localStorage.setItem( mod, mod.toString() );

		result = localStorage.getItem( mod ) === mod.toString();
		localStorage.removeItem( mod );
		return result;
	} catch ( _error ) {
		return result;
	}
};

const android = /(android)/i.test( navigator.userAgent );
const chrome = !! window.chrome;
const firefox = typeof InstallTrigger !== 'undefined';
const ie = /* @cc_on!@ */ false || document.documentMode || false;
const edge = ! ie && !! window.StyleMedia;
const ios = !! navigator.userAgent.match( /(iPod|iPhone|iPad)/i );
const iosMobile = !! navigator.userAgent.match( /(iPod|iPhone)/i );
const opera = !! window.opera || navigator.userAgent.indexOf( ' OPR/' ) >= 0;
const safari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || !chrome && !opera && window.webkitAudioContext !== 'undefined'; // eslint-disable-line
const os = navigator.platform;

/**
 * do not change to arrow function until testing dependencies are updated beyond the following reported issue
 * https://github.com/facebook/jest/issues/5001
 */
function browserTests() {
	return {
		android,
		chrome,
		edge,
		firefox,
		ie,
		ios,
		iosMobile,
		opera,
		safari,
		os,
	};
}

export {
	isJson,
	canLocalStore,
	browserTests,
};
