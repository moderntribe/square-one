<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Blocks\Types\Stats\Support\Statistic;

class Stats extends Block_Config {
	public const NAME = 'stats';

	public const TITLE        = 'title';
	public const DESCRIPTION  = 'description';
	public const STATS        = 'stats';
	public const ROW_VALUE   = 'row_value';
	public const ROW_LABEL   = 'row_label';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public const CONTENT_ALIGN        = 'content';
	public const CONTENT_ALIGN_LEFT   = 'left';
	public const CONTENT_ALIGN_CENTER = 'center';

	public const DISPLAY_DIVIDERS = 'display_dividers';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Stats', 'tribe' ),
			'description' => __( 'Useful for showing various stats/numbers with sub-text', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path fill="#000" d="M4 12h11v2H4zM3 15h13v2H3zM3.872 10.108a2.703 2.703 0 01-1.071-.225 2.809 2.809 0 01-.513-.324 2.766 2.766 0 01-.45-.495l.9-.738c.156.21.333.369.531.477.204.102.393.153.567.153.27 0 .48-.054.63-.162a.566.566 0 00.234-.486.75.75 0 00-.054-.297.464.464 0 00-.207-.225 1.139 1.139 0 00-.405-.135 3.807 3.807 0 00-.648-.045l.198-1.008c.276 0 .504-.018.684-.054.186-.042.33-.096.432-.162a.611.611 0 00.225-.243.777.777 0 00.063-.315.455.455 0 00-.162-.369c-.108-.09-.258-.135-.45-.135a.953.953 0 00-.378.081 4.53 4.53 0 00-.45.243l-.684-.918c.306-.21.603-.351.891-.423.288-.078.585-.117.891-.117.264 0 .507.033.729.099.222.066.414.162.576.288a1.235 1.235 0 01.513 1.017c0 .36-.087.663-.261.909-.168.246-.477.447-.927.603v.036c.306.126.525.294.657.504.138.21.207.438.207.684a1.603 1.603 0 01-.657 1.332c-.204.15-.444.264-.72.342a3.52 3.52 0 01-.891.108zm4.82 0c-.234 0-.456-.039-.666-.117a1.521 1.521 0 01-.54-.378 1.965 1.965 0 01-.36-.657A3.1 3.1 0 017 8.02c0-.672.078-1.248.234-1.728.156-.486.357-.885.603-1.197a2.32 2.32 0 01.828-.684c.306-.15.609-.225.909-.225.234 0 .453.042.657.126.204.084.38.213.53.387.15.168.268.384.352.648.09.264.135.579.135.945 0 .672-.078 1.248-.234 1.728-.15.48-.348.876-.594 1.188a2.37 2.37 0 01-.82.684 2.122 2.122 0 01-.908.216zm.09-1.098c.132 0 .267-.069.405-.207.138-.144.26-.342.369-.594a3.99 3.99 0 00.27-.909 5.88 5.88 0 00.108-1.17c0-.33-.042-.552-.126-.666a.38.38 0 00-.324-.18c-.138 0-.276.072-.414.216a2.068 2.068 0 00-.37.594 4.682 4.682 0 00-.27.918 6.4 6.4 0 00-.098 1.17c0 .168.009.306.027.414.024.102.054.186.09.252.042.06.09.102.144.126.06.024.123.036.189.036zm4.316-1.332a1.227 1.227 0 01-.918-.387 1.514 1.514 0 01-.28-.459 1.77 1.77 0 01-.098-.612c0-.33.042-.633.126-.909.09-.282.21-.525.36-.729.156-.204.342-.363.558-.477.216-.114.456-.171.72-.171.174 0 .339.036.495.108.162.066.3.162.414.288.12.126.213.279.279.459.072.18.108.381.108.603 0 .33-.045.636-.135.918a2.252 2.252 0 01-.36.72 1.58 1.58 0 01-.558.477 1.5 1.5 0 01-.711.171zm.09-.846c.18 0 .336-.126.468-.378s.198-.6.198-1.044c0-.198-.033-.351-.1-.459a.296.296 0 00-.278-.171c-.18 0-.336.126-.468.378s-.198.6-.198 1.044c0 .198.03.354.09.468a.318.318 0 00.288.162zm-.54 3.276l4.23-6.156h.81l-4.23 6.156h-.81zm4.23 0a1.227 1.227 0 01-.918-.387 1.514 1.514 0 01-.28-.459 1.77 1.77 0 01-.098-.612c0-.33.042-.633.126-.909.09-.282.21-.525.36-.729.156-.204.342-.363.558-.477.216-.114.456-.171.72-.171.174 0 .339.036.495.108.162.066.3.162.414.288.12.126.213.279.279.459.072.18.108.381.108.603 0 .33-.045.636-.135.918a2.252 2.252 0 01-.36.72 1.58 1.58 0 01-.558.477 1.5 1.5 0 01-.711.171zm.09-.846c.18 0 .336-.126.468-.378s.198-.6.198-1.044c0-.198-.033-.351-.1-.459a.296.296 0 00-.278-.171c-.18 0-.336.126-.468.378s-.198.6-.198 1.044c0 .198.03.354.09.468a.318.318 0 00.288.162z"/></svg>',
			'keywords'    => [ __( 'stats', 'tribe' ) ],
			'category'    => 'layout',
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
			$this->get_stats_section()
		);
	}

	/**
	 * @return Repeater
	 */
	protected function get_stats_section() {
		$group = new Repeater( self::NAME . '_' . self::STATS );
		$group->set_attributes( [
			'label'  => __( 'Stats List', 'tribe' ),
			'name'   => self::STATS,
			'layout' => 'block',
			'min'    => 0,
			'max'    => 10,
		] );
		$header = new Field( self::ROW_VALUE, [
			'label' => __( 'Value', 'tribe' ),
			'name'  => self::ROW_VALUE,
			'type'  => 'text',
		] );

		$group->add_field( $header );
		$content = new Field( self::ROW_LABEL, [
			'label' => __( 'Label', 'tribe' ),
			'name'  => self::ROW_LABEL,
			'type'  => 'text',
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
				self::LAYOUT_STACKED,
			],
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] ) )->add_setting( new Field( self::NAME . '_' . self::CONTENT_ALIGN, [
			'type'            => 'image_select',
			'name'            => self::CONTENT_ALIGN,
			'choices'         => [
				self::CONTENT_ALIGN_CENTER => __( 'Content Center', 'tribe' ),
				self::CONTENT_ALIGN_LEFT   => __( 'Content Left', 'tribe' ),
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
		] ) )->add_setting( new Field( self::NAME . '_' . self::DISPLAY_DIVIDERS, [
			'label' => __( 'Display Dividers', 'tribe' ),
			'name'  => self::DISPLAY_DIVIDERS,
			'type'  => 'true_false',
		] ) );
	}
}
