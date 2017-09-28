<?php


namespace Tribe\Project\Templates;


use Tribe\Project\Theme\Social_Links;

class Single extends Base {
	public function get_data(): array {
		$data               = parent::get_data();
		$data[ 'post' ]     = $this->get_post();
		$data[ 'comments' ] = $this->get_comments();
		$data[ 'social_share' ] = $this->get_social_share();

		return $data;
	}

	protected function get_components() {
		return [
			new Components\Place( $this->template, $this->twig, [] ),
		];
	}

	protected function get_post() {
		// can't use get_components because we need to setup global postdata first
		$template = new Content\Single\Post( $this->template, $this->twig );
		the_post();
		$data = $template->get_data();
		return $data[ 'post' ];
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
	}

	protected function get_social_share() {
		$social = new Social_Links( [ 'facebook', 'twitter', 'google', 'linkedin', 'email' ], false );
		return $social->format_links( $social->get_links() );
	}

}