/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	theme: [
		'css/master-temp.css',
		'css/print-temp.css'
	],

	theme_wp_editor: [
		'css/admin/editor-style-temp.css'
	],

	theme_wp_login: [
		'css/admin/login-temp.css'
	],

	theme_legacy: [
		'css/legacy-temp.css'
	],

	theme_min_css: [
		'css/dist/*.css',
		'css/admin/dist/*.css'
	],

	theme_min_js: [
		'js/dist/*.js'
	]

};