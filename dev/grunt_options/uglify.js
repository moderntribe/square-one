/**
 *
 * Module: grunt-contrib-uglify
 * Documentation: https://npmjs.org/package/grunt-contrib-uglify
 *
 */
module.exports = {

	thememin: {
		options: {
			banner: '/* Square One: JS Master - <%= grunt.template.today("mm-dd-yyyy") %> */\n',
			sourceMap: false,
			compress: {
				drop_console: true
			}
		},
		files: {
			'<%= pkg._themepath %>/js/dist/master.min.js' : [
				'<%= pkg._themepath %>/js/vendor/browser-polyfill.js',
				'<%= pkg._themepath %>/js/dist/scripts.js'
			]
		}
	}

};