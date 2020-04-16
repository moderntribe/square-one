/**
 * External dependencies
 */
const json2php = require( 'json2php' );
const path = require( 'path' );
const { ExternalsPlugin } = require( 'webpack' );
const { writeFile, unlink } = require( 'fs' );
const { defaultRequestToExternal, defaultRequestToHandle } = require( './util' );

// todo: temp workaround, these names are unsafe, maybe someone makes a master or print chunk

const excludeFromJS = [
	'components',
	'legacy',
	'master',
	'print',
	'gravity-forms',
	'editor-style',
	'login',
];


class DependencyExtractionWebpackPlugin {
	constructor( options ) {
		this.options = Object.assign(
			{
				combinedOutputFile: null,
				outputFormat: 'php',
				useDefaults: true,
				entryPrefix: '',
			},
			options
		);

		// Track requests that are externalized.
		//
		// Because we don't have a closed set of dependencies, we need to track what has
		// been externalized so we can recognize them in a later phase when the dependency
		// lists are generated.
		this.externalizedDeps = new Set();

		// Offload externalization work to the ExternalsPlugin.
		this.externalsPlugin = new ExternalsPlugin(
			'this',
			this.externalizeWpDeps.bind( this )
		);
	}

	externalizeWpDeps( context, request, callback ) {
		let externalRequest;

		// Handle via options.requestToExternal first
		if ( typeof this.options.requestToExternal === 'function' ) {
			externalRequest = this.options.requestToExternal( request );
		}

		// Cascade to default if unhandled and enabled
		if (
			typeof externalRequest === 'undefined' &&
			this.options.useDefaults
		) {
			externalRequest = defaultRequestToExternal( request );
		}

		if ( externalRequest ) {
			this.externalizedDeps.add( request );

			return callback( null, { this: externalRequest } );
		}

		return callback();
	}

	mapRequestToDependency( request ) {
		// Handle via options.requestToHandle first
		if ( typeof this.options.requestToHandle === 'function' ) {
			const scriptDependency = this.options.requestToHandle( request );
			if ( scriptDependency ) {
				return scriptDependency;
			}
		}

		// Cascade to default if enabled
		if ( this.options.useDefaults ) {
			const scriptDependency = defaultRequestToHandle( request );
			if ( scriptDependency ) {
				return scriptDependency;
			}
		}

		// Fall back to the request name
		return request;
	}

	/**
	 * Stringify asset data
	 *
	 * @param {Object} asset Asset data
	 * @returns {String} JSON string
	 */
	stringify( asset ) {
		if ( this.options.outputFormat === 'php' ) {
			return `<?php return ${ json2php(
				JSON.parse( JSON.stringify( asset ) )
			) };`;
		}

		return JSON.stringify( asset );
	}

	writeManifestFile( {
		manifestDir,
		combinedAssetData,
	} ) {
		const {
			combinedOutputFile,
			outputFormat,
		} = this.options;

		const manifestFile = path.resolve(
			manifestDir,
			combinedOutputFile ||
						'assets.' + ( outputFormat === 'php' ? 'php' : 'json' )
		);

		const finalizedData = Object.entries( combinedAssetData ).reduce( ( acc, [ entryName, entry ] ) => {
			if ( entryName === 'chunks' || entry.file.length ) {
				acc[ entryName ] = entry;
			}
			return acc;
		}, {} );

		// Write out finalized manifest file to output dir
		writeFile( manifestFile, this.stringify( finalizedData ), err => {
			if ( err ) {
				throw err;
			}
		} );
	}

	addToData( {
		combinedAssetData,
		assetDir,
		filename,
		asset,
		entryName,
	} ) {
		const {
			entryPrefix,
		} = this.options;

		// Push chunk in with cleaned up filename
		combinedAssetData.chunks.push( filename );

		const combinedEntry = combinedAssetData[ `${ entryPrefix }${ entryName }` ];
		if ( combinedEntry ) {
			const { file } = combinedEntry;
			file.push(
				path.join( assetDir, asset )
			);
		}
	}

