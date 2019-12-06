<?php


namespace Tribe\Project\Templates\Content\Single;

use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Twig\Stringable_Callable;

class Post extends Twig_Template {
	protected $time_formats = [
		'c',
	];

	public function get_data( $attrs = [] ): array {
		$default_values = [
			'featured_image' => [
				'use_lazyload' => false,
				'link' => '',
			]
		];
		$attrs = collect($default_values)->merge($attrs);

		$data[ 'post' ] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => new Stringable_Callable( [ $this, 'defer_get_content' ] ),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image( $attrs[ 'featured_image' ] ),
			'time'           => $this->get_time(),
			'date'           => the_date( '', '', '', false ),
			'author'         => $this->get_author(),
			'categories'     => $this->get_categories(),
		];

		return $data;
	}

	public function defer_get_content() {
		return apply_filters( 'the_content', get_the_content() );
	}

	protected function get_featured_image( $attrs = [] ) {
		$default_attrs = [ 'src_size' => 'full', 'srcset_sizes' => [], 'srcset_sizes_attr' => null ];

		$options = [
			'wrapper_class'     => 'item-single__image',
			'echo'              => false
		];

		$attrs = array_merge( $default_attrs, $attrs, $options );

		return the_tribe_image( get_post_thumbnail_id(), $attrs );
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

	protected function get_categories() {
		return collect( get_categories() )->map( function( $term ){
			$term = (array) $term;
			$term['permalink'] = get_category_link( $term['term_id'] );
			return $term;
		} );
	}

}
