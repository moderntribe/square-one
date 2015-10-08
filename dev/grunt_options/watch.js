/**
 *
 * Module: grunt-contrib-watch
 * Documentation: https://npmjs.org/package/grunt-contrib-watch
 *
 */

module.exports = {

	themecss: {
		files: [
			'<%= pkg._themepath %>/scss/**/**/*.scss',
			'<%= pkg._themepath %>/scss/**/*.scss',
			'<%= pkg._themepath %>/scss/*.scss'
		],
		tasks: [
			'sass:theme',
			'combine_mq:theme',
			'autoprefixer:theme',
			'clean:theme'
		],
		options: {
			spawn: false,
			livereload: true
		}
	},

	themescripts   : {
		files  : [
			'<%= pkg._themepath %>/js/src/**/*.js'
		],
		tasks  : [
			'webpack:themedev'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	themetemplates : {
		files  : [
			'<%= pkg._themepath %>/**/*.php'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	}

};