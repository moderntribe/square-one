<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;

class Links extends Block_Config {
	public const NAME = 'links';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const LINKS_TITLE = 'links_title';
	public const LINKS       = 'links';
	public const LINK_ITEM   = 'item';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Links', 'tribe' ),
			'description' => __( 'A list of links', 'tribe' ),
			'icon'        => 'list-view',
			'keywords'    => [ __( 'list', 'tribe' ) ],
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
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => __( 'Lead in', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => __( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			] )
		)->add_field( new Field( self::NAME . '_' . self::CTA, [
				'label' => __( 'Call to Action', 'tribe' ),
				'name'  => self::CTA,
				'type'  => 'link',
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::LINKS_TITLE, [
				'label' => __( 'Link List Title', 'tribe' ),
				'name'  => self::LINKS_TITLE,
				'type'  => 'text',
			] )
		)->add_field(
			$this->get_links_section()
		);
	}

	/**
	 * @return Repeater
	 */
	protected function get_links_section() {
		$group = new Repeater( self::NAME . '_' . self::LINKS );
		$group->set_attributes( [
			'label'  => __( 'Links List', 'tribe' ),
			'name'   => self::LINKS,
			'layout' => 'block',
			'min'    => 0,
			'max'    => 10,
		] );
		$link = new Field( self::LINK_ITEM, [
			'label' => __( 'Link', 'tribe' ),
			'name'  => self::LINK_ITEM,
			'type'  => 'link',
		] );

		$group->add_field( $link );

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
				self::LAYOUT_STACKED,
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
