/**
 * Set up local-config values for use throughout the webpack configs
 */

function module_exists( name ) {
	try {
		return require.resolve( name );
	} catch ( e ) {
		return false;
	}
}

const localConfig = module_exists( '../../local-config.json' ) ? require( '../../local-config.json' ) : {
	proxy: 'square1.tribe',
	certs_path: '',
	protocol: 'http',
};

if ( localConfig.certs_path.length ) {
	localConfig.protocol = 'https';
}

module.exports = localConfig;
