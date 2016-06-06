/**
 *
 * Module: grunt-eslint
 * Documentation: https://github.com/sindresorhus/grunt-eslint
 * Example:
 *
 */

module.exports = {
	dist: [
		'<%= pkg._corethemepath %>/js/src/**/*.js',
		'<%= pkg._corethemepath %>/js/vendor/tribe-libs/**/*.js',
	],
};
