const gulp = require( 'gulp' );
const uglify = require( 'gulp-uglify' );
const concat = require( 'gulp-concat' );
const pkg = require( '../package.json' );

module.exports = {
	themeMin() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_js_vendor }globals.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }ls.object-fit.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }ls.parent-fit.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }ls.respimg.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }ls.bgset.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }lazysizes.js`,
			`${ pkg.square1.paths.core_theme_js_vendor }swiper.js`,
		] )
			.pipe( concat( 'vendorGlobal.min.js' ) )
			.pipe( uglify( {
				compress: {
					drop_console: true,
				},
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_dist ) );
	},
};
