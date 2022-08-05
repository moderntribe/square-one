<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Patterns;

/**
 * Class Block_Pattern_Base
 *
 * Edits the block categories.
 */
abstract class Pattern_Base {

	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const CONTENT       = 'content';
	public const CATEGORIES    = 'categories';
	public const KEYWORDS      = 'keywords';
	public const VIEWPORTWIDTH = 'viewportWidth';
	public const BLOCKTYPES    = 'blockTypes';
	public const POSTTYPES     = 'postTypes';
	public const INSERTER      = 'inserter';

	protected string $name        = '';
	protected string $title       = '';
	protected string $description = '';
	protected string $content     = '';

	/**
	 * @var string[]
	 */
	protected array $categories = [];

	/**
	 * @var string[]
	 */
	protected array $keywords = [];

	/**
	 * @var string[]
	 */
	protected string $viewportWidth = '';

	/**
	 * @var string[]
	 */
	protected array $blockTypes = [];

	/**
	 * @var string[]
	 */
	protected array $postTypes = [];

	protected bool $inserter = true;

	public function __construct() {
		$this->register();
	}

	public function register(): void {
		register_block_pattern(
			$this->name,
			[
				self::TITLE         => $this->title,
				self::DESCRIPTION   => $this->description,
				self::CONTENT       => $this->content,
				self::CATEGORIES    => $this->categories,
				self::KEYWORDS      => $this->keywords,
				self::VIEWPORTWIDTH => $this->viewportWidth,
				self::BLOCKTYPES    => $this->blockTypes,
				self::POSTTYPES     => $this->postTypes,
				self::INSERTER      => $this->inserter,
			]
		);
	}

}
