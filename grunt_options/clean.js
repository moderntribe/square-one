/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {
	theme: [
		'<%= pkg._corethemepath %>/css/master-temp.css',
		'<%= pkg._corethemepath %>/css/print-temp.css',
	],

	theme_wp_editor: [
		'<%= pkg._corethemepath %>/css/admin/editor-style-temp.css',
	],

	theme_wp_login: [
		'<%= pkg._corethemepath %>/css/admin/login-temp.css',
	],

	theme_legacy: [
		'<%= pkg._corethemepath %>/css/legacy-temp.css',
	],

	theme_min_css: [
		'<%= pkg._corethemepath %>/css/dist/*.css',
		'<%= pkg._corethemepath %>/css/admin/dist/*.css',
	],

	theme_min_js: [
		'<%= pkg._corethemepath %>/js/dist/*.js',
	],
};