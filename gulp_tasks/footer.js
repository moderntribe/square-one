const gulp = require( 'gulp' );
const footer = require( 'gulp-footer' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsVariables() {
		return gulp.src( `${ pkg.square1.paths.core_theme_pcss }utilities/variables/_icons.pcss` )
			.pipe( footer( '}\n' ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }utilities/variables/` ) );
	},
};
