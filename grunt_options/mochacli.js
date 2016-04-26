/**
 *
 * Module: grunt-mocha-cli
 * Documentation: https://www.npmjs.org/package/grunt-git
 *
 *
 */

module.exports = {

	options: {
		compilers: ['js:babel-core/register'],
		require: ['expect'],
		reporter: 'nyan',
		bail: true,
		timeout: 10000,
	},

	all: [
		'<%= pkg._corethemepath %>/tests/**/*.js',
	],
};
