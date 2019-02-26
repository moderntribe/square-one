const gulp = require( 'gulp' );
const header = require( 'gulp-header' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStyle() {
		return gulp.src( `${ pkg._core_theme_pcss_path }base/_icons.pcss` )
			.pipe( header( `
			/* -----------------------------------------------------------------------------
			 * Font Icons (via IcoMoon)
			 * ----------------------------------------------------------------------------- */
			 
			 /* stylelint-disable */
			` ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }base/` ) );
	},
	coreIconsVariables() {
		return gulp.src( `${ pkg._core_theme_pcss_path }utilities/variables/_icons.pcss` )
			.pipe( header( `
			/* -----------------------------------------------------------------------------
			 * Font Icons (via IcoMoon)
			 * ----------------------------------------------------------------------------- */
			 
			 /* stylelint-disable */
			 
			 :root {
			` ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }utilities/variables/` ) );
	},
	theme() {
		return gulp.src( `${ pkg._core_theme_css_dist_path }master.min.css` )
			.pipe( header( '/* Core: Global CSS */' ) )
			.pipe( gulp.dest( pkg._core_theme_css_dist_path ) );
	},
	themePrint() {
		return gulp.src( `${ pkg._core_theme_css_dist_path }print.min.css` )
			.pipe( header( '/* Core: Print CSS */' ) )
			.pipe( gulp.dest( pkg._core_theme_css_dist_path ) );
	},
	themeLegacy() {
		return gulp.src( `${ pkg._core_theme_css_dist_path }legacy.min.css` )
			.pipe( header( '/* Core: Legacy Page CSS */' ) )
			.pipe( gulp.dest( pkg._core_theme_css_dist_path ) );
	},
	themeWPEditor() {
		return gulp.src( `${ pkg._core_admin_css_dist_path }editor-style.min.css` )
			.pipe( header( '/* Core: Visual Editor CSS */' ) )
			.pipe( gulp.dest( pkg._core_admin_css_dist_path ) );
	},
	themeWPLogin() {
		return gulp.src( `${ pkg._core_admin_css_dist_path }login.min.css` )
			.pipe( header( '/* Core: WordPress Login CSS */' ) )
			.pipe( gulp.dest( pkg._core_admin_css_dist_path ) );
	},
};
