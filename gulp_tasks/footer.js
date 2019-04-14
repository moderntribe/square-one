const gulp = require( 'gulp' );
const footer = require( 'gulp-footer' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsVariables() {
		return gulp.src( `${ pkg._core_theme_pcss_path }utilities/variables/_icons.pcss` )
			.pipe( footer( '}' ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }utilities/variables/` ) );
	},
};
