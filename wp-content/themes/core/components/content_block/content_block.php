<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\content_block\Controller::factory( $args );
?>

<<?php echo $c->tag; ?>
	<?php echo $c->classes(); ?>
	<?php echo$c->attributes(); ?>
>

	<p class="c-content-block__leadin h6"></p>

	<h2 class="c-content-block__title h1"></h2>

	<div class="c-content-block__content t-sink s-sink"></div>

	<?php echo $c->render_cta(); ?>

</<?php echo $c->tag; ?>>
