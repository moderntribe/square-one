const gulp = require( 'gulp' );
const header = require( 'gulp-header' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStyle() {
		return gulp.src( `${ pkg.square1.paths.core_theme_pcss }base/_icons.pcss` )
			.pipe( header( `
/* -----------------------------------------------------------------------------
 *
 * Font Icons (via IcoMoon)
 *
 * ----------------------------------------------------------------------------- */

/* stylelint-disable */

` ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }base/` ) );
	},
	coreIconsVariables() {
		return gulp.src( `${ pkg.square1.paths.core_theme_pcss }utilities/variables/_icons.pcss` )
			.pipe( header( `
/* -----------------------------------------------------------------------------
 *
 * Font Icons (via IcoMoon)
 *
 * ----------------------------------------------------------------------------- */

/* stylelint-disable */

:root {` ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }utilities/variables/` ) );
	},
	theme() {
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }master.min.css` )
			.pipe( header( '/* Core: Global CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themePrint() {
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }print.min.css` )
			.pipe( header( '/* Core: Print CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themeLegacy() {
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }legacy.min.css` )
			.pipe( header( '/* Core: Legacy Page CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themeWPEditor() {
		return gulp.src( `${ pkg.square1.paths.core_admin_css_dist }editor-style.min.css` )
			.pipe( header( '/* Core: Visual Editor CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_css_dist ) );
	},
	themeWPLogin() {
		return gulp.src( `${ pkg.square1.paths.core_admin_css_dist }login.min.css` )
			.pipe( header( '/* Core: WordPress Login CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_css_dist ) );
	},
};
