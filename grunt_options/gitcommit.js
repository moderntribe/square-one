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

	deploy: {
		options: {
			message: 'Deployment <%= grunt.template.today("isoDateTime") %>',
			allowEmpty: true,
			cwd: '<%= pkg._deploypath %>'
		}
	}
};