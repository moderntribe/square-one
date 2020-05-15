<?php

namespace Tribe\Project\Templates\Components\Panels\Media_Text;

use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Models\Image as Image_Model;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Image
 *
 * @property Image_Model|null $image
 * @property string[]         $classes
 * @property string[]         $attrs
 */
class Image extends \Tribe\Project\Templates\Components\Context {
	public const IMAGE   = 'image';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected $path = __DIR__ . '/image.twig';

	protected $properties = [
		self::IMAGE   => [
			self::DEFAULT => null,
		],
		self::CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'media-text__media', 'media-text__image' ],
		],
		self::ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		$data = parent::get_data();

		if ( $this->image ) {
			$data[ self::IMAGE ] = $this->factory->get( Image_Component::class, [
				Image_Component::ATTACHMENT   => $this->image,
				Image_Component::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
				Image_Component::USE_LAZYLOAD => false,
			] )->render();
		} else {
			$data[ self::IMAGE ] = '';
		}

		return $data;
	}


}
