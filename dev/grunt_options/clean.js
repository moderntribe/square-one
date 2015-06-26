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
		'<%= pkg._themepath %>/css/admin/login-temp.css',
		'<%= pkg._themepath %>/js/libs.processed.js',
		'<%= pkg._themepath %>/js/scripts.processed.js',
		'<%= pkg._themepath %>/js/scripts-es6.js'
	],

	thememincss: [
		'<%= pkg._themepath %>/css/dist/*.css',
		'<%= pkg._themepath %>/css/admin/dist/*.css'
	],

	thememinjs: [
		'<%= pkg._themepath %>/js/dist/*.js'
	],

	deploy: {
		src: [
			'<%= pkg._deploypath %>/*',
			'!<%= pkg._deploypath %>/.git'
		]
	}
};