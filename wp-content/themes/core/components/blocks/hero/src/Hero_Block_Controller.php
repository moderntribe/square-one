<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\hero\src;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Contracts\Block_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Hero_Block_Controller implements Block_Controller {

	private Hero_Model $model;

	public function __construct( Hero_Model $model ) {
		$this->model = $model;
	}

	public function get_classes(): string {
		$classes   = $this->model->get_classes();
		$classes[] = 'c-block--layout-' . $this->model->layout;

		return Markup_Utils::class_attribute( $classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->model->get_attrs() );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->model->container_classes );
	}

	public function get_media_classes(): string {
		return Markup_Utils::class_attribute( $this->model->media_classes );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->model->content_classes );
	}

	public function get_content_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => $this->model->layout,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-hero__content-container',
				't-theme--light',
			],
		];
	}

	public function get_image_args(): array {
		if ( ! $this->model->media->id ) {
			return [];
		}

		return [
			Image_Controller::IMG_ID       => $this->model->media->id,
			Image_Controller::AS_BG        => true,
			Image_Controller::AUTO_SHIM    => false,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::WRAPPER_TAG  => 'div',
			Image_Controller::CLASSES      => [ 'b-hero__figure', 'c-image--bg', 'c-image--overlay' ],
			Image_Controller::IMG_CLASSES  => [ 'b-hero__img' ],
			Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
			Image_Controller::SRCSET_SIZES => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
			],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-hero__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->model->leadin,
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-hero__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->model->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-hero__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->model->description,
		] );
	}

	private function get_cta(): Deferred_Component {
		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL            => $this->model->cta->link->url,
			Link_Controller::CONTENT        => $this->model->cta->link->title ?: $this->model->cta->link->url,
			Link_Controller::TARGET         => $this->model->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $this->model->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $this->model->cta->aria_label,
			Link_Controller::CLASSES        => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

}
