<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class PostLoop extends Panel_Type_Config {

	const NAME = 'postloop';

	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_POSTS       = 'posts';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Post Loop', 'tribe' ) );
		$panel->set_description( __( 'Displays a List of Posts.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'query.svg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		// Posts.
		$posts = new Fields\Post_List([
			'label'            => __( 'Posts', 'tribe' ),
			'name'             => self::FIELD_POSTS,
			'max'              => 8,
			'min'              => 1,
			'show_max_control' => true,
		] );

		$panel->add_field( $posts );

		return $panel;

	}
}
