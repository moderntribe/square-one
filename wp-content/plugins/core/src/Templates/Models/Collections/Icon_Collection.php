<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Icon;

class Icon_Collection extends DataTransferObjectCollection {

	public static function create( array $repeater ): Icon_Collection {
		return new self( Icon::arrayOf( $repeater ) );
	}

	public function current(): Icon {
		return parent::current();
	}

}
