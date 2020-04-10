/**
 * External dependencies
 */
const json2php = require( 'json2php' );
const path = require( 'path' );
const { ExternalsPlugin } = require( 'webpack' );
const { writeFile, unlink } = require( 'fs' );
const { defaultRequestToExternal, defaultRequestToHandle } = require( './util' );

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

	/**
	 * Hooks into webpack compiler
	 *
	 * @param {*} compiler
	 * @memberof DependencyExtractionWebpackPlugin
	 */
	apply( compiler ) {
		this.externalsPlugin.apply( compiler );

		const combinedAssetsData = {
			chunks: [],
		};

		const {
			entryPrefix,
			combinedOutputFile,
			outputFormat,
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

				const assetData = {
					// Get a sorted array so we can produce a stable, stringified representation.
					dependencies: Array.from(
						entrypointExternalizedWpDeps
					).sort(),
					version: runtimeChunk.hash.substring( 0, 10 ),
					js: [],
					css: [],
				};

				// Set asset data for use in `afterEmit` hook
				combinedAssetsData[ `${ entryPrefix }${ entrypointName }` ] = assetData;
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
				if ( asset.size === 0 ) {
					acc.push( asset.name );
				}
				return acc;
			}, [] );

			const manifestFile = path.resolve(
				compiler.options.output.path,
				combinedOutputFile ||
						'assets.' + ( outputFormat === 'php' ? 'php' : 'json' )
			);

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

					// Push chunk in with cleaned up filename
					const filename = path.basename( asset );
					combinedAssetsData.chunks.push( filename );

					const combinedEntry = combinedAssetsData[ `${ entryPrefix }${ entryName }` ];
					if ( combinedEntry ) {
						const { js, css } = combinedEntry;
						if ( asset.endsWith( '.css' ) ) {
							css.push(
								path.join( cssDir, asset )
							);
						} else if ( asset.endsWith( '.js' ) ) {
							js.push(
								path.join( jsDir, asset )
							);
						}
					}
				}
			}

			// Write out finalized manifest file to output dir
			writeFile( manifestFile, this.stringify( combinedAssetsData ), err => {
				if ( err ) {
					throw err;
				}
			} );
		} );
	}
}

module.exports = DependencyExtractionWebpackPlugin;
