<?php

namespace AttachmentHelper;

class UI {
	/** @var UI */
	private static $instance = NULL;

	public function register_scripts( $args ) {
		static $registered_scripts = FALSE;
		if( !$registered_scripts ) {
			wp_register_script( 'attachment-helper', $this->url( 'assets/js/attachment-helper.js' ), array(
				'jquery',
				'media-upload',
				'media-views'
			), FALSE, TRUE );
			$registered_scripts = TRUE;
		}
	}


	public function enqueue_scripts( $args ) {
		$this->register_scripts( $args );
		
		$AttachmentHelper = array(
			'plupload_init' => $this->get_plupload_init_settings($args['size']),
			'size'          => $args['size'],
			'type'          => $args['type']
		);


		wp_enqueue_media();
		wp_enqueue_script( 'attachment-helper' );

		global $wp_scripts;

		$data = $wp_scripts->get_data( 'attachment-helper', 'data' );
		$wp_scripts->add_data( 'attachment-helper', 'data', $data . $this->get_script_data($args) );
		//wp_localize_script( 'attachment-helper', 'AttachmentHelper', $AttachmentHelper );
		
		wp_enqueue_style('attachment-helper', $this->url( 'assets/css/attachement-helper.css' ) );

	}

	private function get_script_data( $args ) {
		$script = "var AttachmentHelper = window.AttachmentHelper || {};\n";
		$script .= "AttachmentHelper.settings = AttachmentHelper.settings || {};\n";
		$instance_data = array(
			'plupload_init' => $this->get_plupload_init_settings($args['size']),
			'size'          => $args['size'],
			'type'          => $args['type']
		);
		$script .= sprintf("AttachmentHelper.settings['%s'] = %s;\n", $args['settings'], json_encode($instance_data));
		return $script;
	}

	private function get_plupload_init_settings( $size = 'thumbnail' ) {
		return array(
			'runtimes'            => 'html5,silverlight,flash,html4',
			'browse_button'       => 'plupload-browse-button',
			'container'           => 'plupload-upload-ui',
			'drop_element'        => 'drag-drop-area',
			'file_data_name'      => 'async-upload',
			'multiple_queues'     => false,
			'multi_selection'     => false,
			'max_file_size'       => wp_max_upload_size( ) . 'b',
			'url'                 => admin_url( 'admin-ajax.php' ),
			'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
			'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
			'multipart'           => true,
			'urlstream_upload'    => true,

			// Additional parameters:
			'multipart_params'   => array(
				'_ajax_nonce'   => wp_create_nonce( 'photo-upload' ),
				'action'        => 'attachment_helper_upload_image',
				'postID'        => get_the_ID(),
				'size'          => $size,
			),
		);
	}

	private function url( $path = '' ) {
		return plugins_url( $path, __DIR__ );
	}

	public static function instance() {
		if( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
