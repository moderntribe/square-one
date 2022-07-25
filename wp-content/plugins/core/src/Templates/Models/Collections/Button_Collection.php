<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Button;

class Button_Collection extends DataTransferObjectCollection {

	public static function create( array $stats ): Button_Collection {
		return new self( Button::arrayOf( $stats ) );
	}

	public function current(): Button {
		return parent::current();
	}

}
