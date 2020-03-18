<?php


namespace Tribe\Project\Service_Providers\Post_Types;

use Tribe\Project\Post_Types\Sample;

class Sample_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Sample\Sample::class;
	protected $config_class = Sample\Config::class;
}
