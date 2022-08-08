<?php declare(strict_types=1);

namespace Tribe\Project\Admin\Editor;

class Editor_Script_Loader {

	public const TRIBE_TINYMCE_PLUGIN = 'tribe-tinymce';

	/**
	 * Add custom TinyMCE plugins
	 *
	 * @filter mce_external_plugins
	 *
	 * @param array $plugins
	 *
	 * @return array $plugins
	 */
	public function add_mce_plugins( array $plugins ): array {
		$plugins[ self::TRIBE_TINYMCE_PLUGIN ] = trailingslashit( get_template_directory_uri() ) . 'assets/js/src/admin/editor/tribe-tinymce.js';

		return $plugins;
	}

}
