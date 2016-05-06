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
	corePHP: {
		files: [
			'<%= pkg._corethemepath %>/**/*.php',
		],
		options: defaultOpts,
	},

	themeCSS: {
		files: [
			'<%= pkg._corethemepath %>/pcss/**/**/*.pcss',
			'<%= pkg._corethemepath %>/pcss/**/*.pcss',
			'<%= pkg._corethemepath %>/pcss/*.pcss',
			'!<%= pkg._corethemepath %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:theme',
			'postcss:themeLegacy',
		],
		options: defaultOpts,
	},

	themeAdmin: {
		files: [
			'<%= pkg._corethemepath %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:themeWPEditor',
			'postcss:themeWPLogin',
		],
		options: defaultOpts,
	},

	themeScripts: {
		files: [
			'<%= pkg._corethemepath %>/js/src/**/*.js',
		],
		tasks: [
			'eslint:dist',
			'webpack:themeDev',
		],
		options: defaultOpts,
	},

	themeTemplates: {
		files: [
			'<%= pkg._corethemepath %>/**/*.php',
		],
		options: defaultOpts,
	},
};
