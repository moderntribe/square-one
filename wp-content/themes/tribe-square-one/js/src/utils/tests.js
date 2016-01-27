/**
 * @module
 * @description Some handy test for common issues.
 */

'use strict';

let is_json = ( str ) => {

	try {
		JSON.parse( str );
	} catch ( e ) {
		return false;
	}
	return true;

};

let can_local_store = () => {

	let mod, result;
	try {
		mod = new Date;
		localStorage.setItem( mod, mod.toString() );
		result = localStorage.getItem( mod ) === mod.toString();
		localStorage.removeItem( mod );
		return result;
	} catch ( _error ) {
		console.error( 'This browser doesn\'t support local storage or is not allowing writing to it.' );
	}

};

let is_external_link = ( url ) => {

	let match = url.match( /^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/ );
	if ( typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol ) {
		return true;
	}
	if ( typeof match[2] === "string" && match[2].length > 0 && match[2].replace( new RegExp( ":(" + {
				"http:" : 80,
				"https:": 443
			}[location.protocol] + ")?$" ), "" ) !== location.host ) {
		return true;
	}
	return false;

};

let browser_tests = () => {
	return {
		android  : /Android/i.test(window.navigator.userAgent) && /Mobile/i.test(window.navigator.userAgent),
		chrome   : !!window.chrome,
		firefox  : typeof InstallTrigger !== 'undefined',
		ie       : /*@cc_on!@*/false || document.documentMode,
		ios      : !!navigator.userAgent.match(/(iPod|iPhone|iPad)/i),
		iosMobile: !!navigator.userAgent.match(/(iPod|iPhone)/i),
		safari   : Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0,
		opera    : !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0,
		os       : navigator.platform
	};
};

export { is_json, can_local_store, is_external_link, browser_tests }