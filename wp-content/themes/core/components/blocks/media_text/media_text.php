<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\media_text\Media_Text_Block_Controller;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Media_Text_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<div <?php echo $c->get_media_classes(); ?>>
			<?php if ( $c->get_media_type() === Media_Text_Block::IMAGE && ! empty( $c->get_image_args() ) ) {
				get_template_part(
					'components/image/image',
					null,
					$c->get_image_args()
				);
			} elseif ( $c->get_media_type() === Media_Text_Block::EMBED && ! empty( $c->get_video_embed() ) ) {
				echo $c->get_video_embed();
			} ?>
		</div>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_content_args()
			) ?>
		</div>

	</div>
</section>
