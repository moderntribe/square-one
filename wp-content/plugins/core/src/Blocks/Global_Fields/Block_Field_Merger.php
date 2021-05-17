<?php declare( strict_types=1 );

namespace Tribe\Project\Blocks\Global_Fields;

use RuntimeException;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Blocks\Types\Base_Model;

/**
 * Responsible for merging block fields
 *
 * @package Tribe\Project\Blocks\Global_Fields
 */
class Block_Field_Merger {

	protected Block_Controller $controller;

	public function __construct( Block_Controller $controller ) {
		$this->controller = $controller;
	}

	/**
	 * Merge multiple global fields into a block's fields.
	 *
	 * @filter tribe/block/register/fields
	 *
	 * @param Block_Config $block
	 * @param Meta[]       $meta_instances
	 * @param mixed[]      $fields
	 *
	 * @return array
	 */
	public function merge_block_fields( Block_Config $block, array $meta_instances, array $fields ): array {
		$name = $block::NAME;

		if ( ! $this->controller->allowed( $name ) ) {
			return $fields;
		}

		foreach ( $meta_instances as $meta ) {
			if ( ! $meta instanceof Meta ) {
				throw new RuntimeException(
					sprintf(
						'%s is not an instance of \Tribe\Project\Blocks\Global_Fields\Meta',
						get_class( $meta )
					)
				);
			}

			if ( ! $this->controller->allows_specific_field_group( $name, $meta ) ) {
				return $fields;
			}

			return array_merge( $fields, $meta->get_fields() );
		}

		return $fields;
	}

	/**
	 * Merge multiple field model's data into a block's model.
	 *
	 * @filter tribe/block/model/data
	 *
	 * @param Base_Model    $model
	 * @param Block_Model[] $field_models
	 * @param array         $data
	 *
	 * @return array
	 */
	public function merge_model_data( Base_Model $model, array $field_models, array $data ): array {
		if ( ! $this->controller->allowed( $model->get_name() ) ) {
			return $data;
		}

		foreach ( $field_models as $field_model ) {
			if ( ! $field_model instanceof Block_Model ) {
				throw new RuntimeException(
					sprintf(
						'%s is not an instance of \Tribe\Project\Blocks\Global_Fields\Block_Model',
						get_class( $field_model )
					)
				);
			}

			if ( ! $this->controller->allows_specific_field_group( $model->get_name(), $field_model ) ) {
				return $data;
			}

			$field_model->set_block_id( $model->get_id() );

			return array_merge_recursive( $data, $field_model->get_data() );
		}

		return $data;
	}
}
