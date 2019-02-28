const gulp = require( 'gulp' );
const shell = require( 'gulp-shell' );
const gulpif = require( 'gulp-if' );
const browserSync = require( 'browser-sync' );

module.exports = {
	yarnInstall() {
		return gulp.src( '' )
			.pipe( shell( 'yarn install' ) );
	},
	test() {
		return gulp.src( '' )
			.pipe( shell( 'yarn test' ) );
	},
	scriptsThemeDev() {
		const server = browserSync.get( 'Tribe Dev' );
		return gulp.src( '' )
			.pipe( shell( 'yarn js:theme:dev' ) )
			.on( 'finish', gulpif( server.active, server.reload() ) );
	},
	scriptsThemeProd() {
		return gulp.src( '' )
			.pipe( shell( 'yarn js:theme:prod' ) );
	},
	scriptsAdminDev() {
		const server = browserSync.get( 'Tribe Dev' );
		return gulp.src( '' )
			.pipe( shell( 'yarn js:admin:dev' ) )
			.on( 'finish', gulpif( server.active, server.reload() ) );
	},
	scriptsAdminProd() {
		return gulp.src( '' )
			.pipe( shell( 'yarn js:admin:prod' ) );
	},
};
