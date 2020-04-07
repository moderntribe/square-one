const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const common = require( './common.js' );
const dev = require( './themedev' );
const merge = require( 'webpack-merge' );
const TerserPlugin = require( 'terser-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const OptimizeCSSAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const pkg = require( '../package.json' );

module.exports = merge.strategy( {
	cache: 'replace',
	mode: 'replace',
	entry: 'replace',
	output: 'replace',
	plugins: 'replace',
	optimization: 'replace',
} )(
	common,
	dev,
	{
		cache: false,
		mode: 'production',
		entry: {
			scripts: dev.entry.scripts,
			integrations: dev.entry.integrations,
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_theme_js_dist ),
			publicPath: `/${ pkg.square1.paths.core_theme_js_dist }`,
		},
		plugins: [
			new webpack.DefinePlugin( {
				'process.env': { NODE_ENV: JSON.stringify( 'production' ) },
			} ),
			new webpack.LoaderOptionsPlugin( {
				debug: false,
			} ),
			new MiniCssExtractPlugin( {
				filename: '../../css/dist/[name].min.css',
			} ),
		],
		optimization: {
			splitChunks: { // CommonsChunkPlugin()
				cacheGroups: {
					vendor: {
						test: /[\\/]node_modules[\\/]/,
						name: 'vendor',
						chunks: 'all',
					},
				},
			},
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true, //ModuleConcatenationPlugin
			minimizer: [
				new TerserPlugin( {
					cache: true,
					parallel: true,
					sourceMap: true,
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
				new OptimizeCSSAssetsPlugin( {} ),
			],
		},
	}
);
