/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	theme_min_css: [
		'<%= pkg._corethemepath %>/css/dist/*.css',
		'<%= pkg._corethemepath %>/css/admin/dist/*.css',
	],

	theme_min_js: [
		'<%= pkg._corethemepath %>/js/dist/*.js',
	],
};
