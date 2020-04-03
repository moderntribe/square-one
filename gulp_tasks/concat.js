const gulp = require( 'gulp' );
const concat = require( 'gulp-concat' );
const pkg = require( '../package.json' );

module.exports = {
	themeMinVendors() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_js_dist }vendorGlobal.min.js`,
			`${ pkg.square1.paths.core_theme_js_dist }vendorWebpack.min.js`,
		] )
			.pipe( concat( 'vendor.min.js' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_dist ) );
	},
};
