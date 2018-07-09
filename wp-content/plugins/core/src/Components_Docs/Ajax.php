<?php

namespace Tribe\Project\Components_Docs;

use Tribe\Project\Facade\Items\Request;
use Tribe\Project\Templates\Components\Component;

class Ajax {

	const ACTION_UPDATE_PREVIEW = 'components_docs_update_preview';
	const NONCE                 = 'components_docs_nonce';

	/**
	 * @var Registry $registry
	 */
	protected $registry;

	protected $actions = [
		self::ACTION_UPDATE_PREVIEW => true,
	];


	public function __construct( Registry $registry ) {
		$this->registry = $registry;
	}


	public function add_config_items( $data ) {
		$data['preview_action']        = self::ACTION_UPDATE_PREVIEW;
		$data['components_docs_nonce'] = wp_create_nonce( self::NONCE );
		$data['ajax_url']              = admin_url( 'admin-ajax.php' );

		return $data;
	}

	public function add_ajax_actions() {
		foreach ( $this->actions as $name => $public ) {
			add_action( 'wp_ajax_' . $name, [ $this, 'ajax_' . $name ] );

			if ( $public ) {
				add_action( 'wp_ajax_nopriv_' . $name, [ $this, 'ajax_' . $name ] );
			}
		}
	}

	public function ajax_components_docs_update_preview() {
		check_ajax_referer( self::NONCE, 'security' );

		$options = Request::all();
		$type    = Request::input( 'component_class' );
		$item    = $this->registry->get_item( $type );

		if ( empty( $type ) || empty( $item ) ) {
			wp_send_json_error( 'You must provide a valid component class type.' );
		}

		$options = $this->parse_values( $options );

		wp_send_json_success( $item->get_rendered_template( $options ) );
	}

	protected function parse_values( array $options ) {
		foreach ( $options as $key => $value ) {
			if ( is_array( $value ) ) {
				$options[ $key ] = $this->parse_values( $value );
				continue;
			}

			if ( ! empty( $decoded = json_decode( stripslashes( $value ), true ) ) ) {
				$options[ $key ] = $decoded;
				continue;
			}

			if ( strpos( $key, 'attrs' ) !== false ) {
				$options[ $key ] = $this->convert_attributes_to_array( stripslashes( $value ) );
				continue;
			}

			if ( strpos( $key, 'class' ) !== false ) {
				$options[ $key ] = explode( ' ', $value );
			}
		}

		return array_filter( $options );
	}

	protected function convert_attributes_to_array( string $attributes ) {
		$element = new \SimpleXMLElement( "<element $attributes />" );
		$element = (array) $element;

		return $element['@attributes'];
	}

}