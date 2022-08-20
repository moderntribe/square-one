<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;

class Post_Loop_Model extends Field_Model {

	public Query_Model $query;
	public string $tax_relation = 'AND';
	public string $query_type   = Post_Loop_Field_Middleware::QUERY_TYPE_AUTO;

	/**
	 * The ACF repeater data.
	 *
	 * @var mixed[]
	 */
	public array $manual_posts = [];

	/**
	 * An array of WP_Taxonomy->name => term_ids[]
	 * to filter posts by.
	 *
	 * @note These taxonomies must have show_in_rest.
	 *
	 * @var array<string, int[]>|array
	 */
	public array $taxonomies = [];

	public function __construct( array $parameters = [] ) {
		// Compensate for ACF sending invalid types
		$parameters['taxonomies']   = array_filter( (array) ( $parameters['taxonomies'] ?? [] ) );
		$parameters['manual_posts'] = array_filter( (array) ( $parameters['manual_posts'] ?? [] ) );

		parent::__construct( $parameters );
	}

}
