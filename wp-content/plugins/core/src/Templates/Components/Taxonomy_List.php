<?php
namespace Tribe\Project\Templates\Components;

use Tribe\Project\Taxonomies\Category\Category;

class Taxonomy_List extends Component {

	const TEMPLATE_NAME = 'components/taxonomy-list.twig';

	const CONTAINER_CLASSES = 'container_classes';
	const LIST_ITEM_CLASSES = 'list_item_classes';
	const LINK_CLASSES      = 'link_classes';
	const TERM_CLASSES      = 'term_classes';
	const TERMS             = 'terms';
	const TAX               = 'taxonomy';
	const PRIMARY_TAX       = 'primary_taxonomy';
	const ALL               = 'all';
	const SHOW_LINK         = 'show_link';
	const POSTID            = 'post_id';

	protected function parse_options( array $options ): array {

		$defaults = [
			self::CONTAINER_CLASSES     => [],
			self::LIST_ITEM_CLASSES     => [],
			self::LINK_CLASSES          => [],
			self::TERM_CLASSES          => [],
			self::TAX                   => Category::NAME,
			self::PRIMARY_TAX           => Category::NAME,
			self::ALL                   => true,
			self::SHOW_LINK             => true,
			self::POSTID                => get_the_ID()
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {

		$data = [
			self::CONTAINER_CLASSES => $this->merge_classes( [ 'c-taxonomy-list' ], $this->options[ self::CONTAINER_CLASSES ], true ),
			self::LIST_ITEM_CLASSES => $this->merge_classes( [ 'c-taxonomy-list__list-item' ], $this->options[ self::LIST_ITEM_CLASSES ], true ),
			self::LINK_CLASSES      => $this->merge_classes( [ 'c-taxonomy-list__link' ], $this->options[ self::LINK_CLASSES ], true ),
			self::TERM_CLASSES      => $this->merge_classes( [ 'c-taxonomy-list__term' ], $this->options[ self::TERM_CLASSES ], true ),
			self::SHOW_LINK         => $this->options[ self::SHOW_LINK ],
		];

		if( false === $this->options[ self::ALL ] && ! empty( $this->options[ self::PRIMARY_TAX ] ) ) {
			$data[ self::TERMS ] = $this->get_primary_term( $this->options[ self::PRIMARY_TAX ] );
		} else {
			$data[ self::TERMS ] = $this->get_terms();
		}

		return $data;
	}

	public function get_terms(): array {
		$terms = wp_get_object_terms( $this->options[ self::POSTID ], $this->options[ self::TAX ] );

		if( empty( $terms ) ) {
			return [];
		}

		$terms_array = [];
		foreach( $terms as $term ) {

			$terms_array[] = [
				'name'  => esc_html( $term->name ),
				'url'   => get_term_link( $term->term_id, $term->taxonomy )
			];
		}
		return $terms_array;
	}

	/**
	 * Depends on WPSEO to return a primary term. If WPSEO is not installed or activated, will return an empty array
	 *
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	public function get_primary_term( string $taxonomy ): array {

		if( ! class_exists( '\WPSEO_Primary_Term' ) ) {
			return [];
		}

		$term_data = new \WPSEO_Primary_Term( $taxonomy, $this->options[ self::POSTID ] );
		if( empty( $term_data ) ) {
			return [];
		}

		$term = get_term( $term_data->get_primary_term(), $this->options[ self::PRIMARY_TAX ] );

		if( is_wp_error( $term ) ) {
			return $this->get_terms();
		}

		return [
			[
				'name'  => esc_html( $term->name ),
				'url'   => get_term_link( $term->term_id, $this->options[ self::PRIMARY_TAX ] )
			]
		];
	}
}
