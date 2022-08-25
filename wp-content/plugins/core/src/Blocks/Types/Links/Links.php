<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Links extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'links';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_LINKS = 's-links';
	public const LINKS_TITLE   = 'links_title';
	public const LINKS         = 'links';
	public const LINK_CONTENT  = 'link_content';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_INLINE      = 'inline';
	public const LAYOUT_STACKED     = 'stacked';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Links', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.29965 4.43832L6.84652 2.89144C7.27002 2.47725 7.83977 2.24677 8.43214 2.25003C9.02451 2.2533 9.59169 2.49003 10.0106 2.90887C10.4295 3.32771 10.6663 3.89485 10.6697 4.48721C10.6731 5.07958 10.4427 5.64937 10.0286 6.07294L8.82961 7.27191C8.88249 6.89615 8.85944 6.5136 8.76183 6.14691L9.43205 5.47698C9.56265 5.34638 9.66625 5.19133 9.73693 5.02069C9.80761 4.85006 9.84399 4.66717 9.84399 4.48248C9.84399 4.29778 9.80761 4.11489 9.73693 3.94426C9.66625 3.77362 9.56265 3.61857 9.43205 3.48798C9.30145 3.35738 9.14641 3.25378 8.97577 3.1831C8.80513 3.11242 8.62225 3.07604 8.43755 3.07604C8.06454 3.07604 7.70681 3.22422 7.44305 3.48798L5.89618 5.03485C5.63292 5.29888 5.48509 5.65651 5.48509 6.02935C5.48509 6.40219 5.63292 6.75982 5.89618 7.02385C6.11096 7.23715 6.38938 7.37468 6.6893 7.41563C6.68349 7.4224 6.67799 7.42932 6.6725 7.43624C6.66338 7.44773 6.65427 7.4592 6.64374 7.46991L6.01346 8.09991C5.67384 7.95669 5.37467 7.73214 5.1423 7.44604C4.90994 7.15993 4.75151 6.82107 4.68099 6.4593C4.61046 6.09753 4.63 5.72397 4.73789 5.37154C4.84578 5.0191 5.0387 4.69862 5.29965 4.43832ZM22.2187 3.45122H12.4687V4.95122H22.2187V3.45122ZM22.2187 7.20122H12.4687V8.70122H22.2187V7.20122ZM12.4687 13.9512H22.2187V15.4512H12.4687V13.9512ZM22.2187 17.7012H12.4687V19.2012H22.2187V17.7012ZM6.92412 4.80254L6.29384 5.43254C6.28479 5.44174 6.27736 5.45158 6.2699 5.46146C6.26345 5.47002 6.25697 5.4786 6.2494 5.48682C6.54889 5.5281 6.82685 5.6656 7.0414 5.8786C7.30466 6.14263 7.45248 6.50026 7.45248 6.8731C7.45248 7.24594 7.30466 7.60357 7.0414 7.8676L5.49453 9.41447C5.23077 9.67823 4.87304 9.82641 4.50003 9.82641C4.12702 9.82641 3.76929 9.67823 3.50553 9.41447C3.24177 9.15072 3.09359 8.79298 3.09359 8.41997C3.09359 8.04697 3.24177 7.68923 3.50553 7.42548L4.17631 6.75469C4.07907 6.38684 4.05707 6.00315 4.11162 5.6266L2.909 6.82894C2.48703 7.25091 2.24997 7.82322 2.24997 8.41997C2.24997 9.01673 2.48703 9.58904 2.909 10.011C3.33096 10.433 3.90327 10.67 4.50003 10.67C5.09678 10.67 5.66909 10.433 6.09106 10.011L7.63793 8.46413C7.89888 8.20383 8.0918 7.88335 8.19969 7.53091C8.30758 7.17848 8.32712 6.80492 8.25659 6.44315C8.18607 6.08138 8.02764 5.74252 7.79527 5.45641C7.56291 5.17031 7.26373 4.94576 6.92412 4.80254ZM6.92412 15.3025L6.29384 15.9325C6.2848 15.9417 6.27737 15.9516 6.26991 15.9614L6.2699 15.9615C6.26345 15.97 6.25697 15.9786 6.2494 15.9868C6.54889 16.0281 6.82685 16.1656 7.0414 16.3786C7.30466 16.6426 7.45248 17.0003 7.45248 17.3731C7.45248 17.7459 7.30466 18.1036 7.0414 18.3676L5.49453 19.9145C5.23077 20.1782 4.87304 20.3264 4.50003 20.3264C4.12702 20.3264 3.76929 20.1782 3.50553 19.9145C3.24177 19.6507 3.09359 19.293 3.09359 18.92C3.09359 18.547 3.24177 18.1892 3.50553 17.9255L4.17631 17.2547C4.07907 16.8868 4.05707 16.5032 4.11162 16.1266L2.909 17.3289C2.48703 17.7509 2.24997 18.3232 2.24997 18.92C2.24997 19.5167 2.48703 20.089 2.909 20.511C3.33096 20.933 3.90327 21.17 4.50003 21.17C5.09678 21.17 5.66909 20.933 6.09106 20.511L7.63793 18.9641C7.89888 18.7038 8.0918 18.3833 8.19969 18.0309C8.30758 17.6785 8.32712 17.3049 8.25659 16.9432C8.18607 16.5814 8.02764 16.2425 7.79527 15.9564C7.56291 15.6703 7.26374 15.4458 6.92412 15.3025ZM5.29965 14.9383L6.84652 13.3914C7.27002 12.9772 7.83977 12.7468 8.43214 12.75C9.02451 12.7533 9.59169 12.99 10.0106 13.4089C10.4295 13.8277 10.6663 14.3948 10.6697 14.9872C10.6731 15.5796 10.4427 16.1494 10.0286 16.5729L8.82961 17.7719C8.88249 17.3962 8.85944 17.0136 8.76183 16.6469L9.43205 15.977C9.56265 15.8464 9.66625 15.6913 9.73693 15.5207C9.80761 15.3501 9.84399 15.1672 9.84399 14.9825C9.84399 14.7978 9.80761 14.6149 9.73693 14.4443C9.66625 14.2736 9.56265 14.1186 9.43205 13.988C9.30145 13.8574 9.14641 13.7538 8.97577 13.6831C8.80513 13.6124 8.62225 13.576 8.43755 13.576C8.06454 13.576 7.70681 13.7242 7.44305 13.988L5.89618 15.5348C5.63292 15.7989 5.48509 16.1565 5.48509 16.5294C5.48509 16.9022 5.63292 17.2598 5.89618 17.5238C6.11096 17.7371 6.38938 17.8747 6.6893 17.9156C6.68349 17.9224 6.67799 17.9293 6.67249 17.9362C6.66338 17.9477 6.65427 17.9592 6.64374 17.9699L6.01346 18.5999C5.67384 18.4567 5.37467 18.2321 5.1423 17.946C4.90994 17.6599 4.75151 17.3211 4.68099 16.9593C4.61046 16.5975 4.63 16.224 4.73789 15.8715C4.84578 15.5191 5.0387 15.1986 5.29965 14.9383Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'list', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
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

	public function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => esc_html__( 'Overline', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
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
			 ->add_field( new Field( self::NAME . '_' . self::LINKS_TITLE, [
				'label' => esc_html__( 'Link List Title', 'tribe' ),
				'name'  => self::LINKS_TITLE,
				'type'  => 'text',
			] )
			)->add_field(
				$this->get_links_section()
			);

		//==========================================
		// Appearance Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'type'          => 'button_group',
				 'name'          => self::LAYOUT,
				 'choices'       => [
					 self::LAYOUT_INLINE  => esc_html__( 'Inline', 'tribe' ),
					 self::LAYOUT_STACKED => esc_html__( 'Stacked', 'tribe' ),
				 ],
				 'default_value' => self::LAYOUT_STACKED,
			 ] ) );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_links_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::LINKS );
		$group->set_attributes( [
			'label'        => esc_html__( 'Links List', 'tribe' ),
			'name'         => self::LINKS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 10,
			'button_label' => esc_html__( 'Add Link', 'tribe' ),
		] );
		$link = $this->get_cta_field( self::NAME );

		$group->add_field( $link );

		$group->add_field( new Field( self::NAME . '_' . self::LINK_CONTENT, [
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
