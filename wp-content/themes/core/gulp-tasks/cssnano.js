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
	themeLegacyMin() {
		return minify( [
			`${ pkg.square1.paths.core_theme_css }legacy.css`,
		], pkg.square1.paths.core_theme_css_dist );
	},
	adminMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }master.css`,
		] );
	},
	adminBlockEditorMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }block-editor.css`,
		], pkg.square1.paths.core_admin_css_dist );
	},
	adminMCEEditorMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }mce-editor.css`,
		] );
	},
	adminLoginMin() {
		return minify( [
			`${ pkg.square1.paths.core_admin_css }login.css`,
		] );
	},
};
