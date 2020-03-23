<?php
declare( strict_types=1 );

namespace Tribe\Project\Taxonomies\Example;

use Tribe\Project\Taxonomies\Taxonomy_Subscriber;

class Subscriber extends Taxonomy_Subscriber {
	protected $config_class   = Config::class;
}
