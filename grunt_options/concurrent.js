/**
 *
 * Module: grunt-concurrent
 * Documentation: https://github.com/sindresorhus/grunt-concurrent
 * Example:
 *
 */

module.exports = {
	options: {
		logConcurrentOutput: true,
	},

	preflight: [
		[
			'eslint',
		],
		[
			'mochacli:all',
		],
		[
			'clean:theme_min_css',
		],
	],

	dist: [
		[
			'postcss:theme',
			'postcss:theme_min',
			'header:theme_print',
			'header:theme',
		],
		[
			'postcss:theme_wp_editor',
			'postcss:theme_wp_editor_min',
			'header:theme_wp_editor',
		],
		[
			'postcss:theme_wp_login',
			'postcss:theme_wp_login_min',
			'header:theme_wp_login',
		],
		[
			'postcss:theme_legacy',
			'postcss:theme_legacy_min',
			'header:theme_legacy',
		],
		[
			'clean:theme_min_js',
			'copy:themejs',
			'webpack:themeprod',
			'uglify:theme_min',
		],
		'setPHPConstant',
	],
};
