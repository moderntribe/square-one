<?php


namespace JSONLD;


class Post_Data_Builder extends Data_Builder {
	protected function build_data() {
		/** @var \WP_Post $post */
		$post = $this->object;

		$title = $this->get_post_title( $post );
		$type = $this->get_post_item_type( $post );
		$content_key = $type == 'NewsArticle' ? 'articleBody' : 'text';

		$data = [
			'@context'        => 'https://schema.org',
			'@type'           => $type,
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id'   => get_the_permalink( $post ),
			],
			'datePublished'   => get_the_date( 'Y-m-d H:i:s', $post ),
			'dateModified'    => get_the_modified_date( 'Y-m-d H:i:s' ),
			'description'     => $this->get_post_description( $post ),
			'name'            => $title,
			'headline'        => $title,
			'copyrightHolder' => $this->get_option( Settings_Page::ORG_COPYRIGHT_HOLDER),
			'creator'         => $this->get_option( Settings_Page::ORG_CREATOR ),
			'image'           => $this->get_featured_image(),
			'thumbnailUrl'    => $this->get_featured_image( false ),
			'url'             => get_the_permalink( $post ),
		];

		if ( 'post' === $post->post_type ) {
			$data['author'] = [
				'@type' => 'Person',
				'name' => $this->get_author_name( $post ),
			];
			$data['publisher'] = [
				'@type' => 'Organization',
				'name'  => $this->get_option( Settings_Page::ORG_NAME ),
				'logo'  => $this->get_organization_logo(),
			];
		}

		$data[$content_key] = $this->get_post_content( $post );

		return $data;
	}

	/**
	 * @param \WP_Post $post
	 * @return string
	 */
	protected function get_post_description( $post ) {
		if( class_exists( 'WPSEO_Frontend' ) ){
			$object = \WPSEO_Frontend::get_instance();
			$excerpt = $object->metadesc( false );
			if( empty( $excerpt ) ){
				$excerpt = $post->post_excerpt;
			}
			return $excerpt;
		} else {
			return $post->post_excerpt;
		}
	}

	/**
	 * @param \WP_Post $post
	 * @return string
	 */
	protected function get_post_title( $post ) {
		if( class_exists( 'WPSEO_Frontend' ) ){
			$object = \WPSEO_Frontend::get_instance();
			return $object->title( false );
		} else {
			return get_the_title( $post );
		}
	}

	/**
	 * @param \WP_Post $post
	 * @return string
	 */
	protected function get_post_content( $post ) {
		$content = apply_filters( 'the_content', $post->post_content );
		$content .= $this->get_panel_content( $post );
		return $content;
	}

	/**
	 * @param \WP_Post $post
	 * @return string
	 */
	protected function get_panel_content( $post ) {
		if ( ! class_exists( 'ModularContent\\PanelCollection' ) ) {
			return '';
		}

		$content = '';

		$panel_collection = \ModularContent\PanelCollection::find_by_post_id( $post->ID );
		foreach ( $panel_collection->panels() as $panel ) {
			$content .= $this->get_single_panel_content( $panel );
		}

		return $content;
	}

	/**
	 * @param \ModularContent\Panel $panel
	 * @return string
	 */
	protected function get_single_panel_content( $panel ) {
		$extractor = new Panel_Data_Extractor( $panel );
		return $extractor->extract();
	}

	protected function get_featured_image( $object = true ) {
		$post = $this->object;
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		if ( empty( $post_thumbnail_id ) ) {
			$post_thumbnail_id = $this->get_option( Settings_Page::ARTICLE_DEFAULT_IMAGE );
		}
		return $this->get_image( $post_thumbnail_id, $object, 'full' );
	}

	/**
	 * @param \WP_Post $post
	 * @return string
	 */
	protected function get_author_name( $post ) {

		$user_info = get_userdata( $post->post_author );
		if( ! empty( $user_info->first_name ) && ! empty( $user_info->last_name ) ){
			$name = sprintf( '%s %s', $user_info->first_name, $user_info->last_name );
		} else {
			$name = $user_info->display_name;
		}
		return $name;
	}

	/**
	 * @param $post
	 *
	 * @return string
	 */
	protected function get_post_item_type( $post ) {

		$item_type = $this->get_option( Settings_Page::ARTICLE_DEFAULT_TYPE );

		if ( 'page' === $post->post_type ) {
			$item_type = 'WebPage';
		}

		return $item_type;

	}

}