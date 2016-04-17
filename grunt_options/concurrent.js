/**
 *
 * Module: grunt-concurrent
 * Documentation: https://github.com/sindresorhus/grunt-concurrent
 * Example:
 *
 */

module.exports = {

	options: {
		logConcurrentOutput: true
	},

	dist:[
		[
			'sass:theme',
			'postcss:theme_prefix',
			'postcss:theme_min',
			'header:theme_print',
			'header:theme',
			'clean:theme'
		],
		[
			'sass:theme_wp_editor',
			'postcss:theme_wp_editor_prefix',
			'postcss:theme_wp_editor_min',
			'header:theme_wp_editor',
			'clean:theme_wp_editor'
		],
		[
			'sass:theme_wp_login',
			'postcss:theme_wp_login_prefix',
			'postcss:theme_wp_login_min',
			'header:theme_wp_login',
			'clean:theme_wp_login'
		],
		[
			'sass:theme_legacy',
			'postcss:theme_legacy_prefix',
			'postcss:theme_legacy_min',
			'header:theme_legacy',
			'clean:theme_legacy'
		],
		[
			'clean:theme_min_js', 
			'copy:themejs',
			'webpack:themeprod',
			'uglify:theme_min'
		],
		'setPHPConstant'
	]

};