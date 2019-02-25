const gulp = require('gulp');
const postcss = require( 'gulp-postcss' );
const sourcemaps = require( 'gulp-sourcemaps' );
const rename = require( 'gulp-rename' );
const browserSync = require("browser-sync").create('Tribe Dev');
const { reload } = browserSync;
const pkg = require('../package.json');

module.exports = {
	theme() {
		const plugins = [
			require( 'postcss-partial-import' )( {
				extension: '.pcss',
			} ),
			require( 'postcss-mixins' ),
			require( 'postcss-custom-properties' ),
			require( 'postcss-simple-vars' ),
			require( 'postcss-custom-media' ),
			require( 'postcss-quantity-queries' ),
			require( 'postcss-aspect-ratio' ),
			require( 'postcss-nested' ),
			require( 'postcss-inline-svg' ),
			require( 'postcss-preset-env' )( { stage: 0 } ),
			require( 'postcss-calc' ),
		];
		return gulp.src( [
			`${pkg._core_theme_pcss_path}/master.pcss`,
			`${pkg._core_theme_pcss_path}/print.pcss`,
		] )
			.pipe( sourcemaps.init() )
			.pipe( postcss( plugins ) )
			.pipe( rename( { extname: '.css' } ) )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest( pkg._core_theme_css_path ) )
			.pipe( reload( { stream: true } ) );
	}
};