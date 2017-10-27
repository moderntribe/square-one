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
			'postcss:themeLint',
		],
		[
			'clean:themeMinCSS',
		],
	],

	dist: [
		[
			'postcss:theme',
			'postcss:themeMin',
			'header:themePrint',
			'header:theme',
		],
		[
			'postcss:themeWPAdmin',
			'postcss:themeWPAdminMin',
		],
		[
			'postcss:themeWPEditor',
			'postcss:themeWPEditorMin',
			'header:themeWPEditor',
		],
		[
			'postcss:themeWPLogin',
			'postcss:themeWPLoginMin',
			'header:themeWPLogin',
		],
		[
			'postcss:themeLegacy',
			'postcss:themeLegacyMin',
			'header:themeLegacy',
		],
		[
			'clean:themeMinJS',
			'copy:themeJS',
			'webpack',
			'uglify:themeMin',
			'concat:themeMinVendors',
			'clean:themeMinVendorJS',
		],
		'setPHPConstant',
	],
};
