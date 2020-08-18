<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Repeater;

class Accordion extends Block_Config {
	public const NAME = 'accordion';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const ACCORDION   = 'accordion';
	public const ROW_HEADER  = 'row_header';
	public const ROW_CONTENT = 'row_content';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Accordion', 'tribe' ),
			'description' => __( 'The Accordion block', 'tribe' ),
			'icon'        => '<svg width="24" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#000" d="M0 0h23.7v2.7H0zM0 12h23.7v2.7H0zM0 15.3h23.7V18H0z"/><path fill="#fff" stroke="#000" d="M1.7 3.8h20v6.8h-20z"/></svg>',
			'keywords'    => [ __( 'accordion', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
		] ) );
	}

	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label' => __( 'Description', 'tribe' ),
				'name'  => self::DESCRIPTION,
				'type'  => 'textarea',
			] )
		)->add_field(
			$this->get_accordion_section()
		);
	}

	/**
	 * @return Repeater
	 */
	protected function get_accordion_section() {
		$group = new Repeater( self::NAME . '_' . self::ACCORDION );
		$group->set_attributes( [
			'label'  => __( 'Accordion Section', 'tribe' ),
			'name'   => self::ACCORDION,
			'layout' => 'block',
			'min'    => 0,
			'max'    => 10,
		] );
		$header = new Field( self::ROW_HEADER, [
			'label' => __( 'Header', 'tribe' ),
			'name'  => self::ROW_HEADER,
			'type'  => 'text',
		] );

		$group->add_field( $header );
		$content = new Field( self::ROW_CONTENT, [
			'label' => __( 'Content', 'tribe' ),
			'name'  => self::ROW_CONTENT,
			'type'  => 'textarea',
		] );
		$group->add_field( $content );

		return $group;
	}

	public function add_settings() {
		$this->add_setting( new Field( self::NAME . '_' . self::LAYOUT, [
			'type'            => 'image_select',
			'name'            => self::LAYOUT,
			'choices'         => [
				self::LAYOUT_INLINE  => __( 'Inline', 'tribe' ),
				self::LAYOUT_STACKED => __( 'Stacked', 'tribe' ),
			],
			'default_value'   => [
				self::LAYOUT_INLINE,
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
