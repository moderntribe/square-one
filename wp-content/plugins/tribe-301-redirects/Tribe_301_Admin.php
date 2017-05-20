<?php


class Tribe_301_Admin {
	const SLUG = 'tribe-301-options';
	const OPTION = 'tribe-301-redirects';

	/** @var self */
	private static $instance = NULL;

	public function __construct() {
	}

	private function add_hooks() {
		add_action( 'admin_menu', array( $this, 'setup_admin_page' ) );
	}

	public function setup_admin_page() {
		add_options_page(
			__( '301 Redirects', 'tribe' ),
			__( '301 Redirects', 'tribe' ),
			'manage_options',
			self::SLUG,
			array( $this, 'display_settings_page' )
		);

		add_settings_section( 'redirects', '', array( $this, 'display_redirect_settings' ), self::SLUG );
		register_setting(
			self::SLUG,
			self::OPTION,
			array( $this, 'sanitize_options' )
		);


		add_settings_field(
			self::OPTION.'-bulk',
			__('Bulk Add Redirects', 'tribe'),
			array( $this, 'display_bulk_entry_field' ),
			self::SLUG,
			'redirects'
		);

		register_setting(
			self::SLUG,
			self::OPTION.'-bulk',
			array( $this, 'save_bulk_entries' )
		);

	}

	public function display_settings_page() {
		$title = __('301 Redirects', 'tribe');
		$action = admin_url('options.php');
		ob_start();
		//require(ABSPATH . 'wp-admin/options-head.php');
		echo "<form action='".$action."' method='post'>";
		settings_fields(self::SLUG);
		do_settings_sections(self::SLUG);
		submit_button();
		echo "</form>";
		$content = ob_get_clean();

		printf( '<div class="wrap"><h2>%s</h2>%s</div>', $title, $content );
		wp_enqueue_script( 'tribe-301-admin', plugins_url('tribe-301-admin.js', __FILE__), array('jquery-ui-sortable'), 2, TRUE );
		wp_enqueue_style( 'tribe-301-admin', plugins_url('tribe-301-admin.css', __FILE__), array('dashicons'), 2 );
	}

	public function display_redirect_settings() {
		?>
		<table class="tribe-redirects">
			<thead>
				<tr>
					<th><?php _e( 'Request', 'tribe' ); ?></th>
					<th><?php _e( 'Destination', 'tribe' ); ?></th>
					<th><?php _e('Regex', 'tribe'); ?></th>
					<th><?php _e('Delete', 'tribe'); ?></th>
				</tr>
				<tr>
					<td><small><?php _e('example: /about.htm'); ?></small></td>
					<td><small><?php printf( __( 'example: %s/about/', 'tribe'), home_url() ) ?></small></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
				<?php echo $this->expand_redirects(); ?>
				<tr>
					<td class="source"><input type="text" name="<?php echo self::OPTION; ?>[0][request]" value="" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
					<td class="destination"><input type="text" name="<?php echo self::OPTION; ?>[0][destination]" value="" style="width:30em;" /></td>
					<td class="regex"><input type="checkbox" name="<?php echo self::OPTION; ?>[0][regex]" value="1" /></td>
					<td class="delete"><input type="checkbox" name="<?php echo self::OPTION; ?>[0][delete]" value="1" /></td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * utility function to return the current list of redirects as form fields
	 */
	function expand_redirects() {
		$redirects = get_option( self::OPTION, array() );
		$i = 1;
		$output = '';
		$row ='<tr>
						<td class="source"><input type="text" name="%s[%d][request]" value="%s" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
						<td class="destination"><input type="text" name="%s[%d][destination]" value="%s" style="width:30em;" /></td>
						<td class="regex"><input type="checkbox" name="%s[%d][regex]" value="1" %s /></td>
						<td class="delete"><input type="checkbox" name="%s[%d][delete]" value="1" /></td>
					</tr>';
		if ( !empty( $redirects['string'] ) ) {
			foreach ($redirects['string'] as $request => $destination) {
				$output .= sprintf( $row,
					self::OPTION, $i, esc_attr($request),
					self::OPTION, $i, esc_attr($destination),
					self::OPTION, $i, '',
					self::OPTION, $i
				);
				$i++;
			}
		}
		if ( !empty( $redirects['regex'] ) ) {
			foreach ($redirects['regex'] as $request => $destination) {
				$output .= sprintf( $row,
					self::OPTION, $i, esc_attr($request),
					self::OPTION, $i, esc_attr($destination),
					self::OPTION, $i, checked( TRUE, TRUE, FALSE ),
					self::OPTION, $i
				);
				$i++;
			}
		}
		return $output;
	}

	public function sanitize_options( $data ) {
		$redirects = array();
		foreach ( $data as $record ) {
			if ( !empty( $record['delete'] ) ) {
				continue;
			}
			$request = trim( isset($record['request']) ? $record['request'] : '' );
			$request = rtrim( $request, '/' );
			$destination = trim( isset($record['destination']) ? $record['destination'] : '' );
			if ( empty( $request ) || empty( $destination ) ) {
				continue;
			}
			if ( !empty( $record['regex'] ) ) {
				$redirects['regex'][$request] = $destination;
			} else {
				$redirects['string'][$request] = $destination;
			}
		}
		return $redirects;
	}

	public function display_bulk_entry_field() {
		printf( '<p class="description">%s</p>',
			__('Include one redirect per line. The line should include the source path, a tab or comma, then the redirect URL.', 'tribe')
		);
		printf( '<p><label><input type="checkbox" value="regex" name="%s" /> %s</label></p>', self::OPTION.'-bulk-type', __( 'Add as regular expressions', 'tribe' ) );
		printf( '<textarea name="%s" rows="20" cols="80" placeholder="%s"></textarea>',
			self::OPTION.'-bulk',
			__('/source-path', 'tribe').", ".home_url(__('destination-path', 'tribe'))
		);
	}

	public function save_bulk_entries( $data ) {
		$existing_redirects = get_option(self::OPTION, array());
		$rows = explode("\n", $data);
		$count = 0;
		foreach ( $rows as $row ) {
			$args = explode("\t", $row, 2);
			if ( count($args) < 2 ) {
				$args = explode(",", $row, 2);
			}
			if ( count($args) < 2 ) {
				continue; // nothing to do with this one
			}

			// Remove scheme and domain name from source
			$source = trim(str_ireplace(home_url(), '', $args[0]));
			$source = preg_replace('!^https?://.*?/!', '', $source);
			if ( !$source ) {
				continue;
			}
			if ( strpos($source, '/') !== 0 ) {
				$source = '/'.$source;
			}

			$destination = trim($args[1]);
			if ( !$destination ) {
				continue;
			}

			$type = 'string';
			if ( isset( $_POST[self::OPTION.'-bulk-type'] ) && $_POST[self::OPTION.'-bulk-type'] == 'regex' ) {
				$type = 'regex';
			}

			$existing_redirects[$type][$source] = $destination;
			$count++;
		}

		if ( $count > 0 ) {
			remove_filter( 'sanitize_option_' . self::OPTION, array( $this, 'sanitize_options' ) );
			update_option( self::OPTION, $existing_redirects );
			add_filter( 'sanitize_option_' . self::OPTION, array( $this, 'sanitize_options' ) );
		}
		return FALSE;
	}

	public static function instance() {
		if ( empty( self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function init() {
		self::instance()->add_hooks();
	}
} 