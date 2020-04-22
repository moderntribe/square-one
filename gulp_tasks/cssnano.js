const gulp = require( 'gulp' );
const cssnano = require( 'gulp-cssnano' );
const rename = require( 'gulp-rename' );
const sourcemaps = require( 'gulp-sourcemaps' );
const pkg = require( '../package.json' );

function minify( src = [], dest = pkg.square1.paths.core_admin_css_dist ) {
	return gulp.src( src )
		.pipe( sourcemaps.init() )
		.pipe( cssnano( { zindex: false } ) )
		.pipe( rename( {
			suffix: '.min',
			extname: '.css',
		} ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( dest ) );
}

module.exports = {
	themeMin() {
		return minify( [
			`${ pkg.square1.paths.core_theme_css }master.css`,
			`${ pkg.square1.paths.core_theme_css }print.css`,
		], pkg.square1.paths.core_theme_css_dist );
	},
	themeGutenberg() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }gutenberg-editor-style.css`,
		], pkg.square1.paths.core_admin_css_dist );
	},
	themeLegacyMin() {
		return minify( [
			`${ pkg.square1.paths.core_theme_css }legacy.css`,
		], pkg.square1.paths.core_theme_css_dist );
	},
	themeWPEditorMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }editor-style.css`,
		] );
	},
	themeWPAdminMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }master.css`,
		] );
	},
	themeWPLoginMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }login.css`,
		] );
	},
};
