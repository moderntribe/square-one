const gulp = require( 'gulp' );
const replace = require( 'gulp-replace' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStyle() {
		return gulp.src( [
			`${ pkg._core_theme_pcss_path }base/_icons.pcss`,
		] )
			.pipe( replace( /url\('fonts\/(.+)'\) /g, 'resolve(\'fonts/icons-core/$1\') ' ) )
			.pipe( replace( / {2}/g, '\t' ) )
			.pipe( replace( /}$\n^\./gm, '}\n\n\.' ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }base/` ) );
	},
	coreIconsVariables() {
		return gulp.src( [
			`${ pkg._core_theme_pcss_path }utilities/variables/_icons.pcss`,
		] )
			.pipe( replace( /(\\[a-f0-9]+);/g, '"$1";' ) )
			.pipe( replace( /\$icomoon-font-path: "fonts" !default;\n/g, '' ) )
			.pipe( replace( /\$icomoon-font-family: "core-icons" !default;\n/g, '' ) )
			.pipe( replace( /\$/g, '\t--' ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }utilities/variables/` ) );
	},
};
