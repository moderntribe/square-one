module.exports = {
	mac: {},
	win: {
		options: {
			eol: 'crlf',
			overwrite: true,
		},
		files: {
			'': [
				'<%= pkg._core_theme_js_vendor_path %>**/*',
				'<%= pkg._core_theme_js_dist_path %>**/*',
				'<%= pkg._core_theme_css_path %>**/*',
			],
		},
	},
};
