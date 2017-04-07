<?php

namespace JSONLD;

abstract class Data_Builder {
	protected $object = null;
	protected $data   = [ ];

	public function __construct( $object ) {
		$this->object = $object;
	}

	/**
	 * @return array
	 */
	public function get_data() {
		if ( empty( $this->data ) ) {
			$this->data = $this->build_data();
		}
		return $this->data;
	}

	abstract protected function build_data();

	protected function get_option( $option ) {
		return Settings_Page::instance()->get_option( $option );
	}

	protected function get_image( $image_id, $object = true, $size = 'full' ) {
		if ( ! $image_id ) {
			return $object ? [] : '';
		}

		$image_data = wp_get_attachment_image_src( $image_id, $size );
		if( $object ){
			$image = [
				'@type'  => 'ImageObject',
				'url'    => $image_data[0],
				'width'  => $image_data[1],
				'height' => $image_data[2],
			];
		} else {
			$image = $image_data[0];
		}
		return $image;
	}
	
	protected function get_organization_logo() {
		$image_id = $this->get_option( Settings_Page::ORG_LOGO );
		return $this->get_image( $image_id, true, 'full' );
	}
}