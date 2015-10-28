/**
 *
 * Module: grunt-contrib-watch
 * Documentation: https://npmjs.org/package/grunt-contrib-watch
 *
 */

module.exports = {

	theme_css: {
		files: [
			'<%= pkg._themepath %>/scss/**/**/*.scss',
			'<%= pkg._themepath %>/scss/**/*.scss',
			'<%= pkg._themepath %>/scss/*.scss'
		],
		tasks: [
			'sass:theme',
			'combine_mq:theme',
			'postcss:theme_prefix',
			'clean:theme'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	theme_scripts: {
		files: [
			'<%= pkg._themepath %>/js/src/**/*.js'
		],
		tasks: [
			'webpack:themedev'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	theme_templates: {
		files: [
			'<%= pkg._themepath %>/**/*.php'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	}

};