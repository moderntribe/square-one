<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Accordion_Row;

class Accordion_Row_Collection extends DataTransferObjectCollection {

	public static function create( array $rows ): Accordion_Row_Collection {
		return new self( Accordion_Row::arrayOf( $rows ) );
	}

	public function current(): Accordion_Row {
		return parent::current();
	}

}
