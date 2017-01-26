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

	staging: {
		options: {
			tag:'staging/<%= grunt.template.today("yyyy.mm.dd.HH.MM") %>'
		}
	},

	production: {
		options: {
			tag:'release/<%= grunt.template.today("yyyy.mm.dd.HH.MM") %>'
		}
	}
};