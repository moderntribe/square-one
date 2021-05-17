module.exports = {
	presets: [
		'@babel/preset-react',
		[
			'@babel/preset-env',
			{
				useBuiltIns: 'entry',
				modules: false,
				corejs: '3.1',
			},
		],
	],
	plugins: [
		'lodash',
		[
			'module-resolver', {
				root: [ '.' ],
				alias: {
					apps: './wp-content/themes/core/assets/js/src/apps',
					config: './wp-content/themes/core/assets/js/src/theme/config',
					common: './wp-content/themes/core/assets/js/src/apps/common',
					components: './wp-content/themes/core/components',
					constants: './wp-content/themes/core/assets/js/src/apps/constants',
					Example: './wp-content/themes/core/assets/js/src/apps/Example',
					integrations: './wp-content/themes/core/integrations',
					pcss: './wp-content/themes/core/assets/css/src/theme',
					utils: './wp-content/themes/core/assets/js/src/utils',
				},
			},
		],
		'@babel/plugin-proposal-object-rest-spread',
		'@babel/plugin-syntax-dynamic-import',
		'@babel/plugin-transform-regenerator',
		'@babel/plugin-proposal-class-properties',
		'@babel/plugin-transform-object-assign',
	],
	env: {
		test: {
			presets: [
				[
					'@babel/preset-env',
					{
						useBuiltIns: 'entry',
						modules: 'commonjs',
						corejs: '3.1',
					},
				],
			],
			plugins: [
				'istanbul',
			],
		},
	},
};
