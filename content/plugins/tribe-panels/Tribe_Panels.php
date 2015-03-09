<?php

class Tribe_Panels {
	private $view_directory = '';

	public function set_view_directory( $directory ) {
		$this->view_directory = untrailingslashit( $directory );
	}

	private function custom() {
		$panel = new Panel( 'custom' );
		$panel->set_label( __( 'Custom' ) );
		$panel->set_description( __( 'Custom content' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/custom.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Content() );
		$panel->set_view( $this->view_directory . '/custom.php' );
	}

	private function featured_event() {
		$panel = new Panel( 'featured-event' );
		$panel->set_label( __( 'Featured Event' ) );
		$panel->set_description( __( 'Feature an upcoming event' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/featured-event.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Select( array(
			'name'             => 'event-cat',
			'label'            => __( 'Event Category', 'tribe' ),
			'options_callback' => array( $this, 'event_cat_options' ),
		) ) );
		$panel->add_input( new Panel_Input_Checkbox( array(
			'name'  => 'image-only',
			'label' => __( 'Only feature events with a featured image', 'tribe' ),
		) ) );
		$panel->set_view( $this->view_directory . '/featured-event.php' );
	}

	private function events() {
		$panel = new Panel( 'events' );
		$panel->set_label( __( 'Events' ) );
		$panel->set_description( __( 'Display a panel that has specials and upcoming events.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/events.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->set_view( $this->view_directory . '/events.php' );
	}

	private function image() {
		$panel = new Panel( 'image' );
		$panel->set_label( __( 'Image Panel' ) );
		$panel->set_description( __( 'An image panel with three positioning options.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/image.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Image', 'tribe' ),
		                                                 'name'  => 'image' ) ) );
		$panel->add_input( new Panel_Input_Select( array( 'label'   => 'Image Placement',
		                                                  'name'    => 'image-placement',
		                                                  'options' => array( '1' => 'Full Width',
		                                                                      '2' => 'Left',
		                                                                      '3' => 'Right' ) ) ) );
		$panel->add_input( new Panel_Input_Content() );
		$panel->add_input( new Panel_Input_Checkbox( array( 'name' => 'show-link-list', 'label' => 'Show Link Menu', 'description' => 'Select this to show a menu of your choosing at the bottom of the panel.' ) ) );

		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Link List Heading', 'tribe' ),
		                                                'name'  => 'link-list-heading' ) ) );
		$panel->add_input( new Panel_Input_Select( array( 'name'             => 'link-list',
		                                                  'label'            => 'Navigation Links',
		                                                  'options_callback' => array( $this, 'nav_menu_options' ) ) )
		);
		$panel->set_view( $this->view_directory . '/image.php' );
	}

	private function two_col() {
		$panel = new Panel( 'two_col' );
		$panel->set_label( __( 'Two Column' ) );
		$panel->set_description( __( 'Custom content in a two column layout.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/2col.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Column 1 Heading', 'tribe' ),
		                                                'name'  => 'col-1-heading' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'    => __( 'Column 1 Content', 'tribe' ),
		                                                    'name'     => 'col-1-content',
		                                                    'richtext' => true ) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Column 1 Image', 'tribe' ),
		                                                 'name'  => 'col-1-image' ) ) );
		$panel->add_input( new Panel_Input_Link( array(
			'label' => __( 'Column 1 Image Link', 'tribe' ),
			'name'  => 'col-1-image-url'
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Column 2 Heading', 'tribe' ),
		                                                'name'  => 'col-2-heading' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'    => __( 'Column 2 Content', 'tribe' ),
		                                                    'name'     => 'col-2-content',
		                                                    'richtext' => true ) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Column 2 Image', 'tribe' ),
		                                                 'name'  => 'col-2-image' ) ) );
		$panel->add_input( new Panel_Input_Link( array(
			'label' => __( 'Column 2 Image Link', 'tribe' ),
			'name'  => 'col-2-image-url'
		) ) );
		$panel->set_view( $this->view_directory . '/two_column.php' );
	}

	private function three_col() {
		$panel = new Panel( 'three_col' );
		$panel->set_label( __( 'Three Column' ) );
		$panel->set_description( __( 'Custom content in a three column layout.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/3col.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Column 1 Heading', 'tribe' ),
		                                                'name'  => 'col-1-heading' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'    => __( 'Column 1 Content', 'tribe' ),
		                                                    'name'     => 'col-1-content',
		                                                    'richtext' => true ) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Column 1 Image', 'tribe' ),
		                                                 'name'  => 'col-1-image' ) ) );
		$panel->add_input( new Panel_Input_Link( array(
			'label' => __( 'Column 1 Image Link', 'tribe' ),
			'name'  => 'col-1-image-url'
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Column 2 Heading', 'tribe' ),
		                                                'name'  => 'col-2-heading' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'    => __( 'Column 2 Content', 'tribe' ),
		                                                    'name'     => 'col-2-content',
		                                                    'richtext' => true ) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Column 2 Image', 'tribe' ),
		                                                 'name'  => 'col-2-image' ) ) );
		$panel->add_input( new Panel_Input_Link( array(
			'label' => __( 'Column 2 Image Link', 'tribe' ),
			'name'  => 'col-2-image-url'
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Column 3 Heading', 'tribe' ),
		                                                'name'  => 'col-3-heading' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'    => __( 'Column 3 Content', 'tribe' ),
		                                                    'name'     => 'col-3-content',
		                                                    'richtext' => true ) ) );
		$panel->add_input( new Panel_Input_Image( array( 'label' => __( 'Column 3 Image', 'tribe' ),
		                                                 'name'  => 'col-3-image' ) ) );
		$panel->add_input( new Panel_Input_Link( array(
			'label' => __( 'Column 3 Image Link', 'tribe' ),
			'name'  => 'col-3-image-url'
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Link List Heading', 'tribe' ),
		                                                'name'  => 'link-list-heading' ) ) );
		$panel->add_input( new Panel_Input_LinkList( array( 'name'  => 'link-list',
		                                                    'label' => 'Link List' ) )
		);
		$panel->set_view( $this->view_directory . '/three_column.php' );
	}

	private function three_col_posts() {
		$panel = new Panel( 'three_col_posts' );
		$panel->set_label( __( 'Three Column Posts' ) );
		$panel->set_description( __( 'A post loop in a three column layout.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/3col-posts.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label' => __( 'Panel Link Display Text', 'tribe' ),
		                                                'name'  => 'panel-link-text' ) ) );
		/*$panel->add_input( new Panel_Input_Link( array( 'name'        => 'panel-link',
														 'label'       => __( 'Link Panel to URL', 'tribe' ),
														 'limit'       => 1,
														 'description' => __( 'Select a post or page to link the panel to (Uses text above for display)', 'tribe' ) ) ) );/**/
		$panel->add_input( new Panel_Input_Query( array( 'name'        => 'selected-posts',
		                                                 'label'       => __( 'Select Posts (3 Posts)', 'tribe' ),
		                                                 'limit'       => 3,
		                                                 'description' => __( 'Please select the required amount of posts to avoid any display issues.', 'tribe' ) ) ) );
		$panel->set_view( $this->view_directory . '/three_column_posts.php' );
	}

	private function social_networks() {
		$panel = new Panel( 'social_networks' );
		$panel->set_label( __( 'Social Networks' ) );
		$panel->set_description( __( 'Embed social network content.' ) );
		$panel->set_icon( plugins_url( 'assets/panel-thumbs/social.png', dirname( __FILE__ ) ) );
		$panel->add_input( new Panel_Input_Title() );
		$panel->add_input( new Panel_Input_Text( array(
			'name'  => 'nav-title',
			'label' => __( 'Nav Title', 'tribe' )
		) ) );
		$panel->add_input( new Panel_Input_Checkbox( array( 'name' => 'show-twitter', 'label' => 'Show Twitter Timeline', 'description' => 'Check this box to show your tweets in this panel.' ) ) );
		$panel->add_input( new Panel_Input_Textarea( array( 'label'       => __( 'Twitter Embed Code', 'tribe' ),
		                                                    'name'        => 'twitter-embed-code',
		                                                    'richtext'    => false,
		                                                    'description' => __( 'Log into your twitter account, then go to <a href="https://twitter.com/settings/widgets/new" target="_blank">Settings → Widgets → Create New</a>. Enter the associated username, click "Create Widget", copy the code that is provided and paste that into this textarea.', 'tribe' ) ) ) );
		$panel->add_input( new Panel_Input_Checkbox( array( 'name' => 'show-facebook', 'label' => 'Show Facebook Like Box', 'description' => 'Check this box to show a facebook like box of your choosing in this panel.' ) ) );
		$panel->add_input( new Panel_Input_Text( array( 'label'       => __( 'Facebook URL or ID', 'tribe' ),
		                                                'name'        => 'facebook-page-slug',
		                                                'description' => __( 'The URL of your Facebook Page, or your Page ID. E.g., "http://www.facebook.com/moderntribeinc", or just "moderntribeinc".', 'tribe' ) ) ) );
		$panel->set_view( $this->view_directory . '/social_panel.php' );
	}

	public function nav_menu_options() {
		$menus   = wp_get_nav_menus();
		$options = array();
		foreach ( $menus as $menu ) {
			$options[$menu->term_id] = $menu->name;
		}

		return $options;
	}

	public function event_cat_options() {
		$terms      = get_terms( TribeEvents::TAXONOMY, array(
			'hide_empty' => false,
		) );
		$options    = array();
		$options[0] = __( 'Any Category', 'tribe' );
		foreach ( $terms as $term ) {
			$options[$term->term_id] = $term->name;
		}

		return $options;
	}


	public static function initialize_panels() {

		if ( ! class_exists( 'Panel' ) ) return;

		// add_filter( 'panels_context_post_type_options', array( __CLASS__, 'set_available_context_post_types' ), 10, 1 );
		add_filter( 'panels_query_taxonomy_options', array( __CLASS__, 'set_available_query_taxonomies' ), 10, 1 );
		add_filter( 'panel_input_query_post_types', array( __CLASS__, 'filter_query_post_types' ), 10, 2 );

		$dir = apply_filters( 'tribe_panels_directory', get_stylesheet_directory() . '/panels' );
		if ( ! file_exists( $dir ) ) {
			$dir = apply_filters( 'tribe_panels_directory', get_template_directory() . '/panels' );
		}
		$panel_loader = new self();
		$panel_loader->set_view_directory( $dir );

		try {
			$panel_loader->custom();
			$panel_loader->events();
			$panel_loader->featured_event();
			$panel_loader->image();
			$panel_loader->two_col();
			$panel_loader->three_col();
			$panel_loader->three_col_posts();
			$panel_loader->social_networks();
		} catch ( Exception $e ) {
			error_log( "Error loading panels" );
			error_log( print_r( $e, true ) );
		}

	}

	public static function set_available_query_taxonomies( $taxonomies ) {
		$taxonomies[] = 'category';

		return $taxonomies;
	}

	public static function filter_query_post_types( $post_types, $panel_id ) {
		$panel_post = new Panel_Post( $panel_id );
		$type       = $panel_post->get_type();
		if ( $type == 'featured-event' ) {
			return 'tribe_events';
		}

		return $post_types;
	}

	/**
	 * Contextualize based on post type
	 *
	 * @param $post_types
	 *
	 * @return array
	 */
	public static function set_available_context_post_types( $post_types ) {

		//if ( class_exists( 'Tribe_Some_Custom_Post_Type' ) ) {
		//	$post_types[] = Tribe_Some_Custom_Post_Type::POST_TYPE;
		//}

		return $post_types;
	}
}

?>