/**
 *
 * Module: grunt-contrib-concat
 * Documentation: https://npmjs.org/package/grunt-contrib-concat
 *
 */

module.exports = {
	themeMinVendors: {
		src: [
			'<%= pkg._core_theme_assets_path %>/js/dist/vendorGlobal.min.js',
			'<%= pkg._core_theme_assets_path %>/js/dist/vendorWebpack.min.js'
		],
		dest: '<%= pkg._core_theme_assets_path %>/js/dist/vendor.min.js',
	}
};
