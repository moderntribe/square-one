<?php
/**
 * Functions: Localization
 *
 * Functions for translation, namespacing, and localization
 *
 * @since tribe-square-one 1.0
 */


/**
 * tribe_js_i18n stores all text strings needed in the scripts.js file
 *
 * The code below is an example of structure. Check the theme readme js section for more info on how to use.
 *
 * @return array
 */

function tribe_js_i18n() {

    $js_i18n_array = array(
        'help_text' => array(
            'msg_limit'   => __( 'There is a limit to the messages you can post.' )
        ),
        'tooltips' => array(
            'add_to_save'   => __( 'Add Photo to Saved Items' ),
            'in_this_photo' => __( 'Products in this photo' )
        )
    );

    return $js_i18n_array;

}