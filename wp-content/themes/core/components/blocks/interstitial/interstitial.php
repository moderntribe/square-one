<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\interstitial\Interstitial_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Interstitial_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( $c->media ) { ?>
			<div <?php echo $c->get_media_classes(); ?>>
				<?php get_template_part(
					'components/image/image',
					null,
					$c->get_media_args()
				); ?>
			</div>
		<?php } ?>

		<?php if ( ! empty( $c->get_content_args() ) ) { ?>
			<div <?php echo $c->get_content_classes(); ?>>
				<?php get_template_part(
					'components/content_block/content_block',
					null,
					$c->get_content_args()
				); ?>
			</div>
		<?php } ?>

	</div>
</section>
