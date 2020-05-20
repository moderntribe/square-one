<?php
/**
 * A response object.
 *
 * @package Square1-REST
 */
declare( strict_types=1 );

namespace Tribe\Project\REST\Models;

use WP_Post;
use WP_Term;

/**
 * Class Sample_Response_Object.
 */
class Sample_Response_Object {

	/**
	 * Public object attributes define how the response data is structured.
	 *
	 * @var int Term ID.
	 */
	public $ID;

	/**
	 * Term_Response_Object constructor.
	 *
	 * @param WP_Post $sample Any object.
	 */
	public function __construct( WP_Post $sample ) {
		$this->ID = $sample->ID;
	}
}
