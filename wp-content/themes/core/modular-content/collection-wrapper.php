<?php
/**
 * The default view for rendering a collection of modular panels.
 * Override this by creating modular-content/collection-wrapper.php in
 * your theme directory.
 *
 * The template MUST contain the string "[panels]", which will be replaced
 * with the contents of all panels in the collection.
 *
 * @var string $panels The rendered HTML of the panels
 */
?>

<div class="panels-collection">

	<?php echo $panels; ?>

</div>
