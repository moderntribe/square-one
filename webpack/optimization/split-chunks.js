module.exports = {
	minSize: 5000,
	cacheGroups: {
		vendor: {
			test: /[\\/]node_modules[\\/]/,
			name: 'vendor',
			chunks: 'all',
		},
	},
};
