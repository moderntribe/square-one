<?php


namespace Tribe\Project\Templates\Content\Single;

use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Twig\Stringable_Callable;
use Tribe\Project\Theme\Image_Sizes;

class Post extends Twig_Template {
	protected $time_formats = [
		'c',
	];

	public function get_data(): array {
		$data[ 'post' ] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => new Stringable_Callable( [ $this, 'defer_get_content' ] ),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image( [ 'use_lazyload' => false ] ),
			'time'           => $this->get_time(),
			'date'           => the_date( '', '', '', false ),
			'author'         => $this->get_author(),
			'categories'     => get_categories(),
		];

		$data[ 'related_posts' ] = $this->get_related_posts(get_the_ID());

		return $data;
	}

	public function defer_get_content() {
		return apply_filters( 'the_content', get_the_content() );
	}

	protected function get_featured_image($attrs = []) {
		$default_attrs = [ 'src_size' => 'full', 'srcset_sizes' => [], 'srcset_sizes_attr' => null ];
		$attrs = array_merge( $default_attrs, $attrs );

		$options = [
			'wrapper_class'     => 'item-single__image',
			'echo'              => false,
			'src_size'          => $attrs['src_size'],
			'srcset_sizes'      => $attrs['srcset_sizes'],
			'use_srcset'        => count($attrs['srcset_sizes']) > 1 ? true : false,
			'srcset_sizes_attr' => '(max-width: 767px) 300px, (min-width: 768px) 800px'
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
	}

	protected function get_time() {
		$times = [];
		foreach ( $this->time_formats as $format ) {
			$times[ $format ] = get_the_time( $format );
		}

		return $times;
	}

	protected function get_author() {
		return [
			'name' => get_the_author(),
			'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		];
	}

	protected function get_related_posts($post_id) {
		global $post;
		$args = [
			'exclude' => $post_id,
			'numberposts' => 3
		];

		$posts = get_posts( $args );
		$related_posts = [];

		foreach($posts as $post) {
			setup_postdata($post);
			$related_posts[] = [
				'title'          => get_the_title(),
				'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
				'permalink'      => get_the_permalink(),
				'featured_image' => $this->get_featured_image( [
					'src_size'          => Image_Sizes::CORE_FULL,
					'srcset_sizes'      => [ Image_Sizes::CORE_SQUARE, Image_Sizes::CORE_LANDSCAPE ],
					'srcset_sizes_attr' => '(max-width: 767px) 300px, (min-width: 768px) 800px'
				] ),
				'time'           => $this->get_time(),
				'date'           => get_the_date( '', $post ),
				'date_reduced'   => get_the_date( 'M j', $post ),
				'categories'     => get_categories()
			];
			wp_reset_postdata();
		}

		return $related_posts;
	}

}
