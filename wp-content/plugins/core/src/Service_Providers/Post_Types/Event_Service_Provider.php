<?php


namespace Tribe\Project\Service_Providers\Post_Types;

use Pimple\Container;
use Tribe\Project\Post_Types\Event;

class Event_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Event\Event::class;

	public function register( Container $container ) {
		parent::register( $container );
	}
}