const gulp = require( 'gulp' );
const postcss = require( 'gulp-postcss' );
const sourcemaps = require( 'gulp-sourcemaps' );
const rename = require( 'gulp-rename' );
const gulpif = require( 'gulp-if' );
const concat = require( 'gulp-concat' );
const browserSync = require( 'browser-sync' );
const postcssFunctions = require( '../dev_components/theme/pcss/functions' );
const pkg = require( '../package.json' );

const sharedPlugins = [
	require( 'postcss-import-ext-glob' )( {
		sort: 'asc',
	} ),
	require( 'postcss-import' )( {
		path: [
			`./${ pkg.square1.paths.core_theme }`,
		],
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
];

const compilePlugins = sharedPlugins.concat( [
	require( 'postcss-assets' )( { loadPaths: [ `${ pkg.square1.paths.core_theme }/` ] } ),
] );

const compileGutenbergPlugins = sharedPlugins.concat( [
	require( '@moderntribe/postcss-multi-selector-replace' )( { before: [ '.t-sink', '.s-sink' ], after: [ '[data-type^="core/"]', '[data-type^="core/"]' ] } ),
	require( 'postcss-assets' )( { loadPaths: [ `${ pkg.square1.paths.core_theme }/` ] } ),
] );

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
	require( 'postcss-assets' )( { loadPaths: [ `${ pkg.square1.paths.core_theme }/` ] } ),
];

/**
 *
 *
 * @param {Object} options {
 * 	src = [],
 * 	dest = pkg.square1.paths.core_admin_css,
 * 	plugins = compilePlugins,
 * 	bundleName = 'empty.css',
 * }
 * @param {Array<string>} options.src
 * @param {string} options.dest
 * @param {Array<Function>} options.plugins
 * @param {string} options.bundleName
 * @returns
 */
function cssProcess( {
	src = [],
	dest = pkg.square1.paths.core_admin_css,
	plugins = compilePlugins,
	bundleName = 'empty.css', // Needs to be a valid filename else concat errors
} ) {
	const server = browserSync.get( 'Tribe Dev' );
	return gulp.src( src )
		.pipe( sourcemaps.init() )
		.pipe( postcss( plugins ) )
		.pipe( rename( { extname: '.css' } ) )
		.pipe( gulpif(
			bundleName !== 'empty.css',
			concat( bundleName )
		) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( dest ) )
		.pipe( gulpif( server.active, server.reload( { stream: true } ) ) );
}

module.exports = {
	theme() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_theme_pcss }master.pcss`,
				`${ pkg.square1.paths.core_theme_pcss }print.pcss`,
			],
			dest: pkg.square1.paths.core_theme_css,
		} );
	},
	themeLegacy() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_theme_pcss }legacy.pcss`,
			],
			dest: pkg.square1.paths.core_theme_css,
			plugins: legacyPlugins,
		} );
	},
	admin() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_admin_pcss }master.pcss`,
			],
		} );
	},
	adminBlockEditor() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_admin_pcss }block-editor.pcss`,
			],
			dest: pkg.square1.paths.core_admin_css,
			plugins: compileGutenbergPlugins,
		} );
	},
	adminMCEEditor() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_admin_pcss }mce-editor.pcss`,
			],
		} );
	},
	adminLogin() {
		return cssProcess( {
			src: [
				`${ pkg.square1.paths.core_admin_pcss }login.pcss`,
			],
		} );
	},
};
