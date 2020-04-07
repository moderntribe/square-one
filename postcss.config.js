const postcssFunctions = require( './dev_components/theme/pcss/functions' );
const pkg = require( './package.json' );

module.exports = ( { file, options, env } = {} ) => ( { // eslint-disable-line no-unused-vars
	plugins: {
		'postcss-import': {
			path: [
				`./${ pkg.square1.paths.core_theme }`,
			],
		},
		'postcss-mixins': {},
		'postcss-custom-properties': { preserve: false },
		'postcss-simple-vars': {},
		'postcss-custom-media': {},
		'postcss-functions': { functions: postcssFunctions },
		'postcss-quantity-queries': {},
		'postcss-aspect-ratio': {},
		'postcss-nested': {},
		'postcss-inline-svg': {},
		'postcss-preset-env': { stage: 0, autoprefixer: { grid: true } },
		'postcss-calc': {},
		'postcss-assets': {
			loadPaths: [ `${ pkg.square1.paths.core_theme }/` ],
		},
	},
} );
