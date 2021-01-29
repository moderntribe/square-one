<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\dialog;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Dialog_Controller extends Abstract_Controller {
    public const ID      = 'id';
    public const CONTENT = 'content';
    public const TITLE   = 'title';

	/**
	 * @var string
	 */
	private string $id;
	/**
	 * @var string
	 */
    private string $title;
	/**
	 * @var string|Deferred_Component
	 */
	private $content;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

        $this->id      = (string) $args[ self::ID ];
        $this->content = $args[ self::CONTENT ];
        $this->title   = (string) $args[ self::TITLE ];
	}

	protected function defaults(): array {
		return [
            self::ID      => '',
            self::CONTENT => '',
            self::TITLE   => '',
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
    
    public function get_dialog_title(): string {
		return (string) $this->title;
    }

	public function get_content(): string {
		return (string) $this->content;
	}
}
