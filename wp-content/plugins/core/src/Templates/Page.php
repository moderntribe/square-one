<?php


namespace Tribe\Project\Templates;


class Page extends Base {
	public function get_data(): array {
		$data               = parent::get_data();
		$data[ 'post' ]     = $this->get_post();
		$data[ 'comments' ] = $this->get_comments();
		$data[ 'sidebar' ]  = $this->get_sidebar();

		return $data;
	}

	protected function get_post() {
		the_post();
		return [
			'content'        => apply_filters( 'the_content', get_the_content() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];
	}

	protected function get_featured_image() {
		$options = [
			'wrapper_class' => 'page__image',
			'echo'          => false,
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
	}

	protected function get_sidebar() {
		$sidebar = new Sidebar( $this->template, $this->twig, 'sidebar-main' );
		$data = $sidebar->get_data();
		return $data[ 'sidebar' ];
	}

}