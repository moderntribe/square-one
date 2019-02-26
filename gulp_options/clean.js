const del = require('del');
const pkg = require('../package.json');

module.exports = {
	coreIconsStart() {
		return del([
			`${pkg._component_path}/theme/icons/core`,
			`${pkg._core_theme_fonts_path}icons-core`,
			`${pkg._core_theme_pcss_path}base/_icons.pcss`,
			`${pkg._core_theme_pcss_path}utilities/variables/_icons.pcss`,
		]);
	},
	coreIconsEnd() {
		return del([
			`${pkg._component_path}/core-icons.zip`,
		]);
	},
	themeMinCSS() {
		return del([
			`${pkg._core_theme_css_dist_path}*.css`,
			`${pkg._core_admin_css_dist_path}*.css`,
		]);
	},
	themeMinJS() {
		return del([
			`${pkg._core_theme_js_dist_path}*.min.js`,
			`${pkg._core_admin_js_dist_path}*.min.js`,
			`${pkg._core_theme_js_dist_path}*.*.min.js`,
			`${pkg._core_admin_js_dist_path}*.*.min.js`,
		]);
	},
	themeMinVendorJS() {
		return del([
			`${pkg._core_theme_js_dist_path}vendorGlobal.min.js`,
			`${pkg._core_theme_js_dist_path}vendorWebpack.min.js`,
		]);
	},
};
