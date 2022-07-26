<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Stats extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'stats';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public const CONTENT_ALIGN        = 'content';
	public const CONTENT_ALIGN_LEFT   = 'left';
	public const CONTENT_ALIGN_CENTER = 'center';

	public const DIVIDERS      = 'dividers';
	public const DIVIDERS_SHOW = 'show';
	public const DIVIDERS_HIDE = 'hide';

	public const SECTION_STATS = 's-stats';
	public const STATS         = 'stats';
	public const ROW_VALUE     = 'row_value';
	public const ROW_LABEL     = 'row_label';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Stats', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.58331 3.86954C3.79829 4.19129 3.91304 4.56956 3.91304 4.95652C3.91242 5.47523 3.70609 5.97252 3.3393 6.3393C2.97252 6.70609 2.47523 6.91242 1.95652 6.91304C1.56956 6.91304 1.19129 6.79829 0.869537 6.58331C0.547789 6.36832 0.297017 6.06276 0.148932 5.70525C0.000847735 5.34774 -0.0378979 4.95435 0.0375949 4.57482C0.113088 4.1953 0.299428 3.84668 0.573053 3.57305C0.846677 3.29943 1.1953 3.11309 1.57482 3.03759C1.95435 2.9621 2.34774 3.00085 2.70525 3.14893C3.06276 3.29702 3.36832 3.54779 3.58331 3.86954ZM1.30581 4.52173C1.21981 4.65043 1.17391 4.80174 1.17391 4.95652C1.17391 5.16408 1.25637 5.36314 1.40313 5.50991C1.5499 5.65668 1.74896 5.73913 1.95652 5.73913C2.11131 5.73913 2.26262 5.69323 2.39132 5.60724C2.52001 5.52124 2.62032 5.39902 2.67956 5.25601C2.73879 5.11301 2.75429 4.95565 2.72409 4.80384C2.6939 4.65203 2.61936 4.51258 2.50991 4.40313C2.40046 4.29368 2.26101 4.21915 2.1092 4.18895C1.95739 4.15875 1.80003 4.17425 1.65703 4.23349C1.51403 4.29272 1.3918 4.39303 1.30581 4.52173ZM1.27242 10.6961L5.968 3.65276C6.14781 3.38304 6.51224 3.31016 6.78196 3.48997C7.05168 3.66979 7.12457 4.03421 6.94475 4.30393L2.24918 11.3473C2.06936 11.617 1.70494 11.6899 1.43522 11.5101C1.16549 11.3303 1.09261 10.9659 1.27242 10.6961ZM6.26097 8.08693C5.874 8.08693 5.49573 8.20168 5.17398 8.41666C4.85223 8.63165 4.60146 8.93722 4.45338 9.29472C4.30529 9.65223 4.26655 10.0456 4.34204 10.4252C4.41753 10.8047 4.60387 11.1533 4.8775 11.4269C5.15112 11.7005 5.49974 11.8869 5.87927 11.9624C6.25879 12.0379 6.65219 11.9991 7.00969 11.851C7.3672 11.703 7.67277 11.4522 7.88775 11.1304C8.10274 10.8087 8.21749 10.4304 8.21749 10.0435C8.21687 9.52474 8.01053 9.02745 7.64375 8.66067C7.27696 8.29388 6.77968 8.08755 6.26097 8.08693ZM6.26097 10.8261C6.10618 10.8261 5.95487 10.7802 5.82617 10.6942C5.69747 10.6082 5.59716 10.4859 5.53793 10.3429C5.4787 10.1999 5.4632 10.0426 5.49339 9.89077C5.52359 9.73896 5.59813 9.59951 5.70758 9.49006C5.81703 9.38061 5.95647 9.30608 6.10829 9.27588C6.2601 9.24568 6.41745 9.26118 6.56046 9.32042C6.70346 9.37965 6.82569 9.47996 6.91168 9.60866C6.99767 9.73736 7.04357 9.88867 7.04357 10.0435C7.04357 10.251 6.96112 10.4501 6.81435 10.5968C6.66759 10.7436 6.46853 10.8261 6.26097 10.8261ZM14.7724 10.6961L19.468 3.65276C19.6478 3.38304 20.0122 3.31016 20.282 3.48997C20.5517 3.66979 20.6246 4.03421 20.4448 4.30393L15.7492 11.3473C15.5694 11.617 15.2049 11.6899 14.9352 11.5101C14.6655 11.3303 14.5926 10.9659 14.7724 10.6961ZM17.413 4.95652C17.413 4.56956 17.2983 4.19129 17.0833 3.86954C16.8683 3.54779 16.5628 3.29702 16.2052 3.14893C15.8477 3.00085 15.4544 2.9621 15.0748 3.03759C14.6953 3.11309 14.3467 3.29943 14.0731 3.57305C13.7994 3.84668 13.6131 4.1953 13.5376 4.57482C13.4621 4.95435 13.5008 5.34774 13.6489 5.70525C13.797 6.06276 14.0478 6.36832 14.3695 6.58331C14.6913 6.79829 15.0696 6.91304 15.4565 6.91304C15.9752 6.91242 16.4725 6.70609 16.8393 6.3393C17.2061 5.97252 17.4124 5.47523 17.413 4.95652ZM14.6739 4.95652C14.6739 4.80174 14.7198 4.65043 14.8058 4.52173C14.8918 4.39303 15.014 4.29272 15.157 4.23349C15.3 4.17425 15.4574 4.15875 15.6092 4.18895C15.761 4.21915 15.9005 4.29368 16.0099 4.40313C16.1194 4.51258 16.1939 4.65203 16.2241 4.80384C16.2543 4.95565 16.2388 5.11301 16.1796 5.25601C16.1203 5.39902 16.02 5.52124 15.8913 5.60724C15.7626 5.69323 15.6113 5.73913 15.4565 5.73913C15.249 5.73913 15.0499 5.65668 14.9031 5.50991C14.7564 5.36314 14.6739 5.16408 14.6739 4.95652ZM18.674 8.41666C18.9957 8.20168 19.374 8.08693 19.761 8.08693C20.2797 8.08755 20.777 8.29388 21.1437 8.66067C21.5105 9.02745 21.7169 9.52474 21.7175 10.0435C21.7175 10.4304 21.6027 10.8087 21.3878 11.1304C21.1728 11.4522 20.8672 11.703 20.5097 11.851C20.1522 11.9991 19.7588 12.0379 19.3793 11.9624C18.9997 11.8869 18.6511 11.7005 18.3775 11.4269C18.1039 11.1533 17.9175 10.8047 17.842 10.4252C17.7665 10.0456 17.8053 9.65223 17.9534 9.29472C18.1015 8.93722 18.3522 8.63165 18.674 8.41666ZM19.3262 10.6942C19.4549 10.7802 19.6062 10.8261 19.761 10.8261C19.9685 10.8261 20.1676 10.7436 20.3144 10.5968C20.4611 10.4501 20.5436 10.251 20.5436 10.0435C20.5436 9.88867 20.4977 9.73736 20.4117 9.60866C20.3257 9.47996 20.2035 9.37965 20.0605 9.32042C19.9175 9.26118 19.7601 9.24568 19.6083 9.27588C19.4565 9.30608 19.317 9.38061 19.2076 9.49006C19.0981 9.59951 19.0236 9.73896 18.9934 9.89077C18.9632 10.0426 18.9787 10.1999 19.0379 10.3429C19.0972 10.4859 19.1975 10.6082 19.3262 10.6942ZM0 15H10.5V16.5H0V15ZM24 15H13.5V16.5H24V15ZM0 18.75H6.75V20.25H0V18.75ZM20.25 18.75H13.5V20.25H20.25V18.75Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'stats', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE       => esc_html__( 'We’ve got experience', 'tribe' ),
						self::LEAD_IN     => esc_html__( 'Suspendisse potenti', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Pellentesque diam diam, aliquet non mauris eu, posuere mollis urna. Nulla eget congue ligula, a aliquam lectus. Duis non diam maximus justo dictum porttitor in in risus.',
							'tribe'
						),
						self::GROUP_CTA   => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						self::LAYOUT      => self::LAYOUT_INLINE,
						self::STATS       => [
							[
								self::ROW_VALUE => esc_html__( '2', 'tribe' ),
								self::ROW_LABEL => esc_html__( 'Happy Clients', 'tribe' ),
							],
							[
								self::ROW_VALUE => esc_html__( '5', 'tribe' ),
								self::ROW_LABEL => esc_html__( 'Years', 'tribe' ),
							],
							[
								self::ROW_VALUE => esc_html__( '8', 'tribe' ),
								self::ROW_LABEL => esc_html__( 'Countries', 'tribe' ),
							],
							[
								self::ROW_VALUE => esc_html__( '9%', 'tribe' ),
								self::ROW_LABEL => esc_html__( 'Effective', 'tribe' ),
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
			)->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
					'type'          => 'button_group',
					'name'          => self::LAYOUT,
					'choices'       => [
						self::LAYOUT_INLINE  => esc_html__( 'Inline', 'tribe' ),
						self::LAYOUT_STACKED => esc_html__( 'Stacked', 'tribe' ),
					],
					'default_value' => self::LAYOUT_STACKED,
				] ) )->add_field( new Field( self::NAME . '_' . self::CONTENT_ALIGN, [
				   'type'          => 'button_group',
				   'name'          => self::CONTENT_ALIGN,
				   'choices'       => [
					   self::CONTENT_ALIGN_CENTER => esc_html__( 'Content Center', 'tribe' ),
					   self::CONTENT_ALIGN_LEFT   => esc_html__( 'Content Left', 'tribe' ),
				   ],
				   'default_value' => self::CONTENT_ALIGN_CENTER,
			   ] ) )->add_field( new Field( self::NAME . '_' . self::DIVIDERS, [
				   'label'         => esc_html__( 'Stat Dividers', 'tribe' ),
				   'name'          => self::DIVIDERS,
				   'type'          => 'radio',
				   'choices'       => [
					   self::DIVIDERS_SHOW => esc_html__( 'Show', 'tribe' ),
					   self::DIVIDERS_HIDE => esc_html__( 'Hide', 'tribe' ),
				   ],
				   'default_value' => [
					   self::DIVIDERS_SHOW,
				   ],
				] )
			);

		//==========================================
		// Stats Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_STATS, esc_html__( 'Stats', 'tribe' ), 'accordion' ) )
			 ->add_field( $this->get_stats_section() );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_stats_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::STATS );
		$group->set_attributes( [
			'label'  => esc_html__( 'Stats List', 'tribe' ),
			'name'   => self::STATS,
			'layout' => 'block',
			'min'    => 0,
			'max'    => 10,
		] );
		$header = new Field( self::ROW_VALUE, [
			'label' => esc_html__( 'Value', 'tribe' ),
			'name'  => self::ROW_VALUE,
			'type'  => 'text',
		] );

		$group->add_field( $header );
		$content = new Field( self::ROW_LABEL, [
			'label' => esc_html__( 'Label', 'tribe' ),
			'name'  => self::ROW_LABEL,
			'type'  => 'text',
		] );
		$group->add_field( $content );

		return $group;
	}

}
