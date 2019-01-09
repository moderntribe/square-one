<?php


namespace Tribe\Project\Blog_Copier;


class Network_Admin_Screen {
	const NAME = 'copy-blog';

	const SECTION_FORM   = 'copy-blog-form';
	const SECTION_STATUS = 'copy-blog-status';

	const OPTION_LAST_SRC = 'copy-blog-last-src';

	/**
	 * @return void
	 * @action network_admin_menu
	 */
	public function register_screen() {

		add_submenu_page( 'sites.php',
			__( 'Blog Copier', 'tribe' ),
			__( 'Blog Copier', 'tribe' ),
			'manage_sites',
			self::NAME,
			[ $this, 'render_screen', ]
		);

		add_settings_section(
			self::SECTION_FORM,
			__( 'Create a Copy', 'tribe' ),
			function () {
				do_action( 'tribe/project/copy-blog/form' );
			},
			self::NAME
		);

		add_settings_field(
			'copy-blog-source',
			__( 'Choose Source Blog to Copy', 'tribe' ),
			[ $this, 'source_blog_field' ],
			self::NAME,
			self::SECTION_FORM
		);

		add_settings_field(
			'copy-blog-address',
			__( 'New Blog Address', 'tribe' ),
			[ $this, 'new_blog_address_field' ],
			self::NAME,
			self::SECTION_FORM
		);

		add_settings_field(
			'copy-blog-title',
			__( 'New Blog Title', 'tribe' ),
			[ $this, 'new_blog_title_field' ],
			self::NAME,
			self::SECTION_FORM
		);

		add_settings_field(
			'copy-blog-files',
			__( 'Copy Files?', 'tribe' ),
			[ $this, 'copy_files_field' ],
			self::NAME,
			self::SECTION_FORM
		);

		add_settings_field(
			'copy-blog-notification',
			__( 'Notification Recipients', 'tribe' ),
			[ $this, 'notification_recipients_field' ],
			self::NAME,
			self::SECTION_FORM
		);
	}

	public function render_screen() {
		$title  = __( 'Blog Copier', 'tribe' );
		$action = network_admin_url( 'edit.php?action=' . self::NAME );
		ob_start();

		$this->settings_errors();

		do_action( 'tribe/project/copy-blog/before-form' );

		printf( "<form action='%s' method='post'>", esc_url( $action ) );

		// network admin won't do this automatically
		printf( '<input type="hidden" name="action" value="%s" />', esc_attr( self::NAME ) );
		wp_nonce_field( self::NAME );

		do_settings_sections( self::NAME );
		submit_button( __( 'Begin Copy', 'tribe' ) );
		echo "</form>";

		do_action( 'tribe/project/copy-blog/after-form' );

		$content = ob_get_clean();

		printf( '<div class="wrap"><h2>%s</h2>%s</div>', $title, $content );
	}

	private function settings_errors() {
		global $wp_settings_errors;
		$transient = get_transient( 'settings_errors' );
		if ( $transient ) {
			$wp_settings_errors = array_merge( (array) $wp_settings_errors, get_transient( 'settings_errors' ) );
			delete_transient( 'settings_errors' );
		}
		settings_errors();
	}

	public function source_blog_field() {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$query = "SELECT b.blog_id, CONCAT(b.domain, b.path) as domain_path
              FROM {$wpdb->blogs} b 
              WHERE b.site_id = %d
                AND b.blog_id > 1
                AND b.deleted = 0
              ORDER BY domain_path ASC LIMIT 10000";

		$blogs = $wpdb->get_results( $wpdb->prepare( $query, get_current_network_id() ) );

		$selected = absint( isset( $_GET[ 'src' ] ) ? $_GET[ 'src' ] : get_site_option( self::OPTION_LAST_SRC, 0 ) );

		$options = array_map( function ( $blog ) use ( $selected ) {
			return sprintf( '<option value="%d" %s>%s</option>', $blog->blog_id, selected( $blog->blog_id, $selected, false ), esc_html( untrailingslashit( $blog->domain_path ) ) );
		}, $blogs );

		printf( '<select name="%s[src]">%s</select>', esc_attr( self::NAME ), implode( $options ) );
	}

