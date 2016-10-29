<?php


namespace AttachmentHelper;


class Ajax_Handler {

	public function add_hooks() {
		add_action( 'wp_ajax_attachment_helper_upload_image', array(
			$this,
			'ajax_upload_image_file'
		) );
	}

	/**
	 * File upload handler.
	 */
	public function ajax_upload_image_file() {

		// Check referer, die if no ajax:
		check_ajax_referer( 'photo-upload' );

		/// Upload file using Wordpress functions:
		$file = $_FILES[ 'async-upload' ];
		$status = wp_handle_upload( $file, array(
			'test_form'   => true,
			'action'   => 'attachment_helper_upload_image'
		) );

		// Fetch post ID:
		$post_id = $_POST[ 'postID' ];

		// Insert uploaded file as attachment:
		$attach_id = wp_insert_attachment( array(
			'post_mime_type'   => $status[ 'type' ],
			'post_title'   => preg_replace( '/\.[^.]+$/', '', basename( $file[ 'name' ] ) ),
			'post_content'   => '',
			'post_status'   => 'inherit'
		), $status[ 'file' ], $post_id );

		// Include the image handler library:
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate meta data and update attachment:
		$attach_data = wp_generate_attachment_metadata( $attach_id, $status[ 'file' ] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		
		if( strpos( $status['type'], 'image' ) === false ){
			$croppedImage[0] = wp_mime_type_icon( $status['type'] );
		} else {
			// Get image sizes and correct thumb:
			$croppedImage = wp_get_attachment_image_src( $attach_id, $_POST['size'] );
		}

		$imageDetails = getimagesize( $croppedImage[ 0 ] );

		// Create response array:
		$uploadResponse = array(
			'id'      => $attach_id,
			'image'   => $croppedImage[ 0 ],
			'width'   => $imageDetails[ 0 ],
			'height'   => $imageDetails[ 1 ],
			'postID'   => $post_id
		);

		// Return response and exit:
		die( json_encode( $uploadResponse ) );

	}


	/********** SINGLETON FUNCTIONS **********/

	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		$instance = self::get_instance( );
		$instance->add_hooks();
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @static
	 * @return self
	 */
	public static function get_instance() {
		if( !is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


}
