<?php


namespace Tribe\Project\Service_Providers\Post_Types;

use Tribe\Project\Post_Types\Place;

class Place_Post_Type_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Place\Place::class;
	protected $config_class = Place\Config::class;
}