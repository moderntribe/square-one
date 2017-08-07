<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Twig\Twig_Template;

class Slider extends Twig_Template {

	protected $images = [];
	protected $show   = true;

	public function __construct( $images, $show, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->images = $images;
		$this->show   = $show;
	}

	public function get_data(): array {
		$data = [
			'slides' => $this->get_slides(),
			'show'   => $this->show,
		];

		return $data;
	}

	protected function get_slides(): array {
		$slides = [];

		if ( empty( $this->images ) ) {
			return $slides;
		}

		$options = [
			'as_bg' => false,
		];

		foreach ( $this->images as $image ) {
			$image_obj = Image::factory( $image, $options );
			$slides[]  = $image_obj->render();
		}

		return $slides;
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $images
	 * @param        $show
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $images, $show = true, $template = 'components/card.twig' ) {
		return new static( $images, $show, $template );
	}
}