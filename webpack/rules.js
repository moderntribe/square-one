const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	miniExtractPlugin: {
		test: /\.(pcss|css)$/,
		use: [
			MiniCssExtractPlugin.loader,
			{
				loader: 'css-loader',
				options: {
					modules: true,
					localIdentName: '[name]__[local]___[hash:base64:5]',
				},
			},
			{ loader: 'postcss-loader' },
		],
	},
};
