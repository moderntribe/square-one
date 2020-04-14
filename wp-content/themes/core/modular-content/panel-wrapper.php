<?php

use Tribe\Project\Theme\Util;

/**
 * A wrapper around each modular panel.
 *
 * @var \ModularContent\Panel $panel
 * @var int $index 0-based count of panels rendered thus far
 * @var string $type panel type
 * @var string $html The rendered HTML of the panel
 */

$classes    = ['panel'];

// Child Panel
if( $panel->get_depth() >= 1 ) {

	$classes[] = 'panel-child';
	$classes[] = sprintf( 'panel--type-%s', sanitize_html_class( $type ) );

	?>

	<article <?php echo Util::class_attribute( $classes, true ); ?>>

		<?php echo $html; ?>

	</article>

<?php }

// Parent Panel.
else { ?>

	<?php echo $html; ?>

<?php } ?>
