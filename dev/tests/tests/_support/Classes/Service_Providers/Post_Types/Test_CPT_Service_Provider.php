<?php

namespace Tribe\Tests\Service_Providers\Post_Types;

use Tribe\Project\Service_Providers\Post_Types\Post_Type_Service_Provider;
use Tribe\Tests\Post_Types\Test_CPT\Config;
use Tribe\Tests\Post_Types\Test_CPT\Test_CPT;

class Test_CPT_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Test_CPT::class;
	protected $config_class = Config::class;
}