/**
 *
 * Module: grunt-contrib-watch
 * Documentation: https://npmjs.org/package/grunt-contrib-watch
 *
 */

var default_opts = {
	spawn     : false,
	livereload: true
};

module.exports = {

	core_php: {
		files  : [
			'<%= pkg._basethemepath %>/**/*.php'
		],
		options: default_opts
	},

	theme_css: {
		files  : [
			'<%= pkg._basethemepath %>/scss/**/**/*.scss',
			'<%= pkg._basethemepath %>/scss/**/*.scss',
			'<%= pkg._basethemepath %>/scss/*.scss'
		],
		tasks  : [
			'sass:theme',
			'combine_mq:theme',
			'postcss:theme_prefix',
			'clean:theme'
		],
		options: default_opts
	},

	theme_scripts: {
		files  : [
			'<%= pkg._basethemepath %>/js/src/**/*.js'
		],
		tasks  : [
			'webpack:themedev'
		],
		options: default_opts
	},

	theme_templates: {
		files  : [
			'<%= pkg._basethemepath %>/**/*.php'
		],
		options: default_opts
	}

};