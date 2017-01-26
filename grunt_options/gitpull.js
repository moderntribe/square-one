/**
 *
 * Module: grunt-git
 * Documentation: https://www.npmjs.org/package/grunt-git
 *
 *
 */

module.exports = {

	options: {
		verbose: true,
	},

	staging: {
		options: {
			branch: 'staging',
		},
	},

	production: {
		options: {
			branch: 'production',
		},
	},
};