	/**
	 * Hooks into webpack compiler
	 *
	 * @param {*} compiler
	 * @memberof DependencyExtractionWebpackPlugin
	 */
	apply( compiler ) {
		this.externalsPlugin.apply( compiler );

		const combinedAssetsJSData = {
			chunks: [],
		};

		const combinedAssetsCSSData = {
			chunks: [],
		};

		const {
			entryPrefix,
			// Default to compiler output if not set
			jsDir = compiler.options.output.path,
			cssDir = compiler.options.output.path,
		} = this.options;

		//
		// ────────────────────────────────────────────────────────── I ──────────
		//   :::::: E M I T   H O O K : :  :   :    :     :        :          :
		// ────────────────────────────────────────────────────────────────────
		//
		compiler.hooks.emit.tap( this.constructor.name, ( compilation ) => {
			// Process each entry point independently.
			for ( const [
				entrypointName,
				entrypoint,
			] of compilation.entrypoints.entries() ) {
				const entrypointExternalizedWpDeps = new Set();

				// Search for externalized modules in all chunks.
				for ( const chunk of entrypoint.chunks ) {
					for ( const { userRequest } of chunk.modulesIterable ) {
						if ( this.externalizedDeps.has( userRequest ) ) {
							const scriptDependency = this.mapRequestToDependency(
								userRequest
							);
							entrypointExternalizedWpDeps.add(
								scriptDependency
							);
						}
					}
				}

				const runtimeChunk = entrypoint.getRuntimeChunk();

				const assetJSData = {
					// Get a sorted array so we can produce a stable, stringified representation.
					dependencies: Array.from(
						entrypointExternalizedWpDeps
					).sort(),
					version: runtimeChunk.hash.substring( 0, 10 ),
					file: [],
				};
				const assetCSSData = {
					dependencies: [], // Not used
					version: runtimeChunk.hash.substring( 0, 10 ),
					file: [],
				};

				// Set asset data for use in `afterEmit` hook
				combinedAssetsJSData[ `${ entryPrefix }${ entrypointName }` ] = assetJSData;
				combinedAssetsCSSData[ `${ entryPrefix }${ entrypointName }` ] = assetCSSData;
			}
		} );

		//
		// ──────────────────────────────────────────────────────────────────── II ──────────
		//   :::::: A F T E R E M I T   H O O K : :  :   :    :     :        :          :
		// ──────────────────────────────────────────────────────────────────────────────
		//
		compiler.hooks.afterEmit.tap( this.constructor.name, ( compilation ) => {
			const stats = compilation.getStats().toJson();
			const emptyAssets = stats.assets.reduce( ( acc, asset ) => {
				if ( asset.name.indexOf( '.css' ) !== -1 ) {
					return acc;
				}
				if ( // todo: find a better way to handle these empty js files emitted when css is being created
					excludeFromJS.some( v => asset.name.includes( v ) )
				) {
					acc.push( asset.name );
				}
				return acc;
			}, [] );

			// Delete all empty files (e.g. completely extracted CSS files)
			emptyAssets.forEach( asset => {
				const filepath = path.resolve( compiler.options.output.path, asset );

				unlink( filepath, err => {
					if ( err ) {
						throw err;
					}
				} );
			} );

			/**
			 * Perform file path lookup of assets and put assets into their determined file type
			 * if the chunk is part of an entry
			 *
			 * Don't:
			 * - Include empty assets as they were already removed
			 *
			 * Do:
			 * - Only included css and js files
			 * - Keep track of all chunks for browser cache invalidation
			 *
			 */
			for ( const [ entryName, assets ] of Object.entries( stats.assetsByChunkName ) ) {
				for ( const asset of assets ) {
					// Don't include empty assets
					if ( emptyAssets.includes( asset ) ) {
						continue;
					}

					const filename = path.basename( asset );

					if ( filename.endsWith( '.js' ) ) {
						this.addToData( {
							asset,
							assetDir: jsDir,
							combinedAssetData: combinedAssetsJSData,
							entryName,
							filename,
						} );
					} else if ( filename.endsWith( '.css' ) ) {
						this.addToData( {
							asset,
							assetDir: cssDir,
							combinedAssetData: combinedAssetsCSSData,
							entryName,
							filename,
						} );
					}
				}
			}

			this.writeManifestFile( {
				manifestDir: jsDir,
				combinedAssetData: combinedAssetsJSData,
			} );
			this.writeManifestFile( {
				manifestDir: cssDir,
				combinedAssetData: combinedAssetsCSSData,
			} );
		} );
	}
}

module.exports = DependencyExtractionWebpackPlugin;
