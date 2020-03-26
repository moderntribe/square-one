<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Sidebar;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

abstract class Abstract_Sidebar extends Abstract_Template {
	protected $path = 'sidebar.twig';

	/**
	 * @var string The ID of this sidebar
	 */
	protected $sidebar_id;

	public function __construct( Environment $twig, Component_Factory $factory ) {
		if ( ! isset( $this->sidebar_id ) ) {
			throw new \UnexpectedValueException( 'Sidebar ID must be defined in extending class' );
		}
		parent::__construct( $twig, $factory );
	}


	public function get_data(): array {
		$sidebar = [];

		$sidebar['active']  = is_active_sidebar( $this->sidebar_id );
		$sidebar['content'] = $sidebar['active'] ? $this->get_dynamic_sidebar() : '';

		return $sidebar;
	}

	public function get_dynamic_sidebar() {
		ob_start();
		dynamic_sidebar( $this->sidebar_id );

		return ob_get_clean();
	}
}
