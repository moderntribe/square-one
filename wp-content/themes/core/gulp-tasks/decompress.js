const gulp = require( 'gulp' );
const decompress = require( 'gulp-decompress' );
const pkg = require( '../package.json' );

module.exports = {
	coreIcons() {
		return gulp.src( [
			`${ pkg.square1.paths.component }/core-icons.zip`,
		] )
			.pipe( decompress() )
			.pipe( gulp.dest( `${ pkg.square1.paths.component }/theme/icons/core` ) );
	},
};
