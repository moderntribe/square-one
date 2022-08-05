<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Patterns;

/**
 * Class Block_Pattern_Base
 *
 * Edits the block categories.
 */
abstract class Pattern_Base implements Pattern_Interface {

	protected string $name        = '';
	protected string $title       = '';
	protected string $description = '';
	protected string $content     = '';
	protected int $viewportwidth  = 1400;
	protected bool $inserter      = true;

	/**
	 * @var string[]
	 */
	protected array $categories = [ Pattern_Category::CUSTOM_PATTERN_CATEGORY_SLUG ];

	/**
	 * @var string[]
	 */
	protected array $keywords = [];

	/**
	 * @var string[]
	 */
	protected array $blocktypes = [];

	/**
	 * @var string[]
	 */
	protected array $posttypes = [];

	abstract protected function get_args(): array;

	public function __construct() {
		foreach ( $this->get_args() as $key => $value ) {
			$this->{$key} = $value;
		}
	}

	public function register(): void {
		register_block_pattern(
			$this->name,
			[
				self::BLOCKTYPES    => $this->blocktypes,
				self::CATEGORIES    => $this->categories,
				self::CONTENT       => $this->content,
				self::DESCRIPTION   => $this->description,
				self::INSERTER      => $this->inserter,
				self::KEYWORDS      => $this->keywords,
				self::POSTTYPES     => $this->posttypes,
				self::TITLE         => $this->title,
				self::VIEWPORTWIDTH => $this->viewportwidth,
			]
		);
	}

}
