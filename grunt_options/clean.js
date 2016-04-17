/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	theme: [
		'<%= pkg._basethemepath %>/css/master-temp.css',
		'<%= pkg._basethemepath %>/css/print-temp.css'
	],

	theme_wp_editor: [
		'<%= pkg._basethemepath %>/css/admin/editor-style-temp.css'
	],

	theme_wp_login: [
		'<%= pkg._basethemepath %>/css/admin/login-temp.css'
	],

	theme_legacy: [
		'<%= pkg._basethemepath %>/css/legacy-temp.css'
	],

	theme_min_css: [
		'<%= pkg._basethemepath %>/css/dist/*.css',
		'<%= pkg._basethemepath %>/css/admin/dist/*.css'
	],

	theme_min_js: [
		'<%= pkg._basethemepath %>/js/dist/*.js'
	]

};