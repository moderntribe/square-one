const gulp = require( 'gulp' );
const pkg = require( '../package.json' );
const browserSync = require( 'browser-sync' );

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

		// watch theme javascript

		gulp.watch( [
			`${ pkg._core_theme_js_src_path }**/*.js`,
		], [
			'shell:scriptsThemeDev',
		] );

		// watch admin javascript

		gulp.watch( [
			`${ pkg._core_admin_js_src_path }**/*.js`,
		], [
			'shell:scriptsAdminDev',
		] );

		// watch util javascript

		gulp.watch( [
			`${ pkg._core_utils_js_src_path }**/*.js`,
		], [
			'shell:scriptsThemeDev',
			'shell:scriptsAdminDev',
		] );

		// watch php and twig

		gulp.watch( [
			`${ pkg._core_theme_path }/**/*.php`,
			`${ pkg._core_theme_path }/**/*.twig`,
		] ).on( 'change', function() {
			const server = browserSync.get( 'Tribe Dev' );
			if ( server.active ) {
				server.reload();
			}
		} );
	},
};
