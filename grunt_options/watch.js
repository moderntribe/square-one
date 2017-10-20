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
			'<%= pkg._core_theme_pcss_path %>**/*.pcss',
			'!<%= pkg._core_admin_pcss_path %>**/*.pcss',
		],
		tasks: [
			'postcss:theme',
			'postcss:themeLegacy',
		],
		options: defaultOpts,
	},

	themeLogin: {
		files: [
			'<%= pkg._core_admin_pcss_path %>login.pcss',
		],
		tasks: [
			'postcss:themeWPLogin',
		],
		options: defaultOpts,
	},

	themeEditor: {
		files: [
			'<%= pkg._core_admin_pcss_path %>editor-styles.pcss',
		],
		tasks: [
			'postcss:themeWPEditor',
		],
		options: defaultOpts,
	},

	themeAdmin: {
		files: [
			'<%= pkg._core_admin_pcss_path %>**/*.pcss',
			'!<%= pkg._core_admin_pcss_path %>editor-styles.pcss',
			'!<%= pkg._core_admin_pcss_path %>login.pcss',
		],
		tasks: [
			'postcss:themeWPAdmin',
		],
		options: defaultOpts,
	},

	themeScripts: {
		files: [
			'<%= pkg._core_theme_js_src_path %>**/*.js',
		],
		tasks: [
			'webpack:themeDev',
		],
		options: defaultOpts,
	},

	adminScripts: {
		files: [
			'<%= pkg._core_admin_js_src_path %>**/*.js',
		],
		tasks: [
			'webpack:adminDev',
		],
		options: defaultOpts,
	},

	utilScripts: {
		files: [
			'<%= pkg._core_theme_js_util_path %>**/*.js',
		],
		tasks: [
			'webpack:themeDev',
			'webpack:adminDev',
		],
		options: defaultOpts,
	},

	themeTemplates: {
		files: [
			'<%= pkg._core_theme_path %>/**/*.php',
			'<%= pkg._core_theme_path %>/**/*.twig',
		],
		options: defaultOpts,
	},
};
