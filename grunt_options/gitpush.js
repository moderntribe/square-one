/**
 *
 * Module: grunt-git
 * Documentation: https://www.npmjs.org/package/grunt-git
 *
 *
 */

module.exports = {

	options: {
		verbose: true
	},

	staging: {
		options: {
			remote: '<%= pkg._dev_git_repo %>',
			branch: 'staging:staging'
		}
	},

	production: {
		options: {
			remote: '<%= pkg._dev_git_repo %>',
			branch: 'production:production'
		}
	},

	tags: {
		options: {
			remote: '<%= pkg._dev_git_repo %>',
			tags: true
		}
	},
};