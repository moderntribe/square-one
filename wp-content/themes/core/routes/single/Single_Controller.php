<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\single;

use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\blocks\hero\src\Hero_Model;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Single_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;
use Tribe\Project\Templates\Components\Traits\Primary_Term;
use Tribe\Project\Theme\Config\Image_Sizes;
use WP_Query;
use WP_Term;

class Single_Controller extends Abstract_Controller {

	use Page_Title;
	use Primary_Term;

	public const SIDEBAR_ID = 'sidebar_id';

	protected string $sidebar_id;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->sidebar_id = (string) $args[ self::SIDEBAR_ID ];
	}

	/**
	 * @TODO remove this example.
	 */
	public function get_hero_model(): Hero_Model {
		$model                  = new Hero_Model();
		$model->title           = 'A Hero Block title';
		$model->description     = 'A Hero Block description';
		$model->content_classes = [
			'some-class',
		];
		$model->media           = new Image( (array) acf_get_attachment( ( new WP_Query( [
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'post_mime_type'         => 'image/jpeg,image/gif,image/jpg,image/png',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'fields'                 => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			] ) )->posts[0] ?? 0 ) );

		return $model;
	}

	public function get_subheader_args(): array {
		global $post;

		$term = $this->get_primary_term( $post->ID );

		$args                                                      = [];
		$args[ Subheader_Controller::TITLE ]                       = $this->get_page_title();
		$args[ Subheader_Single_Controller::DATE ]                 = get_the_date();
		$args[ Subheader_Single_Controller::AUTHOR ]               = get_the_author_meta( 'display_name', $post->post_author );
		$args[ Subheader_Single_Controller::SHOULD_RENDER_BYLINE ] = true;

		if ( $term instanceof WP_Term ) {
			$args[ Subheader_Single_Controller::TAG_NAME ] = $term->name;
			$args[ Subheader_Single_Controller::TAG_LINK ] = get_term_link( $term );
		}

		$args[ Subheader_Controller::CONTENT_CLASSES ] = [ 'l-sink', 'l-sink--double' ];

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

	public function get_term_link_args( WP_Term $term ): array {
		return  [
			Link_Controller::CONTENT => $term->name,
			Link_Controller::URL     => get_term_link( $term ),
			Link_Controller::CLASSES => ['a-tag-link','a-tag-link--secondary','c-tags-list__list_item'],
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
				Image_Controller::CLASSES      => [ 'c-single-featured-image', 'alignwide' ],
				Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
				Image_Controller::SRCSET_SIZES => [
					Image_Sizes::CORE_MOBILE,
					Image_Sizes::CORE_FULL,
				],
				Image_Controller::IMG_ALT_TEXT => ! empty( $alt_text ) ? $alt_text : '',
				Image_Controller::HTML         => ! empty( $caption ) ? '<figcaption class="item-single__featured-image-caption t-caption">' . wp_get_attachment_caption( $image_id ) . '</figcaption>' : '',
			];
	}

	public function get_sidebar_id(): string {
		return $this->sidebar_id;
	}

	public function get_author_id(): string {
		global $post;

		return $post->post_author;
	}

	protected function defaults(): array {
		return [
			self::SIDEBAR_ID => '',
		];
	}

}
