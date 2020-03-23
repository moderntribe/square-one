<?php
declare( strict_types=1 );

namespace Tribe\Project\Post_Types\Sample;

use Tribe\Project\Post_Types\Post_Type_Subscriber;

class Subscriber extends Post_Type_Subscriber {
	protected $config_class = Config::class;
}
