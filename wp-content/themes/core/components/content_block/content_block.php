<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\content_block\Controller::factory( $args );

// text component still makes sense for less painful ability to
// have fine grain control over text tag & classes (for sink) as
// otherwise the component will have completely fixed markup/styles
// which is appropriate in some cases, otherwise, if we want that now,
// it means you need to spin up specific component args for those areas
// you want control over and that feels less than ideal...
// Same q for quote component too, too much?

// chat out with Davee on Monday
?>

<<?php echo $controller->tag; ?>
	<?php echo $controller->wrapper_classes(); ?>
	<?php echo $controller->wrapper_attributes(); ?>
>

	<p class="c-content-block__leadin h6"></p>

	<h2 class="c-content-block__title h1"></h2>

	<div class="c-content-block__content t-sink s-sink"></div>

	<?php echo $controller->render_cta(); ?>

</<?php echo $controller->tag; ?>>
