module.exports = {
	presets: [
		'@babel/preset-react',
		[
			'@babel/preset-env',
			{
				targets: 'last 2 versions, safari >= 13, ios >= 13, android >= 5.1, not ie <= 11, not ie_mob <= 11, not bb <= 10, not samsung 4, not op_mob <= 12.1',
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
						targets: 'last 2 versions, safari >= 13, ios >= 13, android >= 5.1, not ie <= 11, not ie_mob <= 11, not bb <= 10, not samsung 4, not op_mob <= 12.1',
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
