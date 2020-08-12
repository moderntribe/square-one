<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\quote\Controller::factory( $args );
?>

<blockquote
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>

	<?php if ( ! empty( $c->quote ) ) { ?>
		<h2 class="c-quote__text h4">
			<?php echo $c->quote; ?>
		</h2>
	<?php } ?>

	<?php if ( $c->has_citation() ) { ?>
		<cite class="c-quote__cite">

			<?php echo $c->render_image(); ?>

			<span class="c-quote__cite-text">
				<?php if ( ! empty( $c->cite_name ) ) { ?>
					<span class="c-quote__cite-name">
						<?php echo $c->cite_name; ?>
					</span>
				<?php } ?>
				<?php if ( ! empty( $c->cite_title ) ) { ?>
					<span class="c-quote__cite-title">
						<?php echo $c->cite_title; ?>
					</span>
				<?php } ?>
			</span>

		</cite>
	<?php } ?>

</blockquote>
