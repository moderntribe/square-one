<?php


namespace Tribe\Project;


use Pimple\Container;

class Service_Loader {
	private $services_requiring_initialization = [];

	private $container;

	public function initialize_services() {
		foreach ( $this->services_requiring_initialization as $key => $method ) {
			$object = $this->container[ $key ];
			call_user_func( [ $object, $method ] );
		}
	}

	public function __construct( Container $container ) {
		$this->container = $container;
	}

	public function enqueue( $key, $method ) {
		$this->services_requiring_initialization[ $key ] = $method;
	}
}