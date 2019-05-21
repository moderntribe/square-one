const gulp = require( 'gulp' );
const lec = require( 'gulp-line-ending-corrector' );
const pkg = require( '../package.json' );

module.exports = {
	win() {
		return gulp.src( [
			`${ pkg._core_theme_js_vendor_path }**/*`,
		] )
			.pipe( lec( { verbose: true, eolc: 'CRLF' } ) )
			.pipe( gulp.dest( pkg._core_theme_js_vendor_path ) );
	},
};
