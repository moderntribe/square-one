<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\icon_grid\src;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Contracts\Block_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Icon_Grid_Controller implements Block_Controller {

	private Icon_Grid_Model $model;

	public function __construct( Icon_Grid_Model $model ) {
		$this->model = $model;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->model->get_classes() );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->model->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( [
			'layout-' . $this->model->layout,
		] );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( [
			'g-3-up',
			'g-centered',
		] );
	}

	public function get_header_args(): array {
		if ( empty( $this->model->title ) && empty( $this->model->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LAYOUT  => Content_Block_Controller::LAYOUT_CENTER,
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-icon-grid__header',
			],
		];
	}

	public function get_cta(): Deferred_Component {
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

	public function get_icon_card_args(): array {
		$cards = [];

		if ( ! $this->model->icons->count() ) {
			return $cards;
		}

		foreach ( $this->model->icons as $card ) {
			$cards[] = [
				Card_Controller::STYLE           => Card_Controller::STYLE_PLAIN,
				Card_Controller::TAG             => 'li',
				Card_Controller::CLASSES         => [ 'is-centered-text' ],
				Card_Controller::USE_TARGET_LINK => false,
				Card_Controller::TITLE           => defer_template_part(
					'components/text/text',
					null,
					[
						Text_Controller::TAG     => 'h3',
						Text_Controller::CLASSES => [ 'h5' ],
						Text_Controller::CONTENT => $card->icon_title,
					]
				),
				Card_Controller::DESCRIPTION     => defer_template_part(
					'components/container/container',
					null,
					[
						Container_Controller::CONTENT => wpautop( $card->icon_description ),
						Container_Controller::CLASSES => [ 't-sink', 's-sink' ],
					],
				),
				Card_Controller::IMAGE           => defer_template_part(
					'components/image/image',
					null,
					[
						Image_Controller::IMG_ID       => $card->icon_image->id,
						Image_Controller::AS_BG        => false,
						Image_Controller::SRC_SIZE     => 'medium_large',
						Image_Controller::SRCSET_SIZES => [
							'medium',
							'medium_large',
						],
					],
				),
				Card_Controller::CTA             => defer_template_part(
					'components/link/link',
					null,
					[
						Link_Controller::URL            => $card->cta->link->url,
						Link_Controller::CONTENT        => $card->cta->link->title,
						Link_Controller::TARGET         => $card->cta->link->target,
						Link_Controller::ADD_ARIA_LABEL => $card->cta->add_aria_label,
						Link_Controller::ARIA_LABEL     => $card->cta->aria_label,
						Link_Controller::CLASSES        => [ 'a-cta', 'is-target-link' ],
					]
				),
			];
		}

		return $cards;
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', '', [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-icon-grid__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->model->leadin,
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', '', [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->model->title,
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', '', [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-icon-grid__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->model->description,
		] );
	}

}
