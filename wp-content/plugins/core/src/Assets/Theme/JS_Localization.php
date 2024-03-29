<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Theme;

class JS_Localization {

	/**
	 * stores all text strings needed in the scripts.js file
	 *
	 * The code below is an example of structure. Check the theme readme js section for more info on how to use.
	 *
	 * @return array
	 */
	public function get_data(): array {
		return [
			'help_text' => [
				'msg_limit' => __( 'There is a limit to the messages you can post.', 'tribe' ),
			],
			'tooltips'  => [
				'add_to_save'   => __( 'Add Photo to Saved Items', 'tribe' ),
				'in_this_photo' => __( 'Products in this photo', 'tribe' ),
			],
		];
	}

}
