const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const vendor = require( './vendors.js' );
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
			vendorWebpack: vendor.theme,
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].[chunkhash].min.js',
			path: resolve( `${ __dirname }/../`, pkg._core_theme_js_dist_path ),
			publicPath: `/${ pkg._core_theme_js_dist_path }`,
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
				name: 'vendorWebpack',
				minChunks: 2,
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
