<?php

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class %1$s extends ACF\ACF_Meta_Group {

	const NAME = '%2$s';

%3$s

	public function get_keys() {
		return [
%4$s    ];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( '%5$s', 'tribe' ) );

		%6$s

		return $group->get_attributes();
	}

	%7$s

}