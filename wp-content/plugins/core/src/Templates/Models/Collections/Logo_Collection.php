<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Logo;

class Logo_Collection extends DataTransferObjectCollection {

	public static function create( array $repeater ): Logo_Collection {
		return new self( Logo::arrayOf( $repeater ) );
	}

	public function current(): Logo {
		return parent::current();
	}

}
