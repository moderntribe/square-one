<?php


namespace Tribe\Project\Service_Providers\Taxonomies;

use Tribe\Project\Taxonomies\Category;

class Category_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Category\Category::class;
}