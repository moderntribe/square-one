<?php

/**
 * The default view for rendering a collection of modular panels.
 * Override this by creating modular-content/collection-wrapper.php in
 * your theme directory.
 *
 * IMPORTANT: the data attribute is REQUIRED for the live preview iframe to function.
 *
 * @var string $panels The rendered HTML of the panels
 */
?>
<div class="panel-collection" data-modular-content-collection>
	<?php echo $panels; ?>
</div>
