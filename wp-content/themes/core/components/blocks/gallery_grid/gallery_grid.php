<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\gallery_grid\Gallery_Grid_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attributes(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<div class="b-gallery-grid__header">
			<?php if ( ! empty( $c->get_header_args() ) ) { ?>
				<?php get_template_part(
					'components/content_block/content_block',
					null,
					$c->get_header_args()
				); ?>
			<?php } ?>

			<?php if ( $c->is_slideshow() ) : ?>
				<div class="b-gallery-grid__cta">
					<?php get_template_part( 'components/button/button', null, $c->get_slideshow_button() ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php foreach ( $c->get_gallery_img_thumbs() as $img ) {
				get_template_part( 'components/image/image', null, $img );
			} ?>
		</div>
	
	</div>
</section>

<?php if ( $c->is_slideshow() ) : ?>
	<?php get_template_part(
		'components/dialog/dialog',
		null,
		$c->get_dialog_args(),
	) ?>
<?php endif; ?>
