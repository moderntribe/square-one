const gulp = require( 'gulp' );
const postcss = require( 'gulp-postcss' );
const sourcemaps = require( 'gulp-sourcemaps' );
const rename = require( 'gulp-rename' );
const gulpif = require( 'gulp-if' );
const browserSync = require( 'browser-sync' );
const postcssFunctions = require( '../dev_components/theme/pcss/functions' );
const pkg = require( '../package.json' );

const compilePlugins = [
	require( 'postcss-partial-import' )( {
		extension: '.pcss',
		prefix: '_',
	} ),
	require( 'postcss-mixins' ),
	require( 'postcss-custom-properties' )( { preserve: false } ),
	require( 'postcss-simple-vars' ),
	require( 'postcss-custom-media' ),
	require( 'postcss-functions' )( { functions: postcssFunctions } ),
	require( 'postcss-quantity-queries' ),
	require( 'postcss-aspect-ratio' ),
	require( 'postcss-nested' ),
	require( 'postcss-inline-svg' ),
	require( 'postcss-preset-env' )( { stage: 0, autoprefixer: { grid: true } } ),
	require( 'postcss-calc' ),
	require( 'postcss-assets' )( { loadPaths: [ `${ pkg._core_theme_path }/` ] } ),
];

const legacyPlugins = [
	require( 'postcss-partial-import' )( {
		extension: '.pcss',
		prefix: '_',
	} ),
	require( 'postcss-mixins' ),
	require( 'postcss-custom-properties' )( { preserve: false } ),
	require( 'postcss-simple-vars' ),
	require( 'postcss-nested' ),
	require( 'postcss-preset-env' )( { browsers: [ 'last 20 versions', 'ie 6' ] } ),
	require( 'postcss-assets' )( { loadPaths: [ `${ pkg._core_theme_path }/` ] } ),
];

function cssProcess( src = [], dest = pkg._core_admin_css_path, plugins = compilePlugins ) {
	const server = browserSync.get( 'Tribe Dev' );
	return gulp.src( src )
		.pipe( sourcemaps.init() )
		.pipe( postcss( plugins ) )
		.pipe( rename( { extname: '.css' } ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( dest ) )
		.pipe( gulpif( server.active, server.reload( { stream: true } ) ) );
}

module.exports = {
	theme() {
		return cssProcess( [
			`${ pkg._core_theme_pcss_path }master.pcss`,
			`${ pkg._core_theme_pcss_path }print.pcss`,
		], pkg._core_theme_css_path );
	},
	themeLegacy() {
		return cssProcess( [
			`${ pkg._core_theme_pcss_path }legacy.pcss`,
		], pkg._core_theme_css_path, legacyPlugins );
	},
	themeWPEditor() {
		return cssProcess( [
			`${ pkg._core_admin_pcss_path }editor-style.pcss`,
		] );
	},
	themeWPLogin() {
		return cssProcess( [
			`${ pkg._core_admin_pcss_path }login.pcss`,
		] );
	},
	themeWPAdmin() {
		return cssProcess( [
			`${ pkg._core_admin_pcss_path }master.pcss`,
		] );
	},
};
