<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\gallery_slider\Gallery_Slider_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attributes(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( ! empty( $c->get_header_args() ) ) { ?>
			<div class="l-container">
				<?php get_template_part(
					'components/content_block/content_block',
					null,
					$c->get_header_args()
				); ?>
			</div>
		<?php } ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php get_template_part(
				'components/slider/slider',
				null,
				$c->get_slider_args()
			); ?>
		</div>

	</div>
</section>
