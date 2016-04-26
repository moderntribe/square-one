/**
 *
 * Module: grunt-contrib-watch
 * Documentation: https://npmjs.org/package/grunt-contrib-watch
 *
 */

var defaultOpts = {
	spawn: false,
	livereload: true,
};

module.exports = {
	core_php: {
		files: [
			'<%= pkg._corethemepath %>/**/*.php',
		],
		options: defaultOpts,
	},

	theme_css: {
		files: [
			'<%= pkg._corethemepath %>/scss/**/**/*.scss',
			'<%= pkg._corethemepath %>/scss/**/*.scss',
			'<%= pkg._corethemepath %>/scss/*.scss',
		],
		tasks: [
			'sass:theme',
			'combine_mq:theme',
			'postcss:theme_prefix',
			'clean:theme',
		],
		options: defaultOpts,
	},

	theme_scripts: {
		files: [
			'<%= pkg._corethemepath %>/js/src/**/*.js',
		],
		tasks: [
			'webpack:themedev',
		],
		options: defaultOpts,
	},

	theme_templates: {
		files: [
			'<%= pkg._corethemepath %>/**/*.php',
		],
		options: defaultOpts,
	},
};
