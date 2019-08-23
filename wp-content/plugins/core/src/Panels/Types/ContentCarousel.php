<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ContentCarousel extends Panel_Type_Config {

	const NAME = 'contentcarousel';

	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_CTA         = 'cta';
	const FIELD_POSTS       = 'posts';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Content Carousel', 'tribe' ) );
		$panel->set_description( __( 'Display content cards within a carousel.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'content-carousel.svg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$posts = new Fields\Post_List([
			'label'            => __( 'Posts', 'tribe' ),
			'name'             => self::FIELD_POSTS,
			'max'              => 12,
			'min'              => 4,
			'show_max_control' => true,
		] );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		$panel->add_field( $posts );

		return $panel;

	}
}
