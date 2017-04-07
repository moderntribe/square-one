/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {
	themeMinCSS: [
		'<%= pkg._corethemepath %>/css/dist/*.css',
		'<%= pkg._corethemepath %>/css/admin/dist/*.css',
	],

	themeMinJS: [
		'<%= pkg._corethemepath %>/js/dist/*.min.js',
	],

	themeMinVendorJS: [
		'<%= pkg._corethemepath %>/js/dist/vendorGlobal.min.js',
		'<%= pkg._corethemepath %>/js/dist/vendorWebpack.min.js'
	],
};
