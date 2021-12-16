/**
 * External Dependencies
 */
const path = require( 'path' );
const webpack = require( 'webpack' );

module.exports = {
	resolve: {
		extensions: [ '.js', '.jsx', '.json', '.pcss' ],
	},
	resolveLoader: {
		modules: [ path.resolve( `${ __dirname }/../../`, 'node_modules' ) ],
	},
	devtool: 'eval-source-map',
	devServer: {
		disableHostCheck: true,
		headers: {
			'Access-Control-Allow-Origin': '*',
		},
		port: 3000,
		hot: true,
	},
	plugins: [
		new webpack.IgnorePlugin( {
			resourceRegExp: /^\.\/locale$/,
			contextRegExp: /moment$/,
		} ),
		new webpack.LoaderOptionsPlugin( {
			debug: true,
		} ),
		new webpack.HotModuleReplacementPlugin(),
	],
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: [ /node_modules\/(?!(swiper|dom7)\/).*/ ],
				use: [
					{
						loader: 'babel-loader',
					},
				],
			},
			{
				test: /\.p?css$/,
				use: [
					'style-loader',
					{
						loader: 'css-loader',
						options: {
							modules: {
								localIdentName: '[name]__[local]___[hash:base64:5]',
							},
							importLoaders: 1,
						},
					},
					'postcss-loader',
				],
			},
			{
				test: /\.css$/,
				include: /node_modules/,
				use: [ 'style-loader', 'css-loader' ],
			},
		],
	},
	optimization: {
		concatenateModules: true, //ModuleConcatenationPlugin
		moduleIds: 'deterministic',
	},
};
