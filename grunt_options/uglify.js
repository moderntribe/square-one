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
			'<%= pkg._core_theme_js_dist_path %>vendorGlobal.min.js': [
				'<%= pkg._core_theme_js_vendor_path %>polyfill.js',
				'<%= pkg._core_theme_js_vendor_path %>globals.js',
				'<%= pkg._core_theme_js_vendor_path %>ls.object-fit.js',
				'<%= pkg._core_theme_js_vendor_path %>ls.parent-fit.js',
				'<%= pkg._core_theme_js_vendor_path %>ls.respimg.js',
				'<%= pkg._core_theme_js_vendor_path %>ls.bgset.js',
				'<%= pkg._core_theme_js_vendor_path %>lazysizes.js',
			],
		},
	},
};
