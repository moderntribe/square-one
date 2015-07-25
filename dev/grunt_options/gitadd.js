/**
 *
 * Module: grunt-git
 * Documentation: https://www.npmjs.org/package/grunt-git
 *
 *
 */

module.exports = {

	options: {
		verbose:true
	},

	buildprocess: {
		options: {
			all: true
		}
	},

	deploy: {
		options: {
			cwd: '<%= pkg._deploypath %>',
			all: true
		}
	}
};