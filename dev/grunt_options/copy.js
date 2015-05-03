/**
 *
 * Module: grunt-contrib-copy
 * Documentation: https://github.com/gruntjs/grunt-contrib-copy
 * Example:
 *
 */

module.exports = {
	theme: {
		files: [
			{
				expand: true,
				flatten: true,
				src: ['<%= pkg._npmpath %>/babel-core/browser-polyfill.js'],
				dest: '<%= pkg._themepath %>/js/'
			}
		]
	}
};
