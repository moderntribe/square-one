<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\gallery_grid\Gallery_Grid_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attributes(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( ! empty( $c->get_header_args() ) ) { ?>
			<?php get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_header_args()
			); ?>
		<?php } ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php foreach ( $c->get_gallery_img_thumbs() as $img ) {
				get_template_part(
					'components/image/image',
					null,
					$img
				);
			} ?>
		</div>
	
	</div>
</section>

<!-- TODO - only add this if slideshow is turned on -->
<script data-js="dialog-content-" type="text/template">
	<div class="c-dialog">
		<div class="c-dialog__overlay">
			<div class="c-dialog__overlay-inner">
				<div class="c-dialog__content-wrapper">
					<div class="c-dialog__content-inner">
	
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
