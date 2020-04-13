const gulp = require( 'gulp' );
const pkg = require( '../package.json' );
const browserSync = require( 'browser-sync' );
const webpack = require( 'webpack' );
const webpackStream = require( 'webpack-stream' );
const merge = require( 'webpack-merge' );
const webpackAdminDevConfig = require( '../webpack/admindev' );
const webpackThemeDevConfig = require( '../webpack/themedev' );
const watchRules = require( '../webpack/rules/watch' );

const watchConfig = {
	watch: true,
};

webpackAdminDevConfig.module.rules = watchRules;
webpackThemeDevConfig.module.rules = watchRules;

function maybeReloadBrowserSync() {
	const server = browserSync.get( 'Tribe Dev' );
	if ( server.active ) {
		server.reload();
	}
}

module.exports = {
	frontEndDev() {
		// watch php and twig

		gulp.watch( [
			`${ pkg.square1.paths.core_theme }/**/*.php`,
			`${ pkg.square1.paths.core_theme }/**/*.twig`,
		] ).on( 'change', function() {
			maybeReloadBrowserSync();
		} );
	},
	watchAdminCSS() {
		gulp.src( [
			`${ pkg.square1.paths.core_admin_pcss }**/*.pcss`,
		] )
			.pipe( webpackStream( merge( webpackAdminDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_css_dist ) );
	},
	watchThemeCSS() {
		gulp.src( [
			`${ pkg.square1.paths.core_theme_pcss }**/*.pcss`,
			`${ pkg.square1.paths.core_theme_components }**/*.pcss`,
			`${ pkg.square1.paths.core_theme_integrations }**/*.pcss`,
		] )
			.pipe( webpackStream( merge( webpackThemeDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_css_dist ) );
	},
	watchAdminJS() {
		gulp.src( `${ pkg.square1.paths.core_admin_js_src }**/*.js` )
			.pipe( webpackStream( merge( webpackAdminDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_js_dist ) );
	},
	watchThemeJS() {
		gulp.src( `${ pkg.square1.paths.core_theme_js_src }**/*.js` )
			.pipe( webpackStream( merge( webpackThemeDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_dist ) );
	},
};
