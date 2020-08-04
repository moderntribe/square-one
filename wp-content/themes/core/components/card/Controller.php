<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\card;

/**
 * Class Card
 *
 * @property string   $before_card
 * @property string   $after_card
 * @property array    $title
 * @property array    $text
 * @property array    $image
 * @property string   $pre_title
 * @property string   $post_title
 * @property string[] $card_classes
 * @property string[] $card_header_classes
 * @property string[] $card_content_classes
 * @property array    $button
 */
class Controller {
	private $before_card;
	private $after_card;
	private $title;
	private $text;
	private $image;
	private $pre_title;
	private $post_title;
	private $card_classes;
	private $card_header_classes;
	private $card_content_classes;
	private $button;

	public function __construct( array $args = [] ) {
		$this->before_card          = $args['before_card'] ?? '';
		$this->after_card           = $args['after_card'] ?? '';
		$this->title                = (array) ( $args['title'] ?? [] );
		$this->text                 = (array) ( $args['text'] ?? [] );
		$this->image                = (array) ( $args['image'] ?? [] );
		$this->pre_title            = $args['pre_title'] ?? '';
		$this->post_title           = $args['post_title'] ?? '';
		$this->card_classes         = (array) ( $args['card_classes'] ?? [ 'c-card' ] );
		$this->card_header_classes  = (array) ( $args['card_header_classes'] ?? [ 'c-card__header' ] );
		$this->card_content_classes = (array) ( $args['card_content_classes'] ?? [ 'c-card__content' ] );
		$this->button               = (array) ( $args['button'] ?? [] );
	}

	public static function factory( array $args = [] ): self {
		return new self( $args );
	}

}
