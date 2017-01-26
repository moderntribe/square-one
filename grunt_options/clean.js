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
		'<%= pkg._corethemepath %>/js/dist/*.js',
	],

	deploy: {
		src: [
			'<%= pkg._deploypath %>/*',
			'!<%= pkg._deploypath %>/.git'
		]
	}
};
