<?php


namespace Tribe\Project\Panels\Types;

use ModularContent\PanelViewFinder;
use ModularContent\TypeRegistry;
use ModularContent\Fields;


abstract class Panel_Type_Config {
	protected $ViewFinder = NULL;
	/**
	 * @var array $post_types
	 *
	 * The post types for which this panel will be available.
	 * Empty array (default) == all post types that support panels
	 */
	protected $post_types = [];

	/** @var \Tribe\Project\Panels\Initializer */
	protected $handler = null;

	public function __construct( $panel_handler ) {
		$this->handler = $panel_handler;
	}

	public function register( TypeRegistry $registry, PanelViewFinder $viewfinder ) {
		$this->ViewFinder = $viewfinder;
		$registry->register( $this->panel(), $this->post_types );
	}

	abstract protected function panel();

	protected function background_color_group( $name, $label = '', $default_color = 'white' ) {
		return new Fields\ImageSelect( [
			'label'   => $label,
			'name'    => $name,
			'options' => [
				'black'     => $this->handler->swatch_icon_url( 'black.png' ),
				'red'       => $this->handler->swatch_icon_url( 'red.png' ),
				'blue'      => $this->handler->swatch_icon_url( 'blue.png' ),
				'green'     => $this->handler->swatch_icon_url( 'green.png' ),
				'orange'    => $this->handler->swatch_icon_url( 'orange.png' ),
				'yellow'    => $this->handler->swatch_icon_url( 'yellow.png' ),
				'sand'      => $this->handler->swatch_icon_url( 'sand.png' ),
				'grey-warm' => $this->handler->swatch_icon_url( 'grey-warm.png' ),
				'grey'      => $this->handler->swatch_icon_url( 'grey.png' ),
				'white'     => $this->handler->swatch_icon_url( 'white.png' )
			],
			'default' => $default_color
		] );
	}
}