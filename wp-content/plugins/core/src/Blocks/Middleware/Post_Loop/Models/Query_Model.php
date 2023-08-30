<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;

class Query_Model extends Field_Model {

	/**
	 * How many posts to display, as selected by the user.
	 */
	public int $limit = 0;

	/**
	 * The post types the user selected.
	 *
	 * @var string[]
	 */
	public array $post_types = [];

	public string $order    = Post_Loop_Field_Middleware::OPTION_DESC;
	public string $order_by = Post_Loop_Field_Middleware::OPTION_DATE;

}
