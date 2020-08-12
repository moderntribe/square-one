<?php
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\pagination\single\Controller::factory( $args );

?>
<?php if ( ! empty( $c->previous_post ) || ! empty( $c->next_post ) ) { ?>
	<nav <?php echo $c->wrapper_classes(); ?><?php echo $c->wrapper_attrs(); ?> >

		<h3 <?php echo $c->header_attrs(); ?><?php echo $c->header_classes(); ?>>
			<?php esc_html_e( 'Post Pagination', 'tribe' ); ?>
		</h3>

		<ol <?php echo $c->container_classes(); ?><?php echo $c->container_attrs(); ?>>
			<?php if ( ! empty( $c->previous_post ) ) { ?>
				<li <?php echo $c->list_classes(); ?> <?php echo $c->list_attrs(); ?>>
					<?php get_template_part(
						'components/link/link',
						'pagination',
						$c->previous_post
					); ?>
				</li>
			<?php } ?>

			<?php if ( ! empty( $c->next_post ) ) { ?>
				<li <?php echo $c->list_classes(); ?> <?php echo $c->list_attrs(); ?>>
					<?php get_template_part(
						'components/link/link',
						'pagination',
						$c->next_post
					); ?>
				</li>
			<?php } ?>
		</ol>
	</nav>
<?php }
