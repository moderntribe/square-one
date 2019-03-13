const gulp = require( 'gulp' );
const concat = require( 'gulp-concat' );
const pkg = require( '../package.json' );

module.exports = {
	themeMinVendors() {
		return gulp.src( [
			`${ pkg._core_theme_js_dist_path }vendorGlobal.min.js`,
			`${ pkg._core_theme_js_dist_path }vendorWebpack.min.js`,
		] )
			.pipe( concat( 'vendor.min.js' ) )
			.pipe( gulp.dest( pkg._core_theme_js_dist_path ) );
	},
};
