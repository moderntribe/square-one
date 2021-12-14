const TerserPlugin = require( 'terser-webpack-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );

module.exports = [
	new TerserPlugin( {
		cache: true,
		parallel: true,
		sourceMap: false, // Must be set to true if using source-maps in production
		extractComments: false,
		terserOptions: {
			compress: {
				warnings: false,
				drop_console: true,
			},
			output: {
				comments: false,
			},
		},
	} ),
	new CssMinimizerPlugin(),
];
