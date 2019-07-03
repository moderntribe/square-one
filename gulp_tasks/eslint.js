const gulp = require( 'gulp' );
const eslint = require( 'gulp-eslint' );
const gulpIf = require( 'gulp-if' );
const pkg = require( '../package.json' );

function isFixed( file ) {
	return file.eslint != null && file.eslint.fixed;
}

function lint( src = [], dest = pkg._core_theme_js_src_path ) {
	return gulp.src( src )
		.pipe( eslint( { fix: true } ) )
		.pipe( eslint.format() )
		.pipe( gulpIf( isFixed, gulp.dest( dest ) ) )
		.pipe( eslint.format() )
		.pipe( eslint.failAfterError() );
}

module.exports = {
	theme() {
		return lint( [
			`${ pkg._core_theme_js_src_path }**/*`,
		] );
	},
	apps() {
		return lint( [
			`${ pkg._core_apps_js_src_path }**/*.js`,
		], pkg._core_apps_js_src_path );
	},
	utils() {
		return lint( [
			`${ pkg._core_utils_js_src_path }**/*`,
		], pkg._core_utils_js_src_path );
	},
	admin() {
		return lint( [
			`${ pkg._core_admin_js_src_path }**/*`,
		], pkg._core_admin_js_src_path );
	},
};
