/**
 *
 * Module: grunt-contrib-uglify
 * Documentation: https://npmjs.org/package/grunt-contrib-uglify
 *
 */

module.exports = {
	themeMin: {
		options: {
			banner: '/* Core: JS Master */\n',
			sourceMap: false,
			compress: {
				drop_console: true,
			},
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/js/dist/vendorGlobal.min.js': [
				'<%= pkg._core_theme_assets_path %>/js/vendor/polyfill.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/globals.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/ls.object-fit.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/ls.parent-fit.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/ls.respimg.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/ls.bgset.js',
				'<%= pkg._core_theme_assets_path %>/js/vendor/lazysizes.js',
			],
		},
	},
};
