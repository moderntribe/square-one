<?php


namespace Tribe\Project\Service_Providers\Taxonomies;

use Tribe\Project\Taxonomies\Post_Tag;

class Post_Tag_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Post_Tag\Post_Tag::class;
}
