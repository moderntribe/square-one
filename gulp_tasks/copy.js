const gulp = require( 'gulp' );
const rename = require( 'gulp-rename' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsFonts() {
		return gulp
			.src( [
				`${ pkg.square1.paths.component }/theme/icons/core/fonts/*`,
			] )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_fonts }icons-core/` ) );
	},
	coreIconsStyles() {
		return gulp
			.src( [
				`${ pkg.square1.paths.component }/theme/icons/core/style.css`,
			] )
			.pipe( rename( '_icons.pcss' ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }base/` ) );
	},
	coreIconsVariables() {
		return gulp
			.src( [
				`${ pkg.square1.paths.component }/theme/icons/core/variables.scss`,
			] )
			.pipe( rename( '_icons.pcss' ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }utilities/variables/` ) );
	},
	themeJS() {
		return gulp
			.src( [
				`${ pkg.square1.paths.npm }/jquery/dist/jquery.js`,
				`${ pkg.square1.paths.npm }/jquery/dist/jquery.min.js`,
				`${ pkg.square1.paths.npm }/jquery/dist/jquery.min.map`,
				`${ pkg.square1.paths.npm }/es6-promise/dist/es6-promise.auto.js`,
			] )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_vendor ) );
	},
};
