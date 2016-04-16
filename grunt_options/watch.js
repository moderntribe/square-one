/**
 *
 * Module: grunt-contrib-watch
 * Documentation: https://npmjs.org/package/grunt-contrib-watch
 *
 */

module.exports = {

	theme_css: {
		files  : [
			'scss/**/**/*.scss',
			'scss/**/*.scss',
			'scss/*.scss'
		],
		tasks  : [
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
		files  : [
			'js/src/**/*.js'
		],
		tasks  : [
			'webpack:dev'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	theme_templates: {
		files  : [
			'**/*.php'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	}

};