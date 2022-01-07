<?php declare(strict_types=1);

namespace Tribe\Project\Taxonomies\Example;

use Tribe\Libs\Taxonomy\Taxonomy_Subscriber;

class Subscriber extends Taxonomy_Subscriber {

	// phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
	protected $config_class = Config::class;

}
