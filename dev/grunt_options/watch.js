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

	themehandlebars: {
		files  : [
			'<%= pkg._themepath %>/js/helpers/**/*.js',
			'<%= pkg._themepath %>/js/templates/**/*.handlebars',
			'<%= pkg._themepath %>/js/templates/*.handlebars'
		],
		tasks  : [
			'handlebars:theme',
			'concat:handlebars'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	themescripts   : {
		files  : [
			'<%= pkg._themepath %>/js/scripts/browser.js',
			'<%= pkg._themepath %>/js/scripts/data.js',
			'<%= pkg._themepath %>/js/scripts/elements.js',
			'<%= pkg._themepath %>/js/scripts/keys.js',
			'<%= pkg._themepath %>/js/scripts/plugins.js',
			'<%= pkg._themepath %>/js/scripts/functions.js',
			'<%= pkg._themepath %>/js/scripts/namespaces.js',
			'<%= pkg._themepath %>/js/scripts/modules/**/*.js',
			'<%= pkg._themepath %>/js/scripts/models/**/*.js',
			'<%= pkg._themepath %>/js/scripts/views/**/*.js',
			'<%= pkg._themepath %>/js/scripts/collections/**/*.js',
			'<%= pkg._themepath %>/js/scripts/router.js',
			'!<%= pkg._themepath %>/js/scripts/modules/example.js',
			'<%= pkg._themepath %>/js/scripts/options.js',
			'<%= pkg._themepath %>/js/scripts/state.js',
			'<%= pkg._themepath %>/js/scripts/supports.js',
			'<%= pkg._themepath %>/js/scripts/tests.js',
			'<%= pkg._themepath %>/js/scripts/init.js'
		],
		tasks  : [
			'concat:scripts',
			'babel:theme',
			'clean:theme'
		],
		options: {
			spawn     : false,
			livereload: true
		}
	},

	themelibs      : {
		files  : [
			'<%= pkg._themepath %>/js/libs/**/*.js'
		],
		tasks  : [
			'concat:libs'
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