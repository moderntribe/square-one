<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\single;

use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Single_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\tags_list\Tags_List_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;
use Tribe\Project\Templates\Components\Traits\Primary_Term;
use Tribe\Project\Theme\Config\Image_Sizes;

class Single_Controller extends Abstract_Controller {

	use Page_Title;
	use Primary_Term;

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	public function get_subheader_args(): array {
		global $post;

		$term = $this->get_primary_term( $post->ID );

		$args[ Subheader_Single_Controller::TITLE ]                = $this->get_page_title();
		$args[ Subheader_Single_Controller::DATE ]                 = get_the_date();
		$args[ Subheader_Single_Controller::AUTHOR ]               = get_the_author_meta( 'display_name', $post->post_author );
		$args[ Subheader_Single_Controller::SHOULD_RENDER_BYLINE ] = true;

		if ( $term instanceof \WP_Term ) {
			$args[ Subheader_Single_Controller::TAG_NAME ] = $term->name;
			$args[ Subheader_Single_Controller::TAG_LINK ] = get_term_link( $term );
		}

		return $args;
	}

	public function get_taxonomy_terms(): array {
		global $post;

		$terms = get_the_terms( $post->ID, Category::NAME );
		if ( ! $terms ) {
			return [];
		}

		return $terms;
	}

	public function get_tags_list_args(): array {
		$tags_list = [];
		/** @var \WP_Term $term */
		foreach ( $this->get_taxonomy_terms() as $term ) {
			$tags_list[ $term->name ] = get_term_link( $term );
		}

		return [
			Tags_List_Controller::TAGS => $tags_list,
		];
	}

	public function get_featured_image_args(): array {
		global $post;
		$image_id = (int) get_post_thumbnail_id( $post->ID );
		$caption  = (string) wp_get_attachment_caption( $image_id );
		$alt_text = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );

		return [
				Image_Controller::IMG_ID       => $image_id,
				Image_Controller::AS_BG        => false,
				Image_Controller::CLASSES      => [ 'c-single-featured-image' ],
				Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
				Image_Controller::SRCSET_SIZES => [
					Image_Sizes::CORE_MOBILE,
					Image_Sizes::CORE_FULL,
				],
				Image_Controller::IMG_ALT_TEXT => ! empty( $alt_text ) ? $alt_text : '',
				Image_Controller::HTML         => ! empty( $caption ) ? '<figcaption class="item-single__featured-image-caption">' . wp_get_attachment_caption( $image_id ) . '</figcaption>' : '',
			];
	}

}
