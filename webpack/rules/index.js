const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = [
	{
		test: /\.js$/,
		exclude: [ /(node_modules)/ ],
		use: [
			{
				loader: 'babel-loader',
			},
		],
	},
	{
		test: /\.p?css$/,
		use: [
			MiniCssExtractPlugin.loader,
			{
				loader: 'css-loader',
				options: {
					modules: {
						localIdentName: '[name]__[local]___[hash:base64:5]',
					},
				},
			},
			{ loader: 'postcss-loader' },
		],
	},
];
