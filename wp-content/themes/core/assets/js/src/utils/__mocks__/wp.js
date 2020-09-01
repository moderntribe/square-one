const wpSettingsMock = {};

Object.defineProperty(window, 'modern_tribe_config', {
	value: wpSettingsMock,
});

module.export = wpSettingsMock;
