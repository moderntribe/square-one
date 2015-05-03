/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	theme: [
		'<%= pkg._themepath %>/css/master-temp.css',
		'<%= pkg._themepath %>/css/admin/editor-style-temp.css',
		'<%= pkg._themepath %>/css/admin/login-temp.css'
	],

	thememincss: [
		'<%= pkg._themepath %>/css/dist/*.css',
		'<%= pkg._themepath %>/css/admin/dist/*.css'
	],

	thememinjs: [
		'<%= pkg._themepath %>/js/dist/*.js'
	]

};