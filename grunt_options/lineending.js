module.exports = {
	mac: {},
	win: {
		options: {
			eol: 'crlf',
			overwrite: true,
		},
		files: {
			'': [
				'<%= pkg._core_theme_assets_path %>/js/vendor/*',
				'<%= pkg._core_plugin_assets_path %>/js/dist/*',
				'<%= pkg._core_plugin_assets_path %>/css/*',
			],
		},
	},
};
