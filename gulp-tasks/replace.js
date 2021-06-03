const gulp = require( 'gulp' );
const replace = require( 'gulp-replace' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsStyle() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_pcss }icons/icons.pcss`,
		] )
			.pipe( replace( /url\('fonts\/(.+)'\) /g, 'resolve(\'assets/fonts/theme/icons-core/$1\') ' ) )
			.pipe( replace( / {2}/g, '\t' ) )
			.pipe( replace( /}$\n^\./gm, '}\n\n\.' ) )
			.pipe( replace( /'core-icons' !important/g, 'var(--font-family-core-icons) !important' ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }icons/` ) );
	},
	coreIconsVariables() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_pcss }icons/_variables.pcss`,
		] )
			.pipe( replace( /(\\[a-f0-9]+);/g, '"$1";' ) )
			.pipe( replace( /\$icomoon-font-path: "fonts" !default;\n/g, '' ) )
			.pipe( replace( /\$icomoon-font-family: "core-icons" !default;\n/g, '' ) )
			.pipe( replace( /\$/g, '\t--' ) )
			.pipe( replace( /;\n\n$/m, ';\n' ) )
			.pipe( gulp.dest( `${ pkg.square1.paths.core_theme_pcss }icons/` ) );
	},
};
