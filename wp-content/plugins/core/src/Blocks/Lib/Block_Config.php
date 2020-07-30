<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Lib;

use Tribe\Libs\ACF\Group;
use Tribe\Libs\ACF\Field;

abstract class Block_Config {
	public const NAME        = '';
	public const CONTENT_TAB = 'content';
	public const SETTING_TAB = 'settings';

	/**
	 * @var FieldCollection
	 */
	protected $fields;

	/**
	 * @var FieldCollection
	 */
	protected $settings = [];

	/**
	 * @var Block
	 */
	protected $block;

	abstract public function add_block();

	abstract protected function add_fields();

	abstract protected function add_settings();

	public function init() {
		$this->fields   = new FieldCollection();
		$this->settings = new FieldCollection();
		$this->add_block();
		$this->add_fields();
		$this->add_settings();
	}

	/**
	 * @param Block $block
	 *
	 * @return $this
	 */
	public function set_block( Block $block ): Block_Config {
		$this->block = $block;

		return $this;
	}

	public function get_block() {
		return $this->block;
	}

	/**
	 * @param Field $field
	 *
	 * @return Block_Config
	 */
	public function add_field( Field $field ): Block_Config {
		$this->fields->append( $field );

		return $this;
	}

	/**
	 * @param Field $field
	 *
	 * @return Block_Config
	 */
	public function add_setting( Field $field ): Block_Config {
		$this->settings->append( $field );

		return $this;
	}

	/**
	 * @return Group
	 */
	public function get_field_group() {
		if ( static::NAME == '' ) {
			throw new \InvalidArgumentException( "Block requires a NAME constant in " . static::class );
		}

		$group = new Group( static::NAME, [
			'block' => [ static::NAME ],
		] );

		$group->add_field( $this->get_tab(
			self::CONTENT_TAB, __( 'Content', 'tribe' )
		) );

		foreach ( $this->fields as $field ) {
			$group->add_field( $field );
		}

		if ( ! $this->settings->count() ) {
			return $group;
		}

		$group->add_field( $this->get_tab(
			self::SETTING_TAB, __( 'Settings', 'tribe' )
		) );

		foreach ( $this->settings as $setting ) {
			$group->add_field( $setting );
		}

		return $group;
	}

	/**
	 * @param string $key
	 * @param string $label
	 *
	 * @return Field
	 */
	protected function get_tab( $key, $label ): Field {
		return new Field( $key, [
			'label'     => $label,
			'name'      => $key,
			'type'      => 'tab',
			'placement' => 'top',
		] );
	}

}