const gulp = require( 'gulp' );
const eslint = require( 'gulp-eslint' );
const gulpIf = require( 'gulp-if' );
const pkg = require( '../package.json' );

function isFixed( file ) {
	return file.eslint != null && file.eslint.fixed;
}

function lint( src = [], dest = pkg.square1.paths.core_theme_js_src ) {
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
			`${ pkg.square1.paths.core_theme_js_src }**/*`,
		] );
	},
	apps() {
		return lint( [
			`${ pkg.square1.paths.core_apps_js_src }**/*.js`,
		], pkg.square1.paths.core_apps_js_src );
	},
	utils() {
		return lint( [
			`${ pkg.square1.paths.core_utils_js_src }**/*`,
		], pkg.square1.paths.core_utils_js_src );
	},
	admin() {
		return lint( [
			`${ pkg.square1.paths.core_admin_js_src }**/*`,
		], pkg.square1.paths.core_admin_js_src );
	},
};
