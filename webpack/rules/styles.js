const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	test: /\.p?css$/,
	use: [
		MiniCssExtractPlugin.loader,
		{
			loader: 'css-loader',
			options: {
				modules: {
					localIdentName: '[name]__[local]___[hash:base64:5]',
				},
				importLoaders: 1,
				sourceMap: true,
			},
		},
		{
			loader: 'postcss-loader',
			options: {
				sourceMap: true,
			},
		},
	],
};
