const { extname } = require( 'path' );
const { createHash } = require( 'crypto' );
const json2php = require( 'json2php' );
const { resolve } = require( 'path' );
const fs = require( 'fs' );
const { writeFile } = require( 'fs' );
const fsPromises = fs.promises;

const { square1: { paths } } = require( '../../package.json' );

function stringify( contents ) {
	return `<?php return ${ json2php(
		JSON.parse( JSON.stringify( contents ) )
	) };`;
}

async function listDir( dir ) {
	try {
		return fsPromises.readdir( dir );
	} catch ( err ) {
		console.error( 'Error occurred while reading directory: ', err );
	}
}

async function asyncForEach( array, callback ) {
	for ( let index = 0; index < array.length; index++ ) {
		await callback( array[ index ], index, array );
	}
}

function getAssetHandle( filename = '', prefix = 'tribe-' ) {
	return `${ prefix }${ filename.substr( 0, filename.lastIndexOf( '.' ) ).replace( /\./g, '-' ) }`;
}

function getJSDependency( file, target, handlePrefix ) {
	// admin vendor deps
	if ( file.indexOf( 'vendor' ) !== -1 && target === 'admin' ) {
		return [ 'wp-element', 'wp-blocks', 'wp-hooks', 'wp-components', 'wp-i18n', 'wp-editor' ];
	}
	// theme vendor deps
	if ( file.indexOf( 'vendor' ) !== -1 && target === 'theme' ) {
		return [ 'jquery' ];
	}
	if ( file.indexOf( 'scripts' ) !== -1 ) {
		return [ `${ handlePrefix }vendor` ];
	}
}

async function filehash( filename, algorithm = 'md4' ) {
	return new Promise( ( res, reject ) => {
		const shasum = createHash( algorithm );
		try {
			const s = fs.ReadStream( filename );
			s.on( 'data', function( data ) {
				shasum.update( data );
			} );
			s.on( 'end', function() {
				const hash = shasum.digest( 'hex' );
				return res( hash );
			} );
		} catch ( error ) {
			return reject( 'Hash calculation fail' );
		}
	} );
}

function writeManifestFile( manifestDir, combinedAssetData ) {
	const manifestFile = resolve( manifestDir, 'assets.php' );
	writeFile( manifestFile, stringify( combinedAssetData ), err => {
		if ( err ) {
			throw err;
		}
	} );
}

module.exports = {
	writeCSSData: async( dir, target = 'theme' ) => {
		const cssFiles = await listDir( dir );
		if ( ! cssFiles ) {
			return;
		}
		const data = {
			localize: [],
			enqueue: {
				development: {},
				production: {},
			},
		};
		await asyncForEach( cssFiles, async( file ) => {
			const extension = extname( file );
			if ( extension !== '.css' ) {
				return;
			}
			const handlePrefix = 'tribe-styles-';
			const version = await filehash( `${ dir }/${ file }` );
			const fileNameArray = file.split( '.' );
			// remove min
			const minIndex = fileNameArray.indexOf( 'min' );
			if ( minIndex > -1 ) {
				fileNameArray.splice( minIndex, 1 );
			}
			// filename without chunkhash or min
			const fileName = fileNameArray.join( '.' );
			const media = file.indexOf( 'print' ) !== -1 ? 'print' : 'all';
			const dependencies = [];
			const enqueueTarget = file.indexOf( '.min.css' ) !== -1 ? data.enqueue.production : data.enqueue.development;
			const relativePath = target === 'theme' ? paths.core_theme_relative_css_dist : paths.core_admin_relative_css_dist;
			enqueueTarget[ getAssetHandle( fileName, handlePrefix ) ] = {
				version,
				media,
				file: `${ relativePath }${ file }`,
				dependencies,
			};
			data.localize.push( `${ file }?ver=${ version }` );
		} );
		writeManifestFile( dir, data );
	},

	writeJSData: async( dir, target = 'theme' ) => {
		const jsFiles = await listDir( dir );
		if ( ! jsFiles ) {
			return;
		}
		const enqueueTargets = [ 'vendor', 'scripts' ];
		const data = {
			localize: [],
			enqueue: {
				development: {},
				production: {},
			},
		};
		await asyncForEach( jsFiles, async( file ) => {
			const extension = extname( file );
			if ( extension !== '.js' ) {
				return;
			}
			const handlePrefix = 'tribe-scripts-';
			const fileNameArray = file.split( '.' );
			// store chunkhash as version
			const version = fileNameArray[ 1 ];
			// remove chunkhash
			fileNameArray.splice( 1, 1 );
			// remove min
			const minIndex = fileNameArray.indexOf( 'min' );
			if ( minIndex > -1 ) {
				fileNameArray.splice( minIndex, 1 );
			}
			// filename without chunkhash or min
			const fileName = fileNameArray.join( '.' );
			const enqueueType = file.indexOf( '.min.js' ) !== -1 ? '-min' : '';
			const dependencies = getJSDependency( file, target, handlePrefix );
			const enqueueTarget = enqueueType === '-min' ? data.enqueue.production : data.enqueue.development;
			const relativePath = target === 'theme' ? paths.core_theme_relative_js_dist : paths.core_admin_relative_js_dist;
			// only enqueue certain files
			if ( enqueueTargets.includes( fileNameArray[ 0 ] ) ) {
				enqueueTarget[ getAssetHandle( fileName, handlePrefix ) ] = {
					version,
					file: `${ relativePath }${ file }`,
					dependencies,
				};
			}
			data.localize.push( file );
		} );
		writeManifestFile( dir, data );
	},
};
