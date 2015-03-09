<?php

class Debug_Bar_Extender_Panel extends Debug_Bar_Panel {
	
	private $tab_name;
	private $tab;
	private $callback;

	public function init() {
		$this->title( $this->tab );
	}

	public function set_tab( $name, $callback ) {
		$this->tab_name = strtolower( preg_replace( "#[^a-z0-9]#msiU", "", $name ) );
		$this->tab = $name;
		$this->callback = $callback;
		$this->title( $this->tab );
	}
	
	public function prerender() {
		$this->set_visible( true );
	}

	public function render() {
		echo call_user_func( $this->callback );
	}
}
