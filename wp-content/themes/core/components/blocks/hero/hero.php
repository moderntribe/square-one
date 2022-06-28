<?php declare(strict_types=1);

/**
 * @var \Tribe\Project\Templates\Components\blocks\hero\src\Hero_Block_Controller $c The block controller.
 */
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( ! empty( $c->get_image_args() ) ) { ?>
			<div <?php echo $c->get_media_classes(); ?> >
				<?php get_template_part(
					'components/image/image',
					null,
					$c->get_image_args()
				); ?>
			</div>
		<?php } ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_content_args()
			); ?>
		</div>
	</div>
</section>
