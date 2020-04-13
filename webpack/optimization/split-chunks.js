module.exports = {
	cacheGroups: {
		vendor: {
			test: /[\\/]node_modules[\\/]/,
			name: 'vendor',
			chunks: 'all',
		},
	},
};
