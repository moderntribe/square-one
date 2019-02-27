const gulp = require( 'gulp' );
const uglify = require( 'gulp-uglify' );
const concat = require( 'gulp-concat' );
const pkg = require( '../package.json' );

module.exports = {
	themeMin() {
		return gulp.src( [
			`${ pkg._core_theme_js_vendor_path }globals.js`,
			`${ pkg._core_theme_js_vendor_path }ls.object-fit.js`,
			`${ pkg._core_theme_js_vendor_path }ls.parent-fit.js`,
			`${ pkg._core_theme_js_vendor_path }ls.respimg.js`,
			`${ pkg._core_theme_js_vendor_path }ls.bgset.js`,
			`${ pkg._core_theme_js_vendor_path }lazysizes.js`,
		] )
			.pipe( concat( 'vendorGlobal.min.js' ) )
			.pipe( uglify( {
				compress: {
					drop_console: true,
				},
			} ) )
			.pipe( gulp.dest( pkg._core_theme_js_dist_path ) );
	},
};
