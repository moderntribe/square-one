<?php
/**
 * Template Tags: Schema
 */


/**
 * Get the proper schema type
 *
 * @since core 1.0
 * @since string
 */

function get_schema_type( $post_type = null ) {

	// Default: Posts
	$schema_type = 'BlogPosting';

	// CPT: Events
	if( $post_type == 'tribe_events' ) {
		$schema_type = 'Event';
	}

	return $schema_type;

}


/**
 * JSON-LD Schema: WebSite
 *
 * @link http://schema.org/WebSite
 */

function the_website_schema_as_json_ld() {

	$site_url = trailingslashit( site_url() );

?>

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebSite",
		"name": "<?php bloginfo( 'name' ); ?>",
		"url": "<?php echo $site_url; ?>",
		"author": "<?php bloginfo( 'name' ); ?>",
		"copyrightHolder": "<?php bloginfo( 'name' ); ?>",
		"creator": "<?php bloginfo( 'name' ); ?>",
		"potentialAction": {
	        "@type": "SearchAction",
	        "target": "<?php echo $site_url; ?>?s={search_term_string}",
	        "query-input": "required name=search_term_string"
		}
	}
	</script>

<?php }


/**
 * JSON-LD Schema: WebPage
 *
 * @link http://schema.org/WebPage
 */

function the_webpage_schema_as_json_ld() {

	// Schema Type
	$schema_type = ( is_search() ) ? 'SearchResultsPage' : 'WebPage';

	// Page Name
	$name = ( is_front_page() ) ? 'Home' : get_page_title();

	// Content, Excerpt & Image
	$content = $excerpt = $image = '';
	$object_id = get_queried_object_id();
	if( ! empty( $object_id ) ) {
		$post_object = get_post( $object_id );
		$content     = ( ! empty( $post_object->post_content ) ) ? json_encode( $post_object->post_content ) : '';
		$excerpt     = ( ! empty( $post_object->post_excerpt ) ) ? json_encode( $post_object->post_excerpt ) : '';
		$image       = ( has_post_thumbnail( $object_id ) ) ? wp_get_attachment_url( get_post_thumbnail_id( $object_id ) ) : '';
	}

?>

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "<?php echo $schema_type; ?>",
		<?php if ( ! empty( $name ) ) { ?>
			"name": "<?php echo $name; ?>",
		<?php } ?>
		"url": "<?php echo trailingslashit( site_url() ); ?>",
		<?php if ( ! empty( $content ) ) { ?>
			"mainContentOfPage": <?php echo $content; ?>,
			"text": <?php echo $content; ?>,
		<?php } ?>
		<?php if ( ! empty( $excerpt ) ) { ?>
			"description": <?php echo $excerpt; ?>,
		<?php } ?>
		<?php if ( ! empty( $image ) ) { ?>
			"primaryImageOfPage": "<?php echo $image; ?>",
			"image": "<?php echo $image; ?>", 
		<?php } ?>
		"author": "<?php bloginfo( 'name' ); ?>",
		"copyrightHolder": "<?php bloginfo( 'name' ); ?>",
		"creator": "<?php bloginfo( 'name' ); ?>"
	}
	</script>

<?php }


/**
 * JSON-LD Schema: Organization
 *
 * @link http://schema.org/Organization
 */

function the_organization_schema_as_json_ld() {

?>

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Organization",
		"name": "<?php bloginfo( 'name' ); ?>",
		"legalName": "<?php bloginfo( 'name' ); ?>",
		"url": "<?php echo trailingslashit( site_url() ); ?>"
	}
	</script>

<?php }


/**
 * JSON-LD Schema: Posts
 *
 * @link http://schema.org/docs/full.html
 */

function the_posts_schema_as_json_ld() {

	$schema_type = get_schema_type( get_post_type() );
	$excerpt     = get_the_excerpt();
	$excerpt     = ( ! empty( $excerpt ) ) ? json_encode( $excerpt ) : '';
	$content     = get_the_content();
	$content     = ( ! empty( $content ) ) ? json_encode( $content ) : '';
	$image       = ( has_post_thumbnail() ) ? wp_get_attachment_url( get_post_thumbnail_id() ) : '';
	$categories  = get_the_category();

?>

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "<?php echo $schema_type; ?>",
		"name": "<?php the_title(); ?>",
		"headline": "<?php the_title(); ?>",
		"url": "<?php the_permalink(); ?>",
		<?php // Featured Image
		if ( ! empty( $image ) ) { ?>
			"image": "<?php echo $image; ?>",
			"thumbnailUrl": "<?php echo $image; ?>",
		<?php } ?>
		<?php // Excerpt
		if ( ! empty( $excerpt ) ) { ?>
			"description": <?php echo $excerpt; ?>,
		<?php } ?>
		<?php // Content
		if ( ! empty( $content ) ) { ?>
			"articleBody": <?php echo $content; ?>,
			"text": <?php echo $content; ?>,
		<?php } ?>
		<?php // Categories
		if( ! empty( $categories ) ) { ?>
			"keywords": [
				<?php
				$cat_count = count( $categories ); $cati = 1;
				foreach ( $categories as $category ) {
					$cat_separator = ( $cati !== $cat_count ) ? ',' : '';
					echo '"'. esc_html( $category->name ) .'"'. $cat_separator;
					$cati++;
				} ?>
	        ],
        <?php } ?>
		<?php // Comments
		if( have_comments() ) { ?>
			"comment": [
				<?php $comments = get_approved_comments( get_the_ID() );
				$c_count = count( $comments ); $ci = 1;
				foreach( $comments as $comment ) {
					$c_separator = ( $ci !== $c_count ) ? ',' : '';
					?>
					{
						"@type": "Comment",
						"name": "<?php echo $comment->comment_author; ?>",
						"creator": "<?php echo $comment->comment_author; ?>",
						"parentItem": "<?php the_permalink(); ?>",
						"text": <?php echo json_encode( $comment->comment_content ); ?>,
						"description": <?php echo json_encode( $comment->comment_content ); ?>,
						"datePublished": "<?php esc_attr_e( $comment->comment_date ); ?>",
						"dateCreated": "<?php esc_attr_e( $comment->comment_date ); ?>"
					}
					<?php echo $c_separator;
					$ci++;
				}
			?>
			],
			"commentCount": "<?php echo get_comments_number(); ?>",
			"discussionUrl": "<?php the_permalink(); ?>#comments",
		<?php } ?>
		"author": "<?php the_author(); ?>",
		"copyrightHolder": "<?php bloginfo( 'name' ); ?>",
		"creator": "<?php bloginfo( 'name' ); ?>",
		"datePublished": "<?php esc_attr_e( get_the_time( 'c' ) ); ?>",
		"dateCreated": "<?php esc_attr_e( get_the_time( 'c' ) ); ?>"
	}
	</script>

<?php }