	public function new_blog_address_field() {
		$current_site = get_current_site();
		if ( is_subdomain_install() ) {
			printf( '<input name="%s[address]" type="text" class="regular-text" />.%s', esc_attr( self::NAME ), $current_site->domain );
		} else {
			printf( '%s%s<input name="%s[address]" type="text" class="regular-text" />', $current_site->domain, $current_site->path, esc_attr( self::NAME ) );
		}
	}

	public function new_blog_title_field() {
		printf( '<input name="%s[title]" type="text" class="regular-text" />', esc_attr( self::NAME ) );
	}

	public function copy_files_field() {
		printf( '<input type="checkbox" checked="checked" name="%s[files]" /> ', esc_attr( self::NAME ) );
		printf( '<span class="description">%s</span>', __( 'Copies will be made of all files. Highly recommended.', 'tribe' ) );
	}

	public function notification_recipients_field() {
		printf( '<input name="%s[notify]" type="text" class="regular-text" />', esc_attr( self::NAME ) );
		printf( '<p class="description">%s</p>', __( 'Email addresses that will be notified when the copy is complete. Separate with commas.', 'tribe' ) );
	}

	/**
	 * @return void
	 * @action network_admin_edit_ . self::NAME
	 */
	public function handle_submission() {

		$errors = $this->validate_submission( $_POST );

		if ( count( $errors->get_error_codes() ) > 0 ) {
			foreach ( $errors->get_error_codes() as $code ) {
				foreach ( $errors->get_error_messages( $code ) as $message ) {
					add_settings_error( self::NAME, $code, $message, 'error' );
				}
			}
		} else {
			$args = $_POST[ self::NAME ];


			$email   = get_blog_option( $args[ 'src' ], 'admin_email' );
			$user_id = email_exists( sanitize_email( $email ) );
			if ( $user_id ) {
				$args[ 'user' ] = $user_id;
			} else {
				// Use current user instead
				$args[ 'user' ] = get_current_user_id();
			}
			/**
			 * Filter the ID of the admin user assigned to the destination blog
			 *
			 * @param int   $user_id
			 * @param array $args
			 */
			$args[ 'user' ] = apply_filters( 'tribe/project/copy-blog/user', $args[ 'user' ], $args );


			$config = new Copy_Configuration( $args );

			/**
			 * The action to kick off a blog copy
			 *
			 * @param Copy_Configuration $config
			 */
			do_action( 'tribe/project/copy-blog/copy', $config );

			add_settings_error( self::NAME, 'blog_copy_init', __( 'Your copy is in progress.', 'tribe' ), 'updated' );
		}
		set_transient( 'settings_errors', get_settings_errors(), 30 );

		wp_safe_redirect( network_admin_url( 'sites.php?page=' . self::NAME ) );
		exit();
	}

	/**
	 * @param $submission
	 *
	 * @return \WP_Error
	 */
	private function validate_submission( $submission ) {
		$error = new \WP_Error();
		if ( ! isset( $submission[ '_wpnonce' ] ) || ! wp_verify_nonce( $submission[ '_wpnonce' ], self::NAME ) ) {
			$error->add( 'invalid_nonce', __( 'Error while saving. Please try again.', 'tribe' ) );

			return $error;
		}
		if ( empty( $submission[ self::NAME ] ) ) {
			$error->add( 'empty_request', __( 'Unable to parse the request. Please try again.', 'tribe' ) );

			return $error;
		}

		if ( empty( $submission[ self::NAME ][ 'src' ] ) ) {
			$error->add( 'empty_src', __( 'Please select a blog to copy.', 'tribe' ) );
		}

		if ( empty( $submission[ self::NAME ][ 'address' ] ) ) {
			$error->add( 'empty_address', __( 'Please provide an address for the new blog.', 'tribe' ) );
		}

		if ( empty( $submission[ self::NAME ][ 'title' ] ) ) {
			$error->add( 'empty_title', __( 'Please provide a title for the new blog.', 'tribe' ) );
		}

		return $error;
	}
}