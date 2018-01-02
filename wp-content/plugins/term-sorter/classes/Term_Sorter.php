<?php
// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );

/**
 * Term Sorter
 *
 * Main Class for the Term Sorter Plugin
 *
 *
 * @package Term Sorter
 *
 * @uses runs via self::init() on the plugins_loaded hook
 */
class Term_Sorter {
	
	const VERSION = 1.0;
	
	/**
	 * Plugin path
	 * 
	 * Used along with self::plugin_path() to return path to this plugins files
	 * 
	 * @var string
	 */
	private static $plugin_path = false;
	
    /**
	 * Plugin url
     * To keep track of this plugins root dir
     * Used along with self::plugin_url() to return url to plugin files
     * 
     * @var string
     * 
     */
    private static $plugin_url;
	
	
	/**
	 * Constructor
	 * 
	 * @uses added to plugins_loaded hook by plugins main file
	 * 
	 * 
	 */
	public function __construct() {
		$this->hooks();
		
	}
	
	
	/**
	 * Check For Database Column
	 * 
	 * Checks for the term_order column and add it if not exist
	 * 
	 * @uses $wpdb
	 * 
	 * @uses added to init hook by self::hooks()
	 * 
	 */
	public static function check_for_database_column(){
		global $wpdb;
		if( self::VERSION != get_option( 'term_sorter_column_exists' ) ){
			$wpdb->query( "ALTER TABLE $wpdb->terms ADD term_order INT" );
			update_option( 'term_sorter_column_exists', self::VERSION );
		}
		
	}
	
	
	
    /**
	 * Hooks
	 * 
	 * Setup appropriate actions and filters
	 * 
	 * @uses called via self::__construct()
	 * 
	 * @return void
	 */
	private function hooks(){	
		add_action( 'init', array( __CLASS__, 'check_for_database_column' ) );
		
		add_action('admin_print_scripts-edit-tags.php', array( __CLASS__, 'js_css' ) );
		add_action('wp_ajax_term_sort_update', array( $this, 'update_order' ) );

		add_filter( 'get_terms_orderby', array( __CLASS__, 'sort_terms_by_order' ) );

	}
	
	
	/**
	 * Sort Terms By Order
	 * 
	 * Sort any call to get_terms by term_order
	 * 
	 * @uses added to the get_terms_orderby filter by self::hooks()
	 * 
	 * @param string $orderby
	 * 
	 * @return string
	 */
	public static function sort_terms_by_order( $orderby ){
		
		if( !empty( $orderby ) ){
			if( strpos( $orderby, 'term_order' ) !== false ){
				 return $orderby;
			} else {			
				$orderby = 't.term_order, '.$orderby;
			}
		} else {
			$orderby = 't.term_order, t.name';
		}

		return $orderby;
	}
	
	
	
