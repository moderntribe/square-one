<?php

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class %1$s extends ACF\ACF_Meta_Group {

	const NAME = '%2$s';

%7$s

	public function get_keys() {
		return [
%3$s        ];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( '%4$s', 'tribe' ) );

%5$s

		return $group->get_attributes();
	}

%6$s

}