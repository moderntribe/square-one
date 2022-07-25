<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Tab;

class Tab_Collection extends DataTransferObjectCollection {

	public static function create( array $tabs ): Tab_Collection {
		return new self( Tab::arrayOf( $tabs ) );
	}

	public function current(): Tab {
		return parent::current();
	}

}
