<?php

namespace Tribe\Project\Syndicate;


class Content {

	private function id_in_blog( $id ) {
		return (bool) filter_var( $id, FILTER_VALIDATE_INT, [
			'options' => [
				'min_range' => get_current_blog_id() * Blog::OFFSET,
				'max_range' => ( ( get_current_blog_id() + 1 ) * Blog::OFFSET ) - 1,
			],
		] );
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 * @filter the_content
	 */
	public function fix_site_links( $content ) {
		$site_url      = get_site_url();
		$main_site_url = get_site_url( BLOG_ID_CURRENT_SITE );

		$content = str_replace( $main_site_url, $site_url, $content );

		if ( ! SUBDOMAIN_INSTALL ) {
			$site = get_blog_details();

			$content = str_replace( $site->path . substr( $site->path, 1 ), $site->path, $content );
		}

		return $content;
	}


	public function fix_attachment_image_src( $image, $attachment_id, $size, $icon ) {
		if ( SITE_ID_CURRENT_SITE === get_current_blog_id() || $this->id_in_blog( $attachment_id ) ) {
			return $image;
		}

		$image['src'] = str_replace( get_site_url( get_current_blog_id() ), get_site_url( SITE_ID_CURRENT_SITE ), $image['src'] );

		return $image;
	}
}
