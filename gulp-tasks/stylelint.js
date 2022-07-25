const gulp = require( 'gulp' );
const stylelint = require( 'gulp-stylelint' );
const pkg = require( '../package.json' );

module.exports = {
	theme() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_pcss }**/*.pcss`,
			`!${ pkg.square1.paths.core_theme_pcss }vendor/swiper/_default.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_pcss ) );
	},
	apps() {
		return gulp.src( [
			`${ pkg.square1.paths.core_apps_js_src }**/*.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_apps_js_src ) );
	},
	components() {
		return gulp.src( [
			`${ pkg.square1.paths.core_components_pcss }**/*.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_components_pcss ) );
	},
	routes() {
		return gulp.src( [
			`${ pkg.square1.paths.core_routes_pcss }**/*.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_routes_pcss ) );
	},
	integrations() {
		return gulp.src( [
			`${ pkg.square1.paths.core_theme_integrations }**/*.pcss`,
		] )
			.pipe( stylelint( {
				fix: true,
				reporters: [
					{ formatter: 'string', console: true },
				],
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_integrations ) );
	}
};
