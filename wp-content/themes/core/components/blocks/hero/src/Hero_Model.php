<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\hero\src;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Blocks\Contracts\Block_Model;

class Hero_Model extends Block_Model {

	public Cta $cta;
	public Image $media;
	public string $classes     = 'c-block--full-bleed';
	public string $description = '';
	public string $layout      = Hero::LAYOUT_LEFT;
	public string $leadin      = '';
	public string $title       = '';

	/**
	 * @var string[]
	 */
	public array $container_classes = [];

	/**
	 * @var string[]
	 */
	public array $content_classes = [];

	/**
	 * @var string[]
	 */
	public array $media_classes = [];

}
