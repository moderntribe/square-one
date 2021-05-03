<?php declare( strict_types=1 );
/**
 * Sample Route template.
 *
 * @package Project
 */

get_header();
?>
<p><?php echo esc_html__( 'Year: ') . get_query_var( 'year' ); ?></p>
<p><?php esc_html_e( 'Sample Route', 'tribe' ); ?></p>

<?php
get_footer();