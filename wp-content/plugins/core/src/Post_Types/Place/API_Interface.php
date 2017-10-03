<?php
/**
 * Created by PhpStorm.
 * User: garykovar
 * Date: 9/27/17
 * Time: 2:32 PM
 */

namespace Tribe\Project\Post_Types\Place;

interface API_Interface {
	public function get_place( $post_id );
}