/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	theme: [
		'<%= pkg._themepath %>/css/master-temp.css',
		'<%= pkg._themepath %>/css/print-temp.css'
	],

	theme_wp_editor: [
		'<%= pkg._themepath %>/css/admin/editor-style-temp.css'
	],

	theme_wp_login: [
		'<%= pkg._themepath %>/css/admin/login-temp.css'
	],

	theme_legacy: [
		'<%= pkg._themepath %>/css/legacy-temp.css'
	],

	theme_min_css: [
		'<%= pkg._themepath %>/css/dist/*.css',
		'<%= pkg._themepath %>/css/admin/dist/*.css'
	],

	theme_min_js: [
		'<%= pkg._themepath %>/js/dist/*.js'
	]

};