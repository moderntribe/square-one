<?php


namespace Tribe\Project\Theme\Resources;


class JS_Localization {

	/**
	 * stores all text strings needed in the scripts.js file
	 *
	 * The code below is an example of structure. Check the theme readme js section for more info on how to use.
	 *
	 * @return array
	 */
	public function get_data() {
		$js_i18n_array = array(
			'help_text' => array(
				'msg_limit'    => __( 'There is a limit to the messages you can post.', 'tribe' ),
				'dialog_close' => __( 'Close Popup', 'tribe' ),
			),
			'tooltips' => array(
				'add_to_save'   => __( 'Add Photo to Saved Items', 'tribe' ),
				'in_this_photo' => __( 'Products in this photo', 'tribe' )
			)
		);

		return $js_i18n_array;
	}
}
