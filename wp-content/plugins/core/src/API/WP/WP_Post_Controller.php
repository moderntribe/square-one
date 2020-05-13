<?php
/**
 * The API class that handles integrations with WordPress.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\WP;

use Tribe\Project\API\Indexed_Objects\Indexable;
use Tribe\Project\API\Indexed_Objects\Indexable_Factory;
use Tribe\Project\API\Indexer\Indexer_Service;
use Tribe\Project\DB\Exceptions\IndexableNotFoundException;

/**
 * Class API.
 */
class WP_Post_Controller implements Hookable {

	/**
	 * @var Indexer_Service
	 */
	protected $indexer;

	/**
	 * @var Indexable_Factory
	 */
	protected $factory;

	/**
	 * WP_Post_Controller constructor.
	 *
	 * @param Indexer_Service   $indexer
	 * @param Indexable_Factory $factory
	 */
	public function __construct( Indexer_Service $indexer, Indexable_Factory $factory ) {
		$this->indexer = $indexer;
		$this->factory = $factory;
	}

	/**
	 * @inheritDoc
	 */
	public function save_post( object $post ): void {
		$indexable = $this->get_indexable( $post );

		if ( ! $indexable ) {
			return;
		}

		$this->indexer->save( $indexable );
	}

	/**
	 * @inheritDoc
	 */
	public function delete_post( object $post ): void {
		$indexable = $this->get_indexable( $post );

		if ( ! $indexable ) {
			return;
		}

		$this->indexer->delete( $indexable );
	}

	/**
	 * Uses the factory to create the right indexable object.
	 *
	 * @param object $post The post object.
	 *
	 * @return Indexable|null
	 */
	protected function get_indexable( object $post ): ?Indexable {
		$post_type = $post->post_type ?? ( isset( $post->ID ) ? get_post_type( $post->ID ) : '' );

		try {
			return $this->factory->make( $post_type );
		} catch ( IndexableNotFoundException $e ) {
			return;
		}
	}
}
