<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Interstitial;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;

class Interstitial extends Block_Config {
	public const NAME = 'interstitial';

	public const IMAGE       = 'image';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'left';
	public const LAYOUT_CENTER = 'center';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Interstitial', 'tribe' ),
			'description' => __( 'Interstitial block', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path d="M13.6 12.7H6.4v3.6h7.2v-3.6zM3.7 4.6h12.6v1.8H3.7zM6.4 7.3h7.2v1.8H6.4z" fill="#000"/><path d="M10.517 14.305H8.732v.17h1.785v-.17zM10.517 14.393v-.567l.298.284.299.283-.299.283-.298.283v-.566z" fill="#fff"/></svg>',
			'keywords'    => [ __( 'interstitial', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
		] ) );
	}

	protected function add_fields() {
		$this->add_field(
			new Field( self::NAME . '_' . self::IMAGE, [
				'label'        => __( 'Background Image', 'tribe' ),
				'name'         => self::IMAGE,
				'type'         => 'image',
				'return_format' => 'id',
				'instructions' => __( 'Recommended image size: 1440px wide', 'tribe' ),
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label' => __( 'Description', 'tribe' ),
				'name'  => self::DESCRIPTION,
				'type'  => 'textarea',
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::CTA, [
				'label' => __( 'Call to Action', 'tribe' ),
				'name'  => self::CTA,
				'type'  => 'link',
			] )
		);
	}

	protected function add_settings() {
		$this->add_setting( new Field( self::NAME . '_' . self::LAYOUT, [
			'type'            => 'image_select',
			'name'            => self::LAYOUT,
			'choices'         => [
				self::LAYOUT_LEFT   => __( 'Text Align Left', 'tribe' ),
				self::LAYOUT_CENTER => __( 'Text Align Center', 'tribe' ),
			],
			'default_value'   => [
				self::LAYOUT_LEFT,
			],
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] ) );
	}
}
