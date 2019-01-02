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
			'setPHPConstant',
		],
		options: defaultOpts,
	},

	themeLogin: {
		files: [
			'<%= pkg._core_admin_pcss_path %>login.pcss',
		],
		tasks: [
			'postcss:themeWPLogin',
			'setPHPConstant',
		],
		options: defaultOpts,
	},

	themeEditor: {
		files: [
			'<%= pkg._core_admin_pcss_path %>editor-styles.pcss',
		],
		tasks: [
			'postcss:themeWPEditor',
			'setPHPConstant',
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
			'setPHPConstant',
		],
		options: defaultOpts,
	},

	themeScripts: {
		files: [
			'<%= pkg._core_theme_js_src_path %>**/*.js',
		],
		tasks: [
			'webpack:themeDev',
			'setPHPConstant',
		],
		options: defaultOpts,
	},

	adminScripts: {
		files: [
			'<%= pkg._core_admin_js_src_path %>**/*.js',
		],
		tasks: [
			'webpack:adminDev',
			'setPHPConstant',
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
			'setPHPConstant',
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