	/**
	 * Js Css
	 * 
	 * Add the js for the term sorting
	 * 
	 * @uses added to the admin_print_scripts-edit-tags.php hook by self::hooks()
	 * 
	 * @return void
	 */
	public static function js_css(){
		wp_enqueue_script(
		    'term-sorter', 
			self::plugin_url( 'resources/js/term-sorter.js' ), 
			array('jquery', 'jquery-ui-sortable')
	    );	
		
		wp_enqueue_style(
			'term-sorter', 
			self::plugin_url( 'resources/css/term-sorter.css' )
	    );
		
		$url = get_admin_url().'images/';
		wp_localize_script('term-sorter', 'adminImagesUrl', $url);
	}
	
	
	/**
	 * Update Order
	 * 
	 * Updates the order of the terms via ajax
	 * 
	 * @uses added to the wp_ajax_term_sort_update hook by self::hooks()
	 * 
	 * @return void
	 */
	public function update_order(){
		if ( empty( $_POST['id'] ) || empty( $_POST['taxonomy']) || ( !isset( $_POST['previd'] ) && !isset( $_POST['nextid'] ) ) ) die(-1);
     	if ( !$term = get_term( $_POST['id'], $_POST['taxonomy']) ) die(-1);

		
		$taxonomy = $_POST['taxonomy'];
		$previd = empty( $_POST['previd'] ) ? false : (int) $_POST['previd'];
		$nextid = empty( $_POST['nextid'] ) ? false : (int) $_POST['nextid'];
		$start = empty( $_POST['start'] ) ? 1 : (int) $_POST['start'];
		$new_pos = array(); 
		$return_data = new stdClass;

		$parent_id = $term->parent;
		
		//get the term after this one's parent
		if( !empty( $nextid ) ){
			$next_term = get_term( $nextid, $taxonomy );
			$next_term_parent = $next_term->parent;	
		} else {
			$next_term_parent = false;
		}


		//if the term before this one is the parent of the term after this one all 3 should share same parent
		if ( $previd == $next_term_parent ) {		
			$parent_id = $next_term_parent;
		
		//if the term after this one does not share the same parent we will use the parent of the one before
		} elseif ( $next_term_parent != $parent_id ) {
			
			if( !empty( $previd ) ){
				$prev_term = get_term( $nextid, $taxonomy );
				$prev_term_parent = $prev_term->parent;
			} else {
				$prev_term_parent = false;
			}
			
			if ( $prev_term_parent != $parent_id ) {
				$parent_id = ( $prev_term_parent != false ) ? $prev_term_parent : $next_term_parent;
			}
		}
		
		
		// if the next term's parent isn't our parent, we no longer care about it
		if ( $next_term_parent != $parent_id )
			$nextid = false;
		
		
		$siblings = get_terms( $taxonomy, 
				array(
					'parent'     => $parent_id,
					'orderby'    => 'term_order name',
					'hide_empty' => false
				)
		);

		foreach( $siblings as $sibling ) {

			// don't handle the actual term
			if ( $sibling->term_id == $term->term_id )
				continue;

			// if this is the term that comes after our repositioned term, set our repositioned term position and increment menu order
			if ( $nextid == $sibling->term_id ) {
				wp_update_term( $term->term_id, $taxonomy,
					array(
						'parent'     => $parent_id
					)
				);
				$this->update_term_order($term->term_id, $start, $taxonomy );

				$ancestors = get_ancestors( $term->term_id, $taxonomy );

				$new_pos[$term->term_id] = array(
					'term_order'	=> $start,
					'parent'	    => $parent_id,
					'depth'			=> count( $ancestors ),
				);
				$start++;
			}

			// if repositioned term has been set, and new items are already in the right order, we can stop
			if ( isset( $new_pos[$term->term_id] ) && $sibling->term_order >= $start ) {
				$return_data->next = false;
				break;
			}

			// set the term order of the current sibling and increment the term order
			if ( $sibling->term_order != $start ) {
				$this->update_term_order($sibling->term_id, $start, $taxonomy );
			}
			$new_pos[$sibling->term_id] = $start;
			$start++;

			if ( !$nextid && $previd == $sibling->term_id ) {
				wp_update_term( $term->term_id, $taxonomy,
					array(
						'parent'     => $parent_id
					)
				);
				$this->update_term_order($term->term_id, $start, $taxonomy );
				
				$ancestors = get_ancestors( $term->term_id, $taxonomy );
				$new_pos[$term->term_id] = array(
					'term_order'	=> $start,
					'parent' 	    => $parent_id,
					'depth' 		=> count($ancestors) );
				$start++;
			}

		}

		
		//if we moved a term with children we must refresh the page
		$children = get_terms( $taxonomy, 
			array(
				'parent'     => $term->term_id,
				'orderby'    => 'term_order name',
				'hide_empty' => false
			)
		);
		if ( ! empty( $children ) ){
			die( 'children' );
		}
		


		$return_data->new_pos = $new_pos;

		die( json_encode( $return_data ) );

	}
	
	
	/**
	 * Update Term Order
	 * 
	 * Updates a term_order column in the database
	 * 
	 * @param int $term_id - term to update
	 * @param int $term_order - new order
	 * 
	 * @return void
	 * 
	 */
	public function update_term_order( $term_id, $term_order, $taxonomy ){
		global $wpdb;
		
		$wpdb->update( 
			$wpdb->terms, 
			array( 
				'term_order' => $term_order 
			), 
			array( 
				'term_id' => $term_id
			) 
		);

		if( function_exists( 'update_term_meta' ) ){
			update_term_meta( $term_id, 'term_modified', time() );
		}
		clean_term_cache( $term_id, $taxonomy, true );

		do_action( "sort_term", $term_id, $term_id, $taxonomy );

	}
	
	
	/**
	 * Plugin Path
	 * 
	 * Retreive the path this plugins dir
	 * 
	 * @param string [$append] - optional path file or name to add
	 * @return string
	 * 
	 * @uses self::$plugin_path
	 * 
	 * @return string
	 */
	public static function plugin_path( $append = '' ){
		
		if( !self::$plugin_path ){
			self::$plugin_path = trailingslashit( dirname( dirname(__FILE__) ) );
		} 

		return self::$plugin_path.$append;	
	}
	
	
	/**
	 * Plugin Url
	 * 
	 * Retreive the url this plugins dir
	 * 
	 * @param string [$append] - optional path file or name to add
	 * @return string
	 * 
	 * @uses self::$plugin_url
	 * 
	 * 
	 * @return string
	 */
	public static function plugin_url( $append = '' ){
		
		if( !self::$plugin_url ){
			self::$plugin_url = plugins_url().'/'.trailingslashit( basename( self::plugin_path() ) );
		} 

		return self::$plugin_url.$append;	
	}
	
	
	    //********** SINGLETON FUNCTIONS **********/

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
		self::$instance = self::get_instance();
	}

	/**
	* Get (and instantiate, if necessary) the instance of the
	* class
	*
	* @static
	* @return self
	*/
	public static function get_instance() {
		if (!is_a(self::$instance, __CLASS__)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	
	
}
	