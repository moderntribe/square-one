const gulp = require( 'gulp' );
const header = require( 'gulp-header' );
const moment = require( 'moment' );
const fs = require( 'fs' );

module.exports = {
	buildTimestamp() {
		const file = './build-process.php';
		const date = moment().format( 'HH.mm.ss.DD.MM.YYYY' );
		fs.writeFile( file, '', () => {} );
		return gulp.src( [
			file,
		] )
			.pipe( header( `<?php
define( 'BUILD_THEME_ASSETS_TIMESTAMP', '${ date }' );
			` ) )
			.pipe( gulp.dest( './' ) );
	},
};
