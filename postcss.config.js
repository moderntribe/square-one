const pkg = require( './package.json' );

module.exports = ( { file, options, env } = {} ) => ( { // eslint-disable-line no-unused-vars
	plugins: {
		'postcss-import': {
			path: [
				`./${ pkg._core_theme_path }/`,
			],
		},
		'postcss-mixins': {},
		'postcss-custom-properties': {},
		'postcss-simple-vars': {},
		'postcss-custom-media': {},
		'postcss-quantity-queries': {},
		'postcss-aspect-ratio': {},
		'postcss-nested': {},
		'postcss-inline-svg': {},
		'postcss-preset-env': { stage: 0 },
		'postcss-calc': {},
	},
} );
