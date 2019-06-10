const gulp = require( 'gulp' );
const stylelint = require( 'gulp-stylelint' );
const pkg = require( '../package.json' );

module.exports = {
	theme() {
		return gulp.src( [
			`${ pkg._core_theme_pcss_path }**/*.pcss`,
			`!${ pkg._core_theme_pcss_path }content/page/_legacy.pcss`,
			`!${ pkg._core_theme_pcss_path }vendor/swiper/_default.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg._core_theme_pcss_path ) );
	},
	apps() {
		return gulp.src( [
			`${ pkg._core_apps_js_src_path }**/*.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg._core_apps_js_src_path ) );
	},
};
