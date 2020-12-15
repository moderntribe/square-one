const del = require( 'del' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStart() {
		return del( [
			`${ pkg.square1.paths.component }/theme/icons/core`,
			`${ pkg.square1.paths.core_theme_fonts }icons-core`,
			`${ pkg.square1.paths.core_theme_pcss }icons/icons.pcss`,
			`${ pkg.square1.paths.core_theme_pcss }icons/_variables.pcss`,
		] );
	},
	coreIconsEnd() {
		return del( [
			`${ pkg.square1.paths.component }/core-icons.zip`,
		] );
	},
	themeMinCSS() {
		return del( [
			`${ pkg.square1.paths.core_theme_css_dist }*.css`,
			`${ pkg.square1.paths.core_admin_css_dist }*.css`,
			`${ pkg.square1.paths.core_theme_css_dist }*.css.map`,
			`${ pkg.square1.paths.core_admin_css_dist }*.css.map`,
		] );
	},
	themeMinJS() {
		return del( [
			`${ pkg.square1.paths.core_theme_js_dist }*.min.js`,
			`${ pkg.square1.paths.core_admin_js_dist }*.min.js`,
			`${ pkg.square1.paths.core_theme_js_dist }*.*.js`,
			`${ pkg.square1.paths.core_admin_js_dist }*.*.js`,
			`${ pkg.square1.paths.core_admin_js_dist }*.map`,
			`${ pkg.square1.paths.core_admin_js_dist }**/*.map`,
			`${ pkg.square1.paths.core_theme_js_dist }*.map`,
			`${ pkg.square1.paths.core_theme_js_dist }**/*.map`,
			`${ pkg.square1.paths.core_theme_js_dist }*.*.min.js`,
			`${ pkg.square1.paths.core_admin_js_dist }*.*.min.js`,
		] );
	},
};
