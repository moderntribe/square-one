const gulp = require( 'gulp' );
const lec = require( 'gulp-line-ending-corrector' );
const pkg = require( '../package.json' );

module.exports = {
	win() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_js_vendor }**/*`,
		] )
			.pipe( lec( { verbose: true, eolc: 'CRLF' } ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_vendor ) );
	},
};
