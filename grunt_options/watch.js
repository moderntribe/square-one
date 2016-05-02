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
			'<%= pkg._corethemepath %>/pcss/**/**/*.pcss',
			'<%= pkg._corethemepath %>/pcss/**/*.pcss',
			'<%= pkg._corethemepath %>/pcss/*.pcss',
			'!<%= pkg._corethemepath %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:theme',
			'postcss:theme_legacy',
		],
		options: defaultOpts,
	},

	theme_admin: {
		files: [
			'<%= pkg._corethemepath %>/pcss/admin/*.pcss',
		],
		tasks: [
			'postcss:theme_editor',
			'postcss:theme_login',
		],
		options: defaultOpts,
	},

	theme_scripts: {
		files: [
			'<%= pkg._corethemepath %>/js/src/**/*.js',
		],
		tasks: [
			'eslint:dist',
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
