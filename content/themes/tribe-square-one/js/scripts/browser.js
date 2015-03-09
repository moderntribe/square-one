/**
 * @namespace modern_tribe.br
 * @desc modern_tribe.br is where we have some handy browser tests we shouldn't use but probably will.
 */

t.br = {
	chrome : !!window.chrome,
	firefox: typeof InstallTrigger !== 'undefined',
	ie     : /*@cc_on!@*/false || document.documentMode,
	legacy : false,
	ios    : !!navigator.userAgent.match(/(iPod|iPhone|iPad)/i),
	safari : Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0,
	opera  : !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0,
	os     : navigator.platform
};

