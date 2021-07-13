<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Links extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'links';

	public const SECTION_CONTENT = 's-content';
	public const LEAD_IN         = 'leadin';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';

	public const SECTION_LINKS = 's-links';
	public const LINKS_TITLE   = 'links_title';
	public const LINKS         = 'links';
	public const LINK_HEADER   = 'link_header';
	public const LINK_CONTENT  = 'link_content';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_INLINE      = 'inline';
	public const LAYOUT_STACKED     = 'stacked';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Links', 'tribe' ),
			'description' => esc_html__( 'A list of links', 'tribe' ),
			'icon'        => 'list-view',
			'keywords'    => [ esc_html__( 'list', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'Links', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::GROUP_CTA   => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						self::LINKS_TITLE => esc_html__( 'List Title', 'tribe' ),
						self::LINKS       => [
							[
								self::GROUP_CTA => [
									self::LINK => [
										'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
										'url'    => '#',
										'target' => '',
									],
								],
							],
							[
								self::GROUP_CTA => [
									self::LINK => [
										'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
										'url'    => '#',
										'target' => '',
									],
								],
							],
							[
								self::GROUP_CTA => [
									self::LINK => [
										'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
										'url'    => '#',
										'target' => '',
									],
								],
							],
						],
					],
				],
			],
		] ) );
	}

	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
					 'label'       => esc_html__( 'Lead in', 'tribe' ),
					 'name'        => self::LEAD_IN,
					 'type'        => 'text',
					 'placeholder' => esc_html__( 'Leadin (optional)', 'tribe' ),
					 'wrapper'     => [
						'class' => 'tribe-acf-hide-label',
					],
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::TITLE, [
					'label' => esc_html__( 'Title', 'tribe' ),
					'name'  => self::TITLE,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => esc_html__( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => Classic_Editor_Formats::MINIMAL,
					'tabs'         => 'visual',
					'media_upload' => 0,
				] )
			)->add_field(
				$this->get_cta_field( self::NAME )
			);

		$this->add_section( new Field_Section( self::SECTION_LINKS, esc_html__( 'Links', 'tribe' ), 'accordion' ) )
			 ->add_field(
				new Field( self::NAME . '_' . self::LINKS_TITLE, [
					'label'       => esc_html__( 'Link List Title', 'tribe' ),
					'name'        => self::LINKS_TITLE,
					'type'        => 'text',
					'placeholder' => esc_html( 'Link List Title (optional)', 'tribe' ),
					'wrapper'     => [
						'class' => 'tribe-acf-hide-label',
					],
				] )
			)->add_field(
				$this->get_links_section()
			);

		//==========================================
		// Appearance Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'type'            => 'image_select',
				 'name'            => self::LAYOUT,
				 'choices'         => [
					 self::LAYOUT_INLINE  => esc_html__( 'Inline', 'tribe' ),
					 self::LAYOUT_STACKED => esc_html__( 'Stacked', 'tribe' ),
				 ],
				 'default_value'   => self::LAYOUT_STACKED,
				 'multiple'        => 0,
				 'image_path'      => sprintf(
					 '%sassets/img/admin/blocks/%s/',
					 trailingslashit( get_template_directory_uri() ),
					 self::NAME
				 ),
				 'image_extension' => 'svg',
			 ] ) );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_links_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::LINKS );
		$group->set_attributes( [
			'label'  => esc_html__( 'Links List', 'tribe' ),
			'name'   => self::LINKS,
			'layout' => 'block',
			'min'    => 0,
			'max'    => 10,
		] );
		$link = $this->get_cta_field( self::NAME );

		$group->add_field( $link );

		$group->add_field( new Field( self::NAME . '_' . self::LINK_HEADER, [
			'label'        => esc_html__( 'Link Header', 'tribe' ),
			'name'         => self::LINK_HEADER,
			'type'         => 'text',
		] )
		)->add_field( new Field( self::NAME . '_' . self::LINK_CONTENT, [
			'label'        => esc_html__( 'Link Content', 'tribe' ),
			'name'         => self::LINK_CONTENT,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
		] ) );

		return $group;
	}

}
