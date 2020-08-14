<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\hero\Controller::factory( $args );  ?>
<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>

		<?php if ( ! empty( $c->media ) ) { ?>
			<div <?php echo $c->media_classes(); ?> >
				<?php echo $c->media; ?>
			</div>
		<?php } ?>

		<div <?php echo $c->content_classes(); ?>>
			<?php echo $c->get_content(); ?>
		</div>
	</div>
</section>
