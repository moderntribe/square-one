const gulp = require( 'gulp' );
const decompress = require( 'gulp-decompress' );
const pkg = require( '../package.json' );

module.exports = {
	coreIcons() {
		return gulp.src( [
			`${ pkg._component_path }/core-icons.zip`,
		] )
			.pipe( decompress() )
			.pipe( gulp.dest( `${ pkg._component_path }/theme/icons/core` ) );
	},
};
