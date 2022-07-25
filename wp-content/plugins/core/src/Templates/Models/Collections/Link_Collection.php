<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Link;

class Link_Collection extends DataTransferObjectCollection {

	public static function create( array $repeater ): Link_Collection {
		return new self( Link::arrayOf( $repeater ) );
	}

	public function current(): Link {
		return parent::current();
	}

}
