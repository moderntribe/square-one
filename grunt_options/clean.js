/**
 *
 * Module: grunt-contrib-clean
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {
	coreIconsStart: [
		'<%= pkg._component_path %>/theme/icons/core',
		'<%= pkg._core_theme_assets_path %>/fonts/icons-core',
		'<%= pkg._core_theme_assets_path %>/pcss/base/_icons.pcss',
		'<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss',
	],

	coreIconsEnd: [
		'<%= pkg._component_path %>/core-icons.zip'
	],

	themeMinCSS: [
		'<%= pkg._core_theme_assets_path %>/css/dist/*.css',
		'<%= pkg._core_theme_assets_path %>/css/admin/dist/*.css',
	],

	themeMinJS: [
		'<%= pkg._core_theme_assets_path %>/js/dist/*.min.js',
	],

	themeMinVendorJS: [
		'<%= pkg._core_theme_assets_path %>/js/dist/vendorGlobal.min.js',
		'<%= pkg._core_theme_assets_path %>/js/dist/vendorWebpack.min.js'
	],
};
