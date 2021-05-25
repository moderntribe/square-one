<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\dialog;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Dialog_Controller extends Abstract_Controller {

	public const ID                         = 'id';
	public const CONTENT                    = 'content';
	public const TITLE                      = 'title';
	public const DIALOG_CLASSES             = 'dialog_classes';
	public const DIALOG_ATTRIBUTES          = 'dialog_attributes';
	public const OVERLAY_CLASSES            = 'overlay_classes';
	public const OVERLAY_ATTRIBUTES         = 'overlay_attributes';
	public const CONTENT_WRAPPER_CLASSES    = 'content_wrapper_classes';
	public const CONTENT_WRAPPER_ATTRIBUTES = 'content_wrapper_attributes';
	public const CONTENT_CLASSES            = 'content_classes';
	public const CONTENT_ATTRIBUTES         = 'content_attributes';

	private string $id;
	private string $title;

	/**
	 * @var string|\Tribe\Project\Templates\Components\Deferred_Component
	 */
	private $content;
	private array $dialog_classes;
	private array $dialog_attributes;
	private array $overlay_classes;
	private array $overlay_attributes;
	private array $content_wrapper_classes;
	private array $content_wrapper_attributes;
	private array $content_classes;
	private array $content_attributes;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->id                         = (string) $args[ self::ID ];
		$this->content                    = $args[ self::CONTENT ];
		$this->title                      = (string) $args[ self::TITLE ];
		$this->dialog_classes             = (array) $args[ self::DIALOG_CLASSES ];
		$this->dialog_attributes          = (array) $args[ self::DIALOG_ATTRIBUTES ];
		$this->overlay_classes            = (array) $args[ self::OVERLAY_CLASSES ];
		$this->overlay_attributes         = (array) $args[ self::OVERLAY_ATTRIBUTES ];
		$this->content_wrapper_classes    = (array) $args[ self::CONTENT_WRAPPER_CLASSES ];
		$this->content_wrapper_attributes = (array) $args[ self::CONTENT_WRAPPER_ATTRIBUTES ];
		$this->content_classes            = (array) $args[ self::CONTENT_CLASSES ];
		$this->content_attributes         = (array) $args[ self::CONTENT_ATTRIBUTES ];
	}

	protected function defaults(): array {
		return [
			self::ID                         => '',
			self::CONTENT                    => '',
			self::TITLE                      => '',
			self::DIALOG_CLASSES             => [],
			self::DIALOG_ATTRIBUTES          => [],
			self::OVERLAY_CLASSES            => [],
			self::OVERLAY_ATTRIBUTES         => [],
			self::CONTENT_WRAPPER_CLASSES    => [],
			self::CONTENT_WRAPPER_ATTRIBUTES => [],
			self::CONTENT_CLASSES            => [],
			self::CONTENT_ATTRIBUTES         => [],
		];
	}

	protected function required(): array {
		return [
			self::DIALOG_CLASSES          => ['c-dialog'],
			self::OVERLAY_CLASSES         => ['c-dialog__overlay'],
			self::CONTENT_WRAPPER_CLASSES => ['c-dialog__content-wrapper'],
			self::CONTENT_CLASSES         => ['c-dialog__content'],
		];
	}
	
	/*
	* Passing a unique ID is required for the Dialog to function properly,
	* especially when multiple dialogs are used on a page.
	* Typically the block ID itself works best.
	*/
	public function get_dialog_id(): string {
		return (string) $this->id;
	}
	
	public function get_dialog_classes(): string {
		return Markup_Utils::class_attribute( $this->dialog_classes );
	}

	public function get_dialog_attributes(): string {
		return Markup_Utils::concat_attrs( $this->dialog_attributes );
	}

	public function get_overlay_classes(): string {
		return Markup_Utils::class_attribute( $this->overlay_classes );
	}

	public function get_overlay_attributes(): string {
		return Markup_Utils::concat_attrs( $this->overlay_attributes );
	}

	public function get_content_wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->content_wrapper_classes );
	}

	public function get_content_wrapper_attributes(): string {
		return Markup_Utils::concat_attrs( $this->content_wrapper_attributes );
	}

	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_content_attributes(): string {
		return Markup_Utils::concat_attrs( $this->content_attributes );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	public function get_dialog_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-dialog__title',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	public function get_header(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::TAG     => 'header',
			Container_Controller::CLASSES => [
				'c-dialog__header',
			],
			Container_Controller::CONTENT => $this->get_dialog_title() ?? '',
		] );
	}
	

	public function get_content(): string {
		return (string) $this->content;
	}

}
