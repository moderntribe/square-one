<?php  if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Debug_Bar_Actions' ) ) :

class Debug_Bar_Actions extends Debug_Bar_Panel {
	public function init() {
		$this->title( __( 'Actions', 'debug-bar' ) );
	}

	public function prerender() {
		$this->set_visible( true );
	}

	public function render() {
	    // Placeholder
	    echo '{debug_bar_actions_hooks}';
	}
}

endif;
