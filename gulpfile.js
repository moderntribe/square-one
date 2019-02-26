const gulp = require( 'gulp' );
const runSequence = require( 'run-sequence' );
const shell = require( 'gulp-shell' );
const stylelint = require( 'gulp-stylelint' );
const requireDir = require( 'require-dir' );
const tasks = requireDir( './gulp_tasks' );
const browserSync = require( 'browser-sync' ).create( 'Tribe Dev' );
const { reload } = browserSync;
let config = require( './local-config.json' );

if ( ! config ) {
	config = {
		proxy: 'square1.tribe',
		certs_path: '',
	};
}

/**
 * List out your tasks as defined in the gulp_tasks directory
 * require-dir module will bring those in here as an object
 * Each task type object will be named by its filename
 * So: 'concat:themeMinVendors' means a file named 'concat' in the grunt_tasks dir,
 * and the method themeMinVendors inside that modules export.
 * You must follow this approach, or modify the registerTasks function below.
 *
 * @type {string[]}
 */

const gulpTasks = [
	/* Concat tasks */

	'concat:themeMinVendors',

	/* Copy tasks */

	'copy:coreIconsFonts',
	'copy:coreIconsStyles',
	'copy:coreIconsVariables',
	'copy:themeJS',

	/* Clean tasks */

	'clean:coreIconsStart',
	'clean:coreIconsEnd',
	'clean:themeMinCSS',
	'clean:themeMinJS',
	'clean:themeMinVendorJS',

	/* Eslint tasks */

	'eslint:theme',
	'eslint:apps',
	'eslint:utils',
	'eslint:admin',

	/* Footer tasks */

	'footer:theme',

	/* Header tasks */

	'header:coreIconsStyle',
	'header:coreIconsVariables',
	'header:theme',
	'header:themePrint',
	'header:themeLegacy',
	'header:themeWPEditor',
	'header:themeWPLogin',

	/* Line ending tasks */

	'lineending:win',

	/* Postcss tasks */

	'postcss:theme',
	'postcss:themeLegacy',
	'postcss:themeWPEditor',
	'postcss:themeWPLogin',
	'postcss:themeWPAdmin',

	/* Cssnano tasks */

	'cssnano:themeMin',
	'cssnano:themeLegacyMin',
	'cssnano:themeWPEditorMin',
	'cssnano:themeWPAdminMin',
	'cssnano:themeWPLoginMin',
];

/**
 * Iterate over the above array. Split on the colon to access the imported tasks array's
 * corresponding function for the current task in the loop
 */

function registerTasks() {
	gulpTasks.forEach( ( task ) => {
		const parts = task.split( ':' );
		gulp.task( task, tasks[ parts[ 0 ] ][ parts[ 1 ] ] );
	} );
}

registerTasks();

gulp.task( 'scripts-dev', function() {
	return gulp.src( '' )
		.pipe( shell( 'yarn dev' ) )
		.on( 'finish', reload );
} );

gulp.task( 'scripts-prod', function() {
	return gulp.src( '' )
		.pipe( shell( 'yarn prod' ) );
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
