const gulp = require( 'gulp' );
const runSequence = require( 'run-sequence' );
const gulpIf = require( 'gulp-if' );
const eslint = require( 'gulp-eslint' );
const shell = require( 'gulp-shell' );
const stylelint = require( 'gulp-stylelint' );
const requireDir = require('require-dir');
const tasks = requireDir('./gulp_options');
const browserSync = require( 'browser-sync' ).create('Tribe Dev');
const { reload } = browserSync;
let config = require('./local-config.json');

if (!config) {
	config = {
		proxy: 'square1.tribe',
		certs_path: '',
	}
}

const {
	clean,
	concat,
	postcss,
	cssnano
} = tasks;

function isFixed( file ) {
	return file.eslint != null && file.eslint.fixed;
}

gulp.task( 'scripts-dev', function() {
	return gulp.src( '' )
		.pipe( shell( 'yarn dev' ) )
		.on( 'finish', reload );
} );

gulp.task( 'scripts-prod', function() {
	return gulp.src( '' )
		.pipe( shell( 'yarn prod' ) );
} );

/* Concat tasks */

gulp.task( 'concat:themeMinVendors', concat.themeMinVendors );

/* Clean tasks */

gulp.task( 'clean:coreIconsStart', clean.coreIconsStart );
gulp.task( 'clean:coreIconsEnd', clean.coreIconsEnd );
gulp.task( 'clean:themeMinCSS', clean.themeMinCSS );
gulp.task( 'clean:themeMinJS', clean.themeMinJS );
gulp.task( 'clean:themeMinVendorJS', clean.themeMinVendorJS );

/* Postcss tasks */

gulp.task( 'postcss:theme', postcss.theme );
gulp.task( 'postcss:themeLegacy', postcss.themeLegacy );
gulp.task( 'postcss:themeWPEditor', postcss.themeWPEditor );
gulp.task( 'postcss:themeWPLogin', postcss.themeWPLogin );
gulp.task( 'postcss:themeWPAdmin', postcss.themeWPAdmin );

/* Cssnano tasks */

gulp.task( 'cssnano:themeMin', cssnano.themeMin );
gulp.task( 'cssnano:themeLegacyMin', cssnano.themeLegacyMin );
gulp.task( 'cssnano:themeWPEditorMin', cssnano.themeWPEditorMin );
gulp.task( 'cssnano:themeWPAdminMin', cssnano.themeWPAdminMin );
gulp.task( 'cssnano:themeWPLoginMin', cssnano.themeWPLoginMin );

gulp.task( 'scripts-lint', function() {
	return gulp.src( [ 'resources/assets/js/**/*' ] )
		.pipe( eslint( { fix: true } ) )
		.pipe( eslint.format() )
		.pipe( gulpIf( isFixed, gulp.dest( 'resources/assets/js' ) ) )
		.pipe( eslint.format() )
		.pipe( eslint.failAfterError() );
} );

gulp.task( 'postcss-lint', function() {
	return gulp.src( 'resources/assets/pcss/**/*.pcss' )
		.pipe( stylelint( {
			fix: true,
			reporters: [
				{ formatter: 'string', console: true },
			],
		} ) )
		.pipe( gulp.dest( 'resources/assets/pcss' ) );
} );

gulp.task( 'postcss-lint-js', function() {
	return gulp.src( 'resources/assets/js/**/*.pcss' )
		.pipe( stylelint( {
			fix: true,
			reporters: [
				{ formatter: 'string', console: true },
			],
		} ) )
		.pipe( gulp.dest( 'resources/assets/js' ) );
} );

gulp.task( 'watch', function() {
	gulp.watch( [ 'resources/assets/js/**/*' ], [ 'scripts-dev' ] );
	gulp.watch( [ 'resources/assets/pcss/**/*' ], [ 'postcss-dev' ] );
} );

gulp.task( 'dev', [
	'watch',
], function() {
	browserSync.init( {
		open: true,
		debugInfo: true,
		logConnections: true,
		notify: true,
		proxy: config.proxy,
		ghostMode: {
			scroll: true,
			links: true,
			forms: true,
		},
	} );
} );

gulp.task( 'dist', function( callback ) {
	runSequence(
		'postcss-lint',
		'scripts-lint',
		[ 'postcss-dev', 'postcss-prod' ],
		'scripts-dev',
		'scripts-prod',
		callback,
	);
} );

gulp.task( 'default', [ 'dist' ] );
