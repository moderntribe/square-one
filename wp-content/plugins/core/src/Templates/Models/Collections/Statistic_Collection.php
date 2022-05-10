<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Statistic;

class Statistic_Collection extends DataTransferObjectCollection {

	public static function create( array $stats ): Statistic_Collection {
		return new self( Statistic::arrayOf( $stats ) );
	}

}
