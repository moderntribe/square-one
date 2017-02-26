<?php

use Tribe\Project\Theme\Panel_Util;

/**
 * A wrapper around each modular panel.
 *
 * @var \ModularContent\Panel $panel
 * @var int $index 0-based count of panels rendered thus far
 * @var string $type panel type
 * @var string $html The rendered HTML of the panel
 */

$panel_util = new Panel_Util();
$classes    = ['panel'];

// Child Panel
if( $panel->get_depth() >= 1 ) {

	$classes[] = 'panel-child';

	?>

	<article <?php echo $panel_util->wrapper_classes( $panel, $classes ); ?>>

		<?php echo $html; ?>

	</article>

<?php }

// Parent Panel
else { ?>

	<?php // Zebra striping
	$classes[] = $index % 2 == 0 ? 'panel--even' : 'panel--odd'; ?>

	<section
		<?php echo $panel_util->wrapper_classes( $panel, $classes ); ?>
		data-index="<?php echo $index; ?>"
		data-js="panel"
		data-type="<?php esc_attr_e( $panel->get( 'type' ) ); ?>"
		data-modular-content
	>

		<?php echo $html; ?>

	</section>

<?php } ?>
