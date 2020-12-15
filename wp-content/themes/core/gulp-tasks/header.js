const gulp = require( 'gulp' );
const header = require( 'gulp-header' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStyle() {
		return gulp.src( `${ pkg.square1.paths.core_theme_pcss }icons/icons.pcss` )
			.pipe( header( `/* -----------------------------------------------------------------------------
 *
 * Font Icons (via IcoMoon)
 *
 * This file is generated using the \`gulp icons\` task. Do not edit it directly.
 *
 * ----------------------------------------------------------------------------- */

` ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }icons/` ) );
	},
	coreIconsVariables() {
		return gulp.src( `${ pkg.square1.paths.core_theme_pcss }icons/_variables.pcss` )
			.pipe( header( `/* -----------------------------------------------------------------------------
 *
 * Variables: Icons (via IcoMoon)
 *
 * This file is generated using the \`gulp icons\` task. Do not edit it directly.
 *
 * ----------------------------------------------------------------------------- */

:root {` ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }icons/` ) );
	},
	theme() {
		return Promise.resolve( 'Deprecated' );
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }master.min.css` )
			.pipe( header( '/* Core: Global CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themePrint() {
		return Promise.resolve( 'Deprecated' );
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }print.min.css` )
			.pipe( header( '/* Core: Print CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themeLegacy() {
		return Promise.resolve( 'Deprecated' );
		return gulp.src( `${ pkg.square1.paths.core_theme_css_dist }legacy.min.css` )
			.pipe( header( '/* Core: Legacy Page CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	themeWPEditor() {
		return Promise.resolve( 'Deprecated' );
		return gulp.src( `${ pkg.square1.paths.core_admin_css_dist }editor-style.min.css` )
			.pipe( header( '/* Core: Visual Editor CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_css_dist ) );
	},
	themeWPLogin() {
		return Promise.resolve( 'Deprecated' );
		return gulp.src( `${ pkg.square1.paths.core_admin_css_dist }login.min.css` )
			.pipe( header( '/* Core: WordPress Login CSS */' ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_css_dist ) );
	},
};
