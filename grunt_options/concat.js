/**
 *
 * Module: grunt-contrib-concat
 * Documentation: https://npmjs.org/package/grunt-contrib-concat
 *
 */

module.exports = {
	themeMinVendors: {
		src: [
			'<%= pkg._core_theme_js_dist_path %>vendorGlobal.min.js',
			'<%= pkg._core_theme_js_dist_path %>vendorWebpack.min.js',
		],
		dest: '<%= pkg._core_theme_js_dist_path %>vendor.min.js',
	},
};
