<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Social_Link;

class Social_Link_Collection extends DataTransferObjectCollection {

	public static function create( array $links ): Social_Link_Collection {
		return new self( Social_Link::arrayOf( $links ) );
	}

	public function current(): Social_Link {
		return parent::current();
	}

}
