const webpack = require( 'webpack' );

module.exports = {
	devtool: 'eval-source-map',
	devServer: {
		disableHostCheck: true,
		headers: {
			'Access-Control-Allow-Origin': '*',
		},
	},
	plugins: [
		new webpack.HashedModuleIdsPlugin(),
		new webpack.LoaderOptionsPlugin( {
			debug: true,
		} ),
	],
	module: {
		rules: [
			{
				test: /\.pcss$/,
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
		namedModules: true, // NamedModulesPlugin()
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
};
