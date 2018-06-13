<?php

namespace Tribe\Project\Taxonomies;
use Tribe\Project\Walker\Walker_Category_Radio_List;

/**
 * Class Tribe\Project\Taxonomies\Single_Select_Taxonomy_Meta_Box
 *
 * Handles the meta box for a single-select taxonomy
 *
 */
class Single_Select_Taxonomy_Meta_Box {
	private $taxonomy = '';

	public function __construct( $taxonomy ) {
		$this->taxonomy = $taxonomy;
	}

	public function get_callback() {
		return [ $this, 'callback' ];
	}

	/**
	 * @param \WP_Post $post     Post object.
	 * @param array    $box      {
	 *                           Categories meta box arguments.
	 *
	 * @type string    $id       Meta box ID.
	 * @type string    $title    Meta box title.
	 * @type callback  $callback Meta box display callback.
	 * @type array     $args     {
	 *         Extra meta box arguments.
	 *
	 * @type string    $taxonomy Taxonomy. Default 'category'.
	 *     }
	 * }
	 */
	public function callback( $post, $box ) {
		require_once( dirname( __FILE__ ) . '/Tribe\Project\Walker\Walker_Category_Radio_List.php' );
		?>
		<div id="taxonomy-<?php echo $this->taxonomy; ?>" class="categorydiv">

			<div id="<?php echo $this->taxonomy; ?>-all" class="tabs-panel">
				<?php
				$name = 'tax_input[' . $this->taxonomy . ']';
				echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
				?>
				<ul id="<?php echo $this->taxonomy; ?>checklist" data-wp-lists="list:<?php echo $this->taxonomy; ?>"
				    class="categorychecklist form-no-clear">
					<?php wp_terms_checklist( $post->ID, [ 'taxonomy' => $this->taxonomy, 'walker' => new Walker_Category_Radio_List() ] ); ?>
				</ul>
			</div>
		</div>
		<?php
	}
}