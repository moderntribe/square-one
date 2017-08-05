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
	themeCSS: {
		files: [
			'<%= pkg._core_theme_assets_path %>/pcss/**/*.pcss',
			'!<%= pkg._core_theme_assets_path %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:theme',
			'postcss:themeLegacy',
		],
		options: defaultOpts,
	},

	themeAdmin: {
		files: [
			'<%= pkg._core_theme_assets_path %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:themeWPEditor',
			'postcss:themeWPLogin',
		],
		options: defaultOpts,
	},

	themeScripts: {
		files: [
			'<%= pkg._core_theme_assets_path %>/js/src/**/*.js',
		],
		tasks: [
			'webpack:themeDev',
		],
		options: defaultOpts,
	},

	themeTemplates: {
		files: [
			'<%= pkg._core_theme_path %>/**/*.php',
		],
		options: defaultOpts,
	},
};
