<?php


namespace Tribe\Project\Service_Providers\Taxonomies;

use Tribe\Project\Post_Types\Sample;
use Tribe\Project\Taxonomies\Example;

class Example_Taxonomy_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Example\Example::class;
	protected $config_class = Example\Config::class;
	protected $post_types = [
		Sample\Sample::NAME,
	];
}
