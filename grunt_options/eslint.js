/**
 *
 * Module: grunt-eslint
 * Documentation: https://github.com/sindresorhus/grunt-eslint
 * Example:
 *
 */

module.exports = {
	dist: [
		'<%= pkg._core_theme_js_src_path %>/js/src/**/*.js',
		'<%= pkg._core_admin_js_src_path %>/js/src/**/*.js',
	],
};
