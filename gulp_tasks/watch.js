const gulp = require( 'gulp' );
const pkg = require( '../package.json' );
const browserSync = require( 'browser-sync' );
const webpack = require( 'webpack' );
const webpackStream = require( 'webpack-stream' );
const merge = require( 'webpack-merge' );
const webpackAdminDevConfig = require( '../webpack/admindev' );
const webpackThemeDevConfig = require( '../webpack/themedev' );

const watchConfig = {
	watch: true,
};

const ifdefOpts = {
	'INCLUDEREACT': false,
	'version': 3,
	'ifdef-verbose': true,
	'ifdef-triple-slash': false,
};

const ifDefRuleOverride = [
	{
		test: /\.js$/,
		exclude: [ /(node_modules)/ ],
		use: [
			{
				loader: 'babel-loader',
			},
			{
				loader: 'ifdef-loader',
				options: ifdefOpts,
			},
		],
	},
];

webpackAdminDevConfig.module.rules = ifDefRuleOverride;
webpackThemeDevConfig.module.rules = ifDefRuleOverride;

function maybeReloadBrowserSync() {
	const server = browserSync.get( 'Tribe Dev' );
	if ( server.active ) {
		server.reload();
	}
}

module.exports = {
	frontEndDev() {
		// watch main theme postcss minus admin styles

		gulp.watch( [
			`${ pkg._core_theme_pcss_path }**/*.pcss`,
			`!${ pkg._core_theme_pcss_path }legacy.pcss`,
			`!${ pkg._core_theme_pcss_path }content/page/_legacy.pcss`,
			`!${ pkg._core_admin_pcss_path }**/*.pcss`,
		], [
			'postcss:theme',
		] );

		// watch the legacy postcss

		gulp.watch( [
			`${ pkg._core_theme_pcss_path }legacy.pcss`,
			`${ pkg._core_theme_pcss_path }content/page/_legacy.pcss`,
		], [
			'postcss:themeLegacy',
		] );

		// watch the login postcss

		gulp.watch( [
			`${ pkg._core_admin_pcss_path }login.pcss`,
		], [
			'postcss:themeWPLogin',
		] );

		// watch the editor styles postcss

		gulp.watch( [
			`${ pkg._core_admin_pcss_path }editor-styles.pcss`,
		], [
			'postcss:themeWPEditor',
		] );

		// watch the admin styles postcss

		gulp.watch( [
			`${ pkg._core_admin_pcss_path }**/*.pcss`,
			`!${ pkg._core_admin_pcss_path }editor-styles.pcss`,
			`!${ pkg._core_admin_pcss_path }login.pcss`,
		], [
			'postcss:themeWPAdmin',
		] );

		// watch php and twig

		gulp.watch( [
			`${ pkg._core_theme_path }/**/*.php`,
			`${ pkg._core_theme_path }/**/*.twig`,
		] ).on( 'change', function() {
			maybeReloadBrowserSync();
		} );
	},
	watchAdminJS() {
		gulp.src( `${ pkg._core_admin_js_src_path }**/*.js` )
			.pipe( webpackStream( merge( webpackAdminDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg._core_admin_js_dist_path ) );
	},
	watchThemeJS() {
		gulp.src( `${ pkg._core_theme_js_src_path }**/*.js` )
			.pipe( webpackStream( merge( webpackThemeDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg._core_theme_js_dist_path ) );
	},
};
