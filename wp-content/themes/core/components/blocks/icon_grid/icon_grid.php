<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\icon_grid\Icon_Grid_Controller::factory( $args );
?>
<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		); ?>

		<ul <?php echo $c->get_content_classes(); ?>>

			<?php foreach ( $c->get_icon_card_args() as $card_args ) { ?>
				<?php get_template_part( 'components/card/card', null, $card_args ); ?>
			<?php } ?>

		</ul>

	</div>
</section>

