const gulp = require( 'gulp' );
const cssnano = require( 'gulp-cssnano' );
const rename = require( 'gulp-rename' );
const sourcemaps = require( 'gulp-sourcemaps' );
const pkg = require( '../package.json' );

function minify( src = [], dest = pkg._core_admin_css_path ) {
	return gulp.src( src )
		.pipe( sourcemaps.init() )
		.pipe( cssnano( { zindex: false } ) )
		.pipe( rename( {
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( dest ) );
}

module.exports = {
	themeMin() {
		return minify( [
			`${ pkg._core_theme_css_path }master.css`,
			`${ pkg._core_theme_css_path }print.css`,
		], pkg._core_theme_css_dist_path );
	},
	themeLegacyMin() {
		return minify( [
			`${ pkg._core_theme_css_path }legacy.css`,
		], pkg._core_theme_css_dist_path );
	},
	themeWPEditorMin() {
		return minify( [
			`${ pkg._core_admin_css_path }editor-style.css`,
		] );
	},
	themeWPAdminMin() {
		return minify( [
			`${ pkg._core_admin_css_path }master.css`,
		] );
	},
	themeWPLoginMin() {
		return minify( [
			`${ pkg._core_admin_css_path }login.css`,
		] );
	},
};
