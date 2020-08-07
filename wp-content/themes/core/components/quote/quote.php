<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\quote\Controller::factory( $args );
?>

<blockquote
	<?php echo $controller->classes(); ?>
	<?php echo $controller->attributes(); ?>
>

	<?php if ( ! empty( $controller->quote ) ) { ?>
		<h2 class="c-quote__text h4">
			<?php echo $controller->quote; ?>
		</h2>
	<?php } ?>

	<?php if ( $controller->has_citation() ) { ?>
		<cite class="c-quote__cite">

			<?php echo $controller->render_image(); ?>

			<span class="c-quote__cite-text">
				<?php if ( ! empty( $controller->cite_name ) ) { ?>
					<span class="c-quote__cite-name">
						<?php echo $controller->cite_name; ?>
					</span>
				<?php } ?>
				<?php if ( ! empty( $controller->cite_title ) ) { ?>
					<span class="c-quote__cite-title">
						<?php echo $controller->cite_title; ?>
					</span>
				<?php } ?>
			</span>

		</cite>
	<?php } ?>

</blockquote>
