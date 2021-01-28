<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\dialog;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Dialog_Controller extends Abstract_Controller {
    public const TAG     = 'tag';
    public const ID      = 'id';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
    public const CONTENT = 'content';
    public const TITLE   = 'title';

    private string $tag;
    private string $id;
	private array  $classes;
    private array  $attrs;
    private string $title;
	/**
	 * @var string|Deferred_Component
	 */
	private $content;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

        $this->tag     = (string) $args[ self::TAG ];
        $this->id      = (string) $args[ self::ID ];
		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
        $this->content = $args[ self::CONTENT ];
        $this->title   = (string) $args[ self::TITLE ];
	}

	protected function defaults(): array {
		return [
            self::TAG     => 'div',
            self::ID      => '',
			self::CLASSES => [],
			self::ATTRS   => [],
            self::CONTENT => '',
            self::TITLE   => '',
		];
	}


	public function get_tag(): string {
		return tag_escape( $this->tag );
    }
    
    public function get_dialog_id(): string {
		return (string) $this->id;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
    }
    
    public function get_dialog_title(): string {
		return (string) $this->title;
    }

	public function get_content(): string {
		return (string) $this->content;
	}
}
