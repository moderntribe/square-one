const webpack = require('webpack');

module.exports = {
	devtool: 'eval-source-map',
	devServer: {
		headers: {
			'Access-Control-Allow-Origin': '*'
		}
	},
	plugins: [
		new webpack.HashedModuleIdsPlugin(),
		new webpack.LoaderOptionsPlugin({
			debug: true
		})
	],
	module: {
		rules: [
			{
				test: /\.pcss$/,
				loader: 'style-loader!css-loader?modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]!postcss-loader',
			},
			{
				test: /\.css$/,
				include: /node_modules/,
				loaders: ['style-loader', 'css-loader'],
			},
		],
	},
	optimization: {
		namedModules: true, // NamedModulesPlugin()
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true //ModuleConcatenationPlugin
	}
};
