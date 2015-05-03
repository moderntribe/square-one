module.exports = {
	context: __dirname,
	entry  : '../<%= pkg._themepath %>/js/src/index.js',
	output : {
		filename: 'scripts.js',
		path    : '../<%= pkg._themepath %>/js'
	},

	module: {
		loaders: [
			{test: /\.js$/, loader: 'babel', exclude: /node_modules/}
		]
	}
};