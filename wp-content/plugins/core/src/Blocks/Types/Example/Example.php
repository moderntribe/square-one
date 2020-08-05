<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Example;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;

class Example extends Block_Config {

	public const NAME = 'hero';

	public const IMAGE         = 'image';
	public const LEAD_IN       = 'leadin';
	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const CTA           = 'cta';
	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'layout-left';
	public const LAYOUT_CENTER = 'layout-center';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Hero', 'tribe' ),
			'description' => __( 'Hero block', 'tribe' ),
			'icon'        => 'format-image',
			'keywords'    => [ __( 'hero', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::LEAD_IN, [
				'label' => __( 'Lead in', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::IMAGE, [
				'label' => __( 'Image', 'tribe' ),
				'name'  => self::IMAGE,
				'type'  => 'image',
			] )
		)->add_field( new Field( self::CTA, [
				'label' => __( 'Call to Action', 'tribe' ),
				'name'  => self::CTA,
				'type'  => 'link',
			] )
		)->add_field( new Field( self::DESCRIPTION, [
				'label' => __( 'Description', 'tribe' ),
				'name'  => self::DESCRIPTION,
				'type'  => 'textarea',
			] )
		);
	}

	/**
	 * Register Settings for Block
	 */
	public function add_settings() {
		$this->add_setting( new Field( self::LAYOUT, [
			'label'   => __( 'Layout', 'tribe' ),
			'type'    => 'radio',
			'choices' => [
				self::LAYOUT_CENTER,
				self::LAYOUT_LEFT,
			],
		] ) );
	}

}
