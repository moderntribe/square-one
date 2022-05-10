<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models\Collections;

use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tribe\Project\Templates\Models\Content_Column;

class Content_Column_Collection extends DataTransferObjectCollection {

	public static function create( array $columns ): Content_Column_Collection {
		return new self( Content_Column::arrayOf( $columns ) );
	}

}
