module.exports = {
	minSize: 2000,
	cacheGroups: {
		vendor: {
			test: /[\\/]node_modules[\\/]/,
			name: 'vendor',
			chunks: 'all',
		},
	},
};